<?php
/**
 * Created by PhpStorm.
 * User: michal
 * Date: 1.5.16
 * Time: 16:36
 */

namespace Fagin\Controller;

use Fagin\Exception\InvalidParamException;
use Fagin\Exception\InvalidTopKException;
use Fagin\Service\CarOperation;

class CarController extends Controller {

    /** @var CarOperation $carOperation */
    private $carOperation;

    /**
     * StaticController konstruktor.
     *
     * @param \Twig_Environment $twig
     */
    public function __construct($twig) {
        parent::__construct($twig);
        $this->carOperation = new CarOperation();
    }

    /**
     * Vyrenderuje stranku s konkretnim automobilem
     *
     * @param $id
     * @return mixed
     */
    public function detailAction($id) {
        if (!is_numeric($id)) {
            return $this->response(404);
        }
        $car = $this->carOperation->getCarById($id);
        if ($car == null) {
            return $this->response(404);
        }
        return $this->render("car/detail.html.twig", array("car" => $car));
    }

    public function insertAction() {
        if ($_POST) {
            try{
                $car = $this->carOperation->createCar($_POST["name"],$_POST["volume"],$_POST["power"],$_POST["mileage"],
                    $_POST["manufacture_year"],$_POST["top_speed"],$_POST["acceleration"],$_POST["price"]);
                $car_id = $this->carOperation->insertCar($car,true);
            } catch (InvalidParamException $ex) {
                header(self::CODES[400]);
                return $this->render("car/insert.html.twig", array("car" => $car, "error" => $ex->getMessage()));
            }
            $_SERVER["REQUEST_URI"] = "/car/".$car_id."/";
            return $this->detailAction($car_id);
        }
        else {
            return $this->render("car/insert.html.twig");
        }
    }

}