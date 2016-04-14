<?php
/**
 * Created by PhpStorm.
 * User: michal
 * Date: 14.4.16
 * Time: 14:23
 */

namespace Fagin\Controller;

use Fagin\Service\CarOperation;


class FaginController extends Controller
{
    public function findAction() {
        return $this->render("fagin/find.html.twig",array('cars' => NULL));
    }

    public function carDetailAction($id) {
        if(!is_numeric($id)) {
            return $this->response(404);
        }
        $operation = new CarOperation();
        $car = $operation->getCarById($id);
        if ($car == NULL){
            return $this->response(404);
        }
        return $this->render("fagin/car-detail.html.twig",array('car' => $car));
    }

}