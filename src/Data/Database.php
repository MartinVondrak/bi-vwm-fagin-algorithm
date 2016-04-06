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

class Database {
    /** @var  PDO */
    private $database;

    /**
     * Database constructor.
     */
    public function __construct() {
        $ini_file = dirname(__FILE__) . '/../../config.ini';
        $params = $this->paramsFromIniFile($ini_file);

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
     * Vrati objekt Car pro id dane parametrem.
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
     * Vrati pole vsech objektu Car v databazi.
     *
     * @return Car[]
     */
    public function fetchAllCars() {
        $query = $this->database->prepare('SELECT * FROM `car`');
        $query->setFetchMode(PDO::FETCH_CLASS, 'Fagin\Data\Car');
        $query->execute();
        $cars = $query->fetchAll();
        return $cars;
    }

    /**
     * Vlozi objekt Car do databaze.
     *
     * @param Car $car
     * @return bool
     */
    public function insertCar(Car $car) {
        return true;
    }

    /**
     * Vrati pripojeni do DB.
     *
     * @return PDO
     */
    public function getDatabase() {
        return $this->database;
    }

    /**
     * Rozparsuje konfiguracni ini soubor a vrati pole s informacemi pro pripojeni do DB.
     *
     * @param string $ini_file
     * @return string
     */
    private function paramsFromIniFile($ini_file) {
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