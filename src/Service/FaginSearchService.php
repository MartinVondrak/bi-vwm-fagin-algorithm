<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 24.4.16
 * Time: 21:27
 */

namespace Fagin\Service;


use Fagin\Data\Car;
use Fagin\Exception\InvalidParamException;
use Fagin\Exception\InvalidAggregationFunctionException;

class FaginSearchService extends AbstractSearchService {

    /**
     * Realizuje Faginuv algoritmus.
     * Vrati Top K aut pro zadane parametry, k a agregacni funkci.
     *
     * @param string[] $params
     * @param int      $k
     * @param string   $aggregation
     * @return Car[]|string
     */
    public function getKProductsWithParams($params, $k, $aggregation) {
        try {
            $this->timeLogger->start();
            $normalizedTables = $this->getNormalizedTablesForParams($params);
            $this->timeLogger->stop('Getting normalized table for params: ' . implode(', ', $params) . '.');
        } catch (InvalidParamException $ex) {
            return $ex->getMessage();
        }

        $this->timeLogger->start();
        $carsForAggregation = $this->getProductsForAggregation($normalizedTables, $k, count($params), $params);
        $this->timeLogger->stop('Return from getProductsForAggregation.');

        try {
            $sortedCars = $this->aggregateAndSortProducts($carsForAggregation, $aggregation);
        } catch (InvalidAggregationFunctionException $ex) {
            return $ex->getMessage();
        } catch (InvalidParamException $ex) {
            return $ex->getMessage();
        }

        $this->timeLogger->start();
        $cars = $this->getTopKCars($sortedCars, $k);
        $this->timeLogger->stop('Get final top ' . $k . ' products.');
        return $cars;
    }

    /**
     * Vrati pole k nejlepsich aut ze $sorted pole.
     *
     * @param array $sorted
     * @param int   $k
     * @return Car[]
     */
    private function getTopKCars($sorted, $k) {
        $carIds = array();
        reset($sorted);

        for ($i = 0; $i < $k; $i++) {
            $carIds[] = key($sorted);
            next($sorted);
        }

        $cars = $this->database->fetchCarByIds($carIds);
        return $cars;
    }

    /**
     * Agreguje jednotlive parametry auta a seradi je.
     *
     * @param array  $cars
     * @param string $aggregationFunction
     * @return array
     * @throws InvalidAggregationFunctionException
     * @throws InvalidParamException
     */
    private function aggregateAndSortProducts($cars, $aggregationFunction) {
        foreach ($cars as $id => $params) {
            switch ($aggregationFunction) {
                case self::MAX:
                    $aggregatedValues = max($params);
                    break;
                case self::MIN:
                    $aggregatedValues = min($params);
                    break;
                case self::AVG:
                    $aggregatedValues = $this->avg($params);
                    break;
                default:
                    throw new InvalidAggregationFunctionException($aggregationFunction);
                    break;
            }

            $cars[$id]['aggregation'] = $aggregatedValues;
        }

        $this->timeLogger->start();
        uasort($cars, array('self', 'sortCarsDesc'));
        $this->timeLogger->stop('Sorting cars');
        return $cars;
    }

    /**
     * Vrati pole aut pripravenych pro agregacni funkci.
     *
     * @param \ArrayObject[] $tables
     * @param int            $k
     * @param int            $paramCount
     * @param string[]       $params
     * @return array
     */
    private function getProductsForAggregation($tables, $k, $paramCount, $params) {
        $this->timeLogger->stop('Call of getProductsForAggregation.');
        $carArray = array();
        $carFound = 0;
        $index = 0;

        if (empty($tables)) {
            return $carArray;
        }

        $this->timeLogger->start();

        while ($carFound < $k) {
            foreach ($tables as $param => $table) {
                $it = $table->getIterator();
                $it->seek($index);

                if (!isset($carArray[$it->key()])) {
                    $carArray[$it->key()] = array();
                }

                $carArray[$it->key()][$param] = array();
                $carArray[$it->key()][$param] = $it->current();

                if (count($carArray[$it->key()]) == $paramCount) {
                    $carFound++;
                }
            }

            $index++;
        }

        $this->timeLogger->stop('Getting ' . $k . ' complete products.');
        $this->timeLogger->start();

        foreach ($params as $param) {
            foreach ($carArray as $carId => $carParams) {
                if (!isset($carArray[$carId][$param])) {
                    $carArray[$carId][$param] = $tables[$param][$carId];
                }
            }
        }

        $this->timeLogger->stop('Getting cross links');
        $this->timeLogger->start();
        return $carArray;
    }

    /**
     * Vrati pole s normalizovanymi tabulkami podle zadanych parametru.
     *
     * @param string[] $params
     * @return \ArrayObject[]
     * @throws InvalidParamException
     */
    private function getNormalizedTablesForParams($params) {
        $normalizedTables = array();

        foreach ($params as $param) {
            $normalizedTables[$param] = new \ArrayObject($this->database->fetchNormalizedParamTable($param));
        }

        return $normalizedTables;
    }

    /**
     * Komparator pro razeni aut podle agregovanych hodnot sestupne.
     *
     * @param array $a
     * @param array $b
     * @return int
     */
    private static function sortCarsDesc($a, $b) {
        if ($a['aggregation'] < $b['aggregation']) {
            return 1;
        }

        return -1;
    }

}