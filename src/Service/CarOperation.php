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
use Fagin\Exception\NormalizationErrorException;

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

    /**
     * Normalizuje vsechny parametry vsech aut.
     *
     * @throws NormalizationErrorException
     */
    public function normalize() {
        foreach (Car::PARAMS as $param) {
            if (!$this->normalizeParam($param)) {
                throw new NormalizationErrorException($param);
            }
        }
    }

    /**
     * Normalizuje dany parametr pro vsechna auta.
     *
     * @param string $param
     * @return bool
     * @throws \Fagin\Exception\InvalidParamException
     */
    private function normalizeParam($param) {
        $max = $this->database->getCarMaxParam($param);
        $min = $this->database->getCarMinParam($param);
        $cars = $this->database->fetchAllCars();
        $normalizedTable = array();

        foreach ($cars as $car) {
            $value = $car->getParam($param);
            $normalizedValue = $this->normalizeValue($value, $param, $min, $max);
            $normalizedTable[$car->getId()] = $normalizedValue;
        }

        return $this->database->saveNormalizedTable($normalizedTable, $param);
    }

    /**
     * Normalizuje danou hodnotu, rozlisuje hodnoty podle prime a neprime umery.
     *
     * @param mixed  $value
     * @param string $param
     * @param mixed  $min
     * @param mixed  $max
     * @return float|int
     */
    private function normalizeValue($value, $param, $min, $max) {
        $denominator = $max - $min;

        if ($denominator == 0) {
            return 1;
        }

        if ($param == Car::MILEAGE or $param == Car::ACCELERATION or $param == Car::PRICE) {
            return ($max - $value) / $denominator;
        } else {
            return 1 - ($max - $value) / $denominator;
        }
    }

    /**
     * Vrati vsechny auta z databaze.
     *
     * @return Car[]
     */
    public function getAllCars() {
        return $this->database->fetchAllCars();
    }

    /**
     * Vrati auto z databaze dle zadaneho id
     *
     * @param $id
     * @return Car
     */
    public function getCarById($id) {
        return $this->database->fetchCar($id);
    }
}