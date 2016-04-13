<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 10.4.16
 * Time: 13:12
 */

namespace Fagin\Service;


use Fagin\Data\Car;
use Fagin\Data\Database;

class CarOperation {

    /** @var Database $database */
    private $database;

    /**
     * CarOperation konstruktor.
     */
    public function __construct() {
        $this->database = new Database();
    }

    /**
     * Vlozi auto do DB a pripadne normalizuje tabulky.
     *
     * @param Car  $car
     * @param bool $normalize
     * @return int|null
     */
    public function insertCar(Car $car, $normalize = false) {
        $car_id = $this->database->insertCar($car);

        if ($normalize and is_int($car_id)) {
            $this->normalize();
        }

        return $car_id;
    }

    public function normalize() {
        foreach (Car::PARAMS as $param) {
            $this->normalizeParam($param);
        }
    }

    private function normalizeParam($param) {
        $max = $this->database->getCarMaxParam($param);
        $min = $this->database->getCarMinParam($param);
        $cars = $this->database->fetchAllCars();
        $normalizedTable = array();

        foreach ($cars as $car) {
            $value = $car->getParam($param);
            $normalizedValue = $this->normalizeValue($value, $param, $min, $max);
            $normalizedTable[] = array('id' => $car->getId(), $param => $normalizedValue);
            var_dump($normalizedTable);
        }

        return true;
    }

    private function normalizeValue($value, $param, $min, $max) {
        $denominator = $max - $min;

        if ($denominator == 0) {
            $denominator = 1;
        }

        if ($param == Car::VOLUME or $param == Car::POWER or Car::TOP_SPEED) {
            return 1 - ($max - $value) / $denominator;
        } else {
            return ($max - $value) / $denominator;
        }
    }
}