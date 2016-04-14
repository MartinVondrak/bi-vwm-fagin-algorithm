<?php
/**
 * Created by PhpStorm.
 * User: michal
 * Date: 14.4.16
 * Time: 14:23
 */

namespace Fagin\Controller;


class FaginController extends Controller
{
    public function findAction() {
        return $this->render("find.html.twig",array('cars' => NULL));
    }

}