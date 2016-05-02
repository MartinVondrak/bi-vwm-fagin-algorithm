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
use Fagin\Exception\InvalidTopKException;

class FaginSearchService extends AbstractSearchService {

    /**
     * Realizuje Faginuv algoritmus.
     * Vrati Top K aut pro zadane parametry, k a agregacni funkci.
     *
     * @param string[] $params
     * @param int      $k
     * @param string   $aggregation
     * @return Car[]
     * @throws InvalidAggregationFunctionException
     * @throws InvalidTopKException;
     */
    public function getKProductsWithParams($params, $k, $aggregation) {
        $this->timeLogger->logMessage('--------- FAGIN ---------');
        $k = $this->validateTopK($k);
        $this->timeLogger->start();
        $normalizedTables = $this->getNormalizedTablesForParams($params);
        $this->timeLogger->stop('Getting normalized table for params: ' . implode(', ', $params) . '.');
        $carsForAggregation = $this->getProductsForAggregation($normalizedTables, $k, count($params), $params);
        $sortedCars = $this->aggregateAndSortProducts($carsForAggregation, $aggregation);
        $this->timeLogger->start();
        $cars = $this->getTopKCars($sortedCars, $k);
        $this->timeLogger->stop('Get final top ' . $k . ' products.');
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
        /** @var \ArrayIterator[] $iterators */
        $iterators = array();

        if (empty($tables)) {
            return $carArray;
        }

        foreach ($tables as $param => $table) {
            $iterators[$param] = $table->getIterator();
        }

        $this->timeLogger->start();

        while ($carFound < $k) {
            foreach ($tables as $param => $table) {
                $it = $iterators[$param];

                if (!isset($carArray[$it->key()])) {
                    $carArray[$it->key()] = array();
                }

                $carArray[$it->key()][$param] = array();
                $carArray[$it->key()][$param] = $it->current();

                if (count($carArray[$it->key()]) == $paramCount) {
                    $carFound++;
                }

                $iterators[$param]->next();
            }
        }

        $this->timeLogger->stop('Getting ' . $k . ' complete products.');
        $this->timeLogger->logMessage('Got ' . count($carArray) . ' incomplete cars');
        $this->timeLogger->start();

        foreach ($params as $param) {
            foreach ($carArray as $carId => $carParams) {
                if (!isset($carArray[$carId][$param])) {
                    $carArray[$carId][$param] = $tables[$param][$carId];
                }
            }
        }

        $this->timeLogger->stop('Getting cross links');
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

}