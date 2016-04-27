<?php
/**
 * Created by PhpStorm.
 * User: michal
 * Date: 13.4.16
 * Time: 22:42
 */

namespace Fagin\Controller;

use Fagin\Data\Car;
use Fagin\Service\CarOperation;
use Fagin\Service\AbstractSearchService;
use Fagin\Service\FaginSearchService;


class TestController extends Controller
{
    public function testAction() {
        $agregation = $_POST["agregation"];
        $top_k = $_POST["top_k"];
        $params = explode(",",$_POST["params"]);
        var_dump($params);

        $operation = new FaginSearchService();
        $timeStart = microtime(true);
        $cars = $operation->getKProductsWithParams($params, $top_k, $agregation);
        //var_dump($cars);
        $timeEnd = microtime(true);
        $dbTime = $timeEnd - $timeStart;

        return $this->render("test/test.html.twig", array('cars' => $cars, 'dbTime' => round($dbTime,4)));
    }

    public function testFormAction () {
        return $this->render("test/testfind.html.twig");
    }
}