<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 18.3.16
 * Time: 18:45
 */

namespace Fagin\Data;


use Fagin\Exception\InvalidParamException;

class Car implements \JsonSerializable {

    const VOLUME = 'volume';
    const POWER = 'power';
    const MILEAGE = 'mileage';
    const MANUFACTURE_YEAR = 'manufacture_year';
    const TOP_SPEED = 'top_speed';
    const ACCELERATION = 'acceleration';
    const PRICE = 'price';

    /** @var array PARAMS */
    const PARAMS = array(
        self::VOLUME,
        self::POWER,
        self::MILEAGE,
        self::MANUFACTURE_YEAR,
        self::TOP_SPEED,
        self::ACCELERATION,
        self::PRICE
    );

    /** @var int $id */
    private $id;

    /** @var string $name */
    private $name;

    /** @var int $volume */
    private $volume;

    /** @var int $power */
    private $power;

    /** @var int $mileage */
    private $mileage;

    /** @var int $manufacture_year */
    private $manufacture_year;

    /** @var int $top_speed */
    private $top_speed;

    /** @var double $acceleration */
    private $acceleration;

    /** @var int $price */
    private $price;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Car
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getVolume() {
        return $this->volume;
    }

    /**
     * @param int $volume
     * @return Car
     */
    public function setVolume($volume) {
        $this->volume = $volume;
        return $this;
    }

    /**
     * @return int
     */
    public function getPower() {
        return $this->power;
    }

    /**
     * @param int $power
     * @return Car
     */
    public function setPower($power) {
        $this->power = $power;
        return $this;
    }

    /**
     * @return int
     */
    public function getMileage() {
        return $this->mileage;
    }

    /**
     * @param int $mileage
     * @return Car
     */
    public function setMileage($mileage) {
        $this->mileage = $mileage;
        return $this;
    }

    /**
     * @return int
     */
    public function getManufactureYear() {
        return $this->manufacture_year;
    }

    /**
     * @param int $manufacture_year
     * @return Car
     */
    public function setManufactureYear($manufacture_year) {
        $this->manufacture_year = $manufacture_year;
        return $this;
    }

    /**
     * @return int
     */
    public function getTopSpeed() {
        return $this->top_speed;
    }

    /**
     * @param int $top_speed
     * @return Car
     */
    public function setTopSpeed($top_speed) {
        $this->top_speed = $top_speed;
        return $this;
    }

    /**
     * @return float
     */
    public function getAcceleration() {
        return $this->acceleration;
    }

    /**
     * @param float $acceleration
     * @return Car
     */
    public function setAcceleration($acceleration) {
        $this->acceleration = $acceleration;
        return $this;
    }

    /**
     * @return int
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * @param int $price
     * @return Car
     */
    public function setPrice($price) {
        $this->price = $price;
        return $this;
    }

    /**
     * @param string $param
     * @return mixed
     * @throws InvalidParamException
     */
    public function getParam($param) {
        if (!in_array($param, self::PARAMS)) {
            throw new InvalidParamException($param);
        }

        return $this->$param;
    }

    /**
     * Prevede objekt Car na asociativni pole pro funkci json_encode().
     *
     * @return array
     */
    public function jsonSerialize() {
        return $this->toArray();
    }

    /**
     * Prevede objekt na asociativni pole.
     *
     * @return array
     */
    public function toArray() {
        $cars = array();
        $cars['id'] = $this->id;
        $cars['name'] = $this->name;

        foreach (self::PARAMS as $param) {
            try {
                $cars[$param] = $this->getParam($param);
            } catch (InvalidParamException $ex) {
                return array();
            }
        }

        return $cars;
    }

}