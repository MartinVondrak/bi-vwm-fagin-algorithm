<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 18.3.16
 * Time: 18:45
 */

namespace Fagin\Data;


class Car {

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


}