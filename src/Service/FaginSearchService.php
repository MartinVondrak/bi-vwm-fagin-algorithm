<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 24.4.16
 * Time: 21:27
 */

namespace Fagin\Service;


use Fagin\Data\Database;
use Fagin\Exception\InvalidParamException;

class FaginSearchService {

    /** @var Database $database */
    private $database;

    /**
     * SearchService konstruktor.
     */
    public function __construct() {
        $this->database = new Database();
    }

    public function getKProductsWithParams($params, $k) {
        try {
            $normalizedTables = $this->getNormalizedTablesForParams($params);
        } catch (InvalidParamException $ex) {
            return $ex->getMessage();
        }

        $valuesForAggregation = $this->getProductsForAggregation($normalizedTables, $k, count($params), $params);
        return $valuesForAggregation;
    }

    /**
     * Vrati pole aut pripravenych pro agregacni funkci.
     *
     * @param \ArrayObject[] $tables
     * @param int            $k
     * @param int            $paramCount
     * @param array          $params
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
     * @param array $params
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