<?php
/**
 * Created by PhpStorm.
 * User: michal
 * Date: 14.4.16
 * Time: 14:23
 */

namespace Fagin\Controller;

use Fagin\Service\CarOperation;
use Fagin\Service\FaginSearchService;


class FaginController extends Controller {

    /** @var CarOperation $carOperation */
    private $carOperation;

    /** @var FaginSearchService $faginService */
    private $faginService;

    public function __construct($twig) {
        parent::__construct($twig);
        $this->carOperation = new CarOperation();
        $this->faginService = new FaginSearchService();
    }

    /**
     * Vyrenderuje vyhledavaci formular
     *
     * @return mixed
     */
    public function findAction() {
        return $this->render('fagin/find.html.twig');
    }

    /**
     * Vrati JSON vsech aut.
     *
     * @return string
     */
    public function getAllCarsAction() {
        $cars = json_encode($this->carOperation->getAllCars());
        return $cars;
    }

    /**
     * Vyrenderuje stranku s konkretnim automobilem
     *
     * @param $id
     * @return mixed
     */
    public function carDetailAction($id) {
        if (!is_int($id)) {
            return $this->response(404);
        }
        $car = $this->carOperation->getCarById($id);
        if ($car == null) {
            return $this->response(404);
        }
        return $this->render('fagin/car-detail.html.twig', array('car' => $car));
    }

}