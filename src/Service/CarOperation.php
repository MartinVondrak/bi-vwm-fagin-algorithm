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
            $this->normalizeCarsToDb();
        }

        return $car_id;
    }

    /**
     * Normalizuje vsechny parametry vsech aut.
     *
     * @throws NormalizationErrorException
     */
    public function normalizeCarsToDb() {
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

    /**
     * Normalizuje predane parametry u predanych aut a vrati je v asociativnim poli.
     *
     * @param Car[]    $cars
     * @param string[] $params
     * @return array
     * @throws \Fagin\Exception\InvalidParamException
     */
    public function normalizeCarsToArray($cars, $params) {
        $normalizedCars = array();
        $min = array();
        $max = array();

        foreach ($params as $param) {
            $max[$param] = $this->database->getCarMaxParam($param);
            $min[$param] = $this->database->getCarMinParam($param);
        }

        foreach ($cars as $car) {
            $normalizedCars[$car->getId()] = array();

            foreach ($params as $param) {
                $normalizedCars[$car->getId()][$param] = $this->normalizeValue($car->getParam($param), $param, $min[$param], $max[$param]);
            }
        }

        return $normalizedCars;
    }

}