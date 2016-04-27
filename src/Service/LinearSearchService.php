<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 27.4.16
 * Time: 12:38
 */

namespace Fagin\Service;


class LinearSearchService extends AbstractSearchService {

    /**
     * Realizuje sekvencni algoritmus.
     * Vrati Top K aut pro zadane parametry, k a agregacni funkci.
     *
     * @param string[] $params
     * @param int      $k
     * @param string   $aggregation
     * @return Car[]|string
     */
    public function getKProductsWithParams($params, $k, $aggregation) {

    }
}