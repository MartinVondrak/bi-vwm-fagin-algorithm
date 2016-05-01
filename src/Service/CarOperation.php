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
use Fagin\Exception\InvalidParamException;
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
     * Vytvori entitu Car
     *
     * @param $name
     * @param $volume
     * @param $power
     * @param $mileage
     * @param $manufacture_year
     * @param $top_speed
     * @param $acceleration
     * @param $price
     * @return Car
     */
    public function createCar($name, $volume, $power, $mileage, $manufacture_year, $top_speed, $acceleration, $price) {
        $car = new Car();
        $car->setName($this->transformInput($name));
        $car->setVolume($this->transformInput($volume));
        $car->setPower($this->transformInput($power));
        $car->setMileage($this->transformInput($mileage));
        $car->setManufactureYear($this->transformInput($manufacture_year));
        $car->setTopSpeed($this->transformInput($top_speed));
        $car->setAcceleration($this->transformInput($acceleration));
        $car->setPrice($this->transformInput($price));
        return $car;
    }

    /**
     * Vlozi auto do DB a pripadne normalizuje tabulky.
     *
     * @param bool $normalize
     * @return int|null
     */
    public function insertCar(Car $car, $normalize = false) {

        $this->validateCar($car);
        $car_id = $this->database->insertCar($car);

        if ($normalize and preg_match("/^\d*/",$car_id)) {
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

    /**
     * Zvaliduje entitu Car
     *
     * @param Car $car
     * @throws InvalidParamException
     */
    private function validateCar($car) {
        if (empty($car->getName())) {
            throw new InvalidParamException("Invalid name.");
        }
        if (empty($car->getVolume()) || !preg_match("/^\d*$/",$car->getVolume())) {
            var_dump($car->getVolume());
            throw new InvalidParamException("Invalid volume.");
        }
        if (empty($car->getPower()) || !preg_match("/^\d*$/",$car->getPower())) {
            throw new InvalidParamException("Invalid power.");
        }
        if (empty($car->getMileage()) || !preg_match("/^\d*$/",$car->getMileage())) {
            throw new InvalidParamException("Invalid mileage.");
        }
        if (empty($car->getManufactureYear()) || !preg_match("/^\d*$/",$car->getManufactureYear())) {
            throw new InvalidParamException("Invalid manufacture year.");
        }
        if (empty($car->getTopSpeed()) || !preg_match("/^\d*$/",$car->getTopSpeed())) {
            throw new InvalidParamException("Invalid top speed.");
        }
        if (empty($car->getAcceleration()) || !preg_match("/^\d*\.?\d*$/",$car->getAcceleration())) {
            throw new InvalidParamException("Invalid acceleration.");
        }
        if (empty($car->getPrice()) || !preg_match("/^\d*$/",$car->getPrice())) {
            throw new InvalidParamException("Invalid price.");
        }
    }

    /**
     * Otestuje vstup
     *
     * @param $data
     * @return string
     */
    private function transformInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

}