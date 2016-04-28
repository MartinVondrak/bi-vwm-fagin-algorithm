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
     * @return Car[]|string
     */
    public function getKProductsWithParams($params, $k, $aggregation) {
        $this->timeLogger->start();
        $cars = $this->database->fetchAllCars();
        $this->timeLogger->stop('LINEAR - Get all cars.');
        $this->timeLogger->start();
        $normalizedCars = $this->carOperation->normalizeCarsToArray($cars, $params);
        $this->timeLogger->stop('LINEAR - Normalize all cars.');
        $sortedCars = $this->aggregateAndSortProducts($cars, $aggregation);
    }

    private function aggregateAndSortProducts($cars, $aggregationFunction) {
        $this->timeLogger->start();

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

        $this->timeLogger->stop('LINEAR - Calculating aggregated values');
        $this->timeLogger->start();
        uasort($cars, array('self', 'sortCarsDesc'));
        $this->timeLogger->stop('LINEAR - Sorting cars');
        return $cars;
    }
}