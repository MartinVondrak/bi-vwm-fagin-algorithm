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
            echo time()."<br>";
            $normalizedTables = $this->getNormalizedTablesForParams($params);
            echo time()."<br>";
        } catch (InvalidParamException $ex) {
            return $ex->getMessage();
        }
        $tempArr = array();
        $carCount = 0;
        $i = 0;
        echo time()."<br>";
        while ($carCount < $k) {
            foreach ($normalizedTables as $table) {
                if (!key_exists($table[$i]['id'], $tempArr)) {
                    $tempArr[$table[$i]['id']] = array();
                }

                $tempArr[$table[$i]['id']][] = $table[$i];
            }
            $i++;
            $carCount++;
        }
        echo time()."<br>";

        return $tempArr;
    }

    /**
     * Vrati pole s normalizovanymi tabulkami podle zadanych parametru.
     *
     * @param array $params
     * @return array
     * @throws InvalidParamException
     */
    public function getNormalizedTablesForParams($params) {
        $normalizedTables = array();

        foreach ($params as $param) {
            $normalizedTables[] = $this->database->fetchNormalizedParamTable($param);
        }

        return $normalizedTables;
    }

}