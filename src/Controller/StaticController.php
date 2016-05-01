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

}