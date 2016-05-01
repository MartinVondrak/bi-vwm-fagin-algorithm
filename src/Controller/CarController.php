<?php
/**
 * Created by PhpStorm.
 * User: michal
 * Date: 1.5.16
 * Time: 16:36
 */

namespace Fagin\Controller;

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
        print_r($_POST);
        if ($_POST) {
            echo "Not empty";
        }
        return $this->render("car/insert.html.twig", array("car" => NULL));
    }

}