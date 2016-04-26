<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 24.4.16
 * Time: 21:27
 */

namespace Fagin\Service;


use Fagin\Data\Car;
use Fagin\Data\Database;
use Fagin\Exception\InvalidParamException;
use Fagin\Exception\InvalidAggregationFunctionException;

class FaginSearchService {

    /** @var Database $database */
    private $database;

    /**
     * SearchService konstruktor.
     */
    public function __construct() {
        $this->database = new Database();
    }

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
            $normalizedTables = $this->getNormalizedTablesForParams($params);
        } catch (InvalidParamException $ex) {
            return $ex->getMessage();
        }

        $carsForAggregation = $this->getProductsForAggregation($normalizedTables, $k, count($params), $params);

        try {
            $sortedCars = $this->aggregateAndSortProducts($carsForAggregation, $aggregation);
        } catch (InvalidAggregationFunctionException $ex) {
            return $ex->getMessage();
        } catch (InvalidParamException $ex) {
            return $ex->getMessage();
        }

        $cars = $this->getTopKCars($sortedCars, $k);
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
        $cars = array();
        reset($sorted);

        for ($i = 0; $i < $k; $i++) {
            $cars[] = $this->database->fetchCar(key($sorted));
            next($sorted);
        }

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
                case 'max':
                    $aggregatedValues = max($params);
                    break;
                case 'min':
                    $aggregatedValues = min($params);
                    break;
                case 'avg':
                    $aggregatedValues = $this->avg($params);
                    break;
                default:
                    throw new InvalidAggregationFunctionException($aggregationFunction);
                    break;
            }

            $cars[$id]['aggregation'] = $aggregatedValues;
        }

        uasort($cars, array('self', 'sortCarsDesc'));
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
        $carArray = array();
        $carFound = 0;
        $index = 0;

        if (empty($tables)) {
            return $carArray;
        }

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

        foreach ($params as $param) {
            foreach ($carArray as $carId => $carParams) {
                if (!isset($carArray[$carId][$param])) {
                    $carArray[$carId][$param] = $tables[$param][$carId];
                }
            }
        }

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
     * Spocita prumer hodnot v poli.
     *
     * @param array $values
     * @return float
     * @throws InvalidParamException
     */
    private function avg($values) {
        if (!is_array($values)) {
            throw new InvalidParamException('Expected array ' . gettype($values) . 'given.');
        }

        return array_sum($values) / count($values);
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