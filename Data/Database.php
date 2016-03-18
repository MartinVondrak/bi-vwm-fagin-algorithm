<?php

/**
 * Created by PhpStorm.
 * User: martin
 * Date: 18.3.16
 * Time: 18:40
 */

namespace Fagin\Data;

use PDO;

class Database {
    /** @var  PDO */
    private $database;

    /**
     * Database constructor.
     */
    public function __construct() {
        $this->database = new PDO();
    }

    /**
     *
     * @param int $id
     * @return Car
     */
    public function fetchCar($id) {

    }


}