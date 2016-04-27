<?php
/**
 * Created by PhpStorm.
 * User: michal
 * Date: 14.4.16
 * Time: 22:18
 */

namespace Fagin\Controller;


class StaticController extends Controller
{
    /**
     * Vyrenderuje hlavni stranku
     *
     * @return mixed
     */
    public function indexAction() {
        return $this->render("static/index.html.twig");
    }

    /**
     * Vyrenderuje stranku s konkretnim automobilem
     *
     * @param $id
     * @return mixed
     */
    public function carDetailAction($id) {
        if (!is_numeric($id)) {
            return $this->response(404);
        }
        $car = $this->carOperation->getCarById($id);
        if ($car == null) {
            return $this->response(404);
        }
        return $this->render('fagin/car-detail.html.twig', array('car' => $car));
    }

}