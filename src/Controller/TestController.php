<?php
/**
 * Created by PhpStorm.
 * User: michal
 * Date: 13.4.16
 * Time: 22:42
 */

namespace Fagin\Controller;

use Fagin\Service\CarOperation;


class TestController extends Controller
{
    public function testAction() {
        $operation = new CarOperation();
        $timeStart = microtime(true);
        $cars = $operation->getAllCars();
        $timeEnd = microtime(true);
        $dbTime = $timeEnd - $timeStart;

        return $this->render("test/test.html.twig", array('cars' => $cars, 'dbTime' => round($dbTime,4)));
    }
}