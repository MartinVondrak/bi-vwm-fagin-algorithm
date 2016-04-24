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

    public function getKProductsWithParams($params) {
        try {
            $normalizedTables = $this->getNormalizedTablesForParams($params);
        } catch (InvalidParamException $ex) {
            return $ex->getMessage();
        }

        return;
    }

    /**
     * Vrati pole s normalizovanymi tabulkami podle zadanych parametru.
     *
     * @param array $params
     * @return array
     * @throws InvalidParamException
     */
    private function getNormalizedTablesForParams($params) {
        $normalizedTables = array();

        foreach ($params as $param) {
            $normalizedTables[] = $this->database->fetchNormalizedParamTable($param);
        }

        return $normalizedTables;
    }

}