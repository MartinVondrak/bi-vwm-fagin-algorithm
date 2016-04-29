<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 27.4.16
 * Time: 12:38
 */

namespace Fagin\Service;


use Fagin\Data\Car;
use Fagin\Exception\InvalidAggregationFunctionException;
use Fagin\Exception\InvalidTopKException;

class LinearSearchService extends AbstractSearchService {

    /** @var CarOperation $carOperation */
    private $carOperation;

    /**
     * LinearSearchService constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->carOperation = new CarOperation();
    }

    /**
     * Realizuje sekvencni algoritmus.
     * Vrati Top K aut pro zadane parametry, k a agregacni funkci.
     *
     * @param string[] $params
     * @param int      $k
     * @param string   $aggregation
     * @return Car[]
     * @throws InvalidAggregationFunctionException
     * @throws InvalidTopKException
     */
    public function getKProductsWithParams($params, $k, $aggregation) {
        $k = $this->validateTopK($k);
        $this->timeLogger->logMessage('--------- LINEAR ---------');
        $this->timeLogger->start();
        $cars = $this->database->fetchAllCars();
        $this->timeLogger->stop('Get all cars.');
        $this->timeLogger->start();
        $normalizedCars = $this->carOperation->normalizeCarsToArray($cars, $params);
        $this->timeLogger->stop('Normalize all cars.');
        $sortedCars = $this->aggregateAndSortProducts($normalizedCars, $aggregation);
        $this->timeLogger->start();
        $cars = $this->getTopKCars($sortedCars, $k);
        $this->timeLogger->stop('Get final top ' . $k . ' products.');
        return $cars;
    }
}