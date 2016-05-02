<?php

/**
 * Created by PhpStorm.
 * User: martin
 * Date: 18.3.16
 * Time: 18:40
 */

namespace Fagin\Data;

use PDO;
use PDOException;
use Fagin\Exception\InvalidParamException;

class Database {

    /** @var  PDO */
    private $database;

    /**
     * Database konstruktor.
     */
    public function __construct() {
        $ini_file = dirname(__FILE__) . '/../../config.ini';
        $params = Database::paramsFromIniFile($ini_file);

        try {
            $this->database = new PDO(
                $params['conn_string'],
                $params['user'],
                $params['password'],
                array(PDO::ATTR_PERSISTENT => true)
            );
        } catch (PDOException $ex) {
            echo 'ERROR ' . $ex->getCode() . ': ' . $ex->getMessage() . ' in ' . $ex->getFile() . ' on line ' . $ex->getLine() . '.<br>';
            exit;
        }
    }

    /**
     * Vrati auto podle ID.
     *
     * @param int $id
     * @return Car
     */
    public function fetchCar($id) {
        $query = $this->database->prepare('SELECT * FROM `car` WHERE `id` = :id');
        $query->setFetchMode(PDO::FETCH_CLASS, 'Fagin\Data\Car');
        $query->execute(array(':id' => $id));
        $car = $query->fetch();
        return $car;
    }

    /**
     * Vraci auta podle zadaneho pole s ID.
     *
     * @param array $ids
     * @param bool  $associative
     * @return Car[]
     */
    public function fetchCarByIds($ids, $associative = false) {
        $query = $this->database->prepare('SELECT * FROM `car` WHERE `id` IN (' . str_repeat('?, ', count($ids) - 1) . '?)');

        if ($associative) {
            $query->setFetchMode(PDO::FETCH_ASSOC);
        } else {
            $query->setFetchMode(PDO::FETCH_CLASS, 'Fagin\Data\Car');
        }

        $query->execute($ids);
        return $query->fetchAll();
    }

    /**
     * Vrati vsechna auta jako pole.
     *
     * @return Car[]
     */
    public function fetchAllCars() {
        $query = $this->database->prepare('SELECT * FROM `car` ORDER BY `id`');
        $query->setFetchMode(PDO::FETCH_CLASS, 'Fagin\Data\Car');
        $query->execute();
        $cars = $query->fetchAll();
        return $cars;
    }

    /**
     * Vrati auto s nejvetsim zadanym parametrem.
     *
     * @param string $param
     * @return mixed
     * @throws InvalidParamException
     */
    public function getCarMaxParam($param) {
        if (!in_array($param, Car::PARAMS)) {
            throw new InvalidParamException($param);
        }

        $query = $this->database->prepare('SELECT * FROM `car` ORDER BY `' . $param . '` DESC LIMIT 1');
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute();
        $car = $query->fetch();
        return $car[$param];
    }

    /**
     * Vrati auto s nejmensim zadanym parametrem.
     *
     * @param string $param
     * @return mixed
     * @throws InvalidParamException
     */
    public function getCarMinParam($param) {
        if (!in_array($param, Car::PARAMS)) {
            throw new InvalidParamException($param);
        }

        $query = $this->database->prepare('SELECT * FROM `car` ORDER BY `' . $param . '` ASC LIMIT 1');
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute();
        $car = $query->fetch();
        return $car[$param];
    }

    /**
     * Vlozi objekt Car do databaze.
     * Vraci ID nove vlozeneho auta nebo null pri neuspechu.
     *
     * @param Car $car
     * @return int|null
     */
    public function insertCar(Car $car) {
        if ($car == null) {
            return null;
        }

        $query = $this->database->prepare('INSERT INTO `car` (`name`, `volume`, `power`, `mileage`, `manufacture_year`, `top_speed`, `acceleration`, `price`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $params = array(
            $car->getName(),
            $car->getVolume(),
            $car->getPower(),
            $car->getMileage(),
            $car->getManufactureYear(),
            $car->getTopSpeed(),
            $car->getAcceleration(),
            $car->getPrice()
        );

        if (!$query->execute($params)) {
            return null;
        }

        return $this->database->lastInsertId();
    }

    /**
     * Ulozi normalizovana data do prislusne tabulky a vrati true nebo false podle uspechu.
     *
     * @TODO neni mozna napsane uplne nejbezpecneji.
     * @param $normalizedTable
     * @param $param
     * @return bool
     * @throws InvalidParamException
     */
    public function saveNormalizedTable($normalizedTable, $param) {
        if (!in_array($param, Car::PARAMS)) {
            throw new InvalidParamException($param);
        }

        $table = 'car_' . $param;
        $this->database->query('TRUNCATE TABLE `' . $table . '`');
        $sql = 'INSERT INTO `' . $table . '` (`id`, `' . $param . '`) VALUES ';

        foreach ($normalizedTable as $id => $row) {
            $sql .= '(' . $id . ', ' . $row . '), ';
        }

        $sql = rtrim($sql, ", ");
        $query = $this->database->prepare($sql);
        return $query->execute();
    }

    /**
     * Vrati pole se zaznamy aut.
     * Kazdy zaznam je pole s ID auta a normalizovane hodnoty daneho parametru.
     *
     * @param string $param
     * @return array
     * @throws InvalidParamException
     */
    public function fetchNormalizedParamTable($param) {
        if (!in_array($param, Car::PARAMS)) {
            throw new InvalidParamException($param);
        }

        $table = 'car_' . $param;
        $sql = 'SELECT * FROM `' . $table . '` ORDER BY `' . $param . '` DESC';
        $query = $this->database->prepare($sql);
        $query->setFetchMode(PDO::FETCH_KEY_PAIR);
        $query->execute();
        $normalizedTable = $query->fetchAll();
        return $normalizedTable;
    }

    /**
     * Vrati pocet vsech aut v databazi.
     *
     * @return int
     */
    public function countCars() {
        $query = $this->database->prepare('SELECT COUNT(*) FROM `car`');
        $query->execute();
        $carCount = $query->fetch();
        return $carCount[0];
    }

    /**
     * Rozparsuje konfiguracni ini soubor a vrati pole s informacemi pro pripojeni do DB.
     *
     * @param string $ini_file
     * @return string
     */
    private static function paramsFromIniFile($ini_file) {
        $config = parse_ini_file($ini_file, true);
        $conn_string = $config['database']['type'] . ':';
        $conn_string .= 'host=' . $config['database']['host'] . ';';
        $conn_string .= 'port=' . $config['database']['port'] . ';';
        $conn_string .= 'dbname=' . $config['database']['name'] . ';';
        $conn_string .= 'charset=' . $config['database']['charset'];
        $params = array(
            'conn_string' => $conn_string,
            'user' => $config['database']['username'],
            'password' => $config['database']['password']
        );
        return $params;
    }
}