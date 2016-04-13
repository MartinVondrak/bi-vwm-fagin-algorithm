<?php
/**
 * Created by PhpStorm.
 * User: michal
 * Date: 13.4.16
 * Time: 22:42
 */

namespace Fagin\Controller;

use Fagin\Data\Database;


class TestController extends Controller
{
    public function testAction() {
        $db = new Database();
        $timeStart = microtime(true);
        $cars = $db->fetchAllCars();
        $timeEnd = microtime(true);
        $dbTime = $timeEnd - $timeStart;

        return $this->render("test.html.twig", array('cars' => $cars, 'dbTime' => round($dbTime,4)));
    }
}