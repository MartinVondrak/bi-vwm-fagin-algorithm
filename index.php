<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 17.3.16
 * Time: 22:53
 */

require_once 'autoload.php';


use Fagin\Controller\AlgorithmController;
use Fagin\Controller\CarController;
use Fagin\Controller\StaticController;
use Fagin\Controller\TestController;


$uri = explode('/',$_SERVER['REQUEST_URI'],4);

$algorithmController = new AlgorithmController($twig);
$carController = new CarController($twig);
$staticController = new StaticController($twig);
$testController = new TestController($twig);

if (count($uri) == 2) {
    if($uri[1] == NULL) {
        echo $staticController->indexAction();
    }
}
elseif (count($uri) == 3) {
    if ($uri[2] == NULL) {
        if ($uri[1] == "test") {
            echo $testController->testAction();
        }
        elseif ($uri[1] == "testform") {
            echo $testController->testFormAction();
        }
        elseif ($uri[1] == "vyhledat") {
            echo $algorithmController->findFormAction();
        }
        elseif ($uri[1] == "vlozit") {
            echo $carController->insertAction();
        }
        else {
            echo $staticController->response(404);
        }
    }
    else {
        echo $staticController->response(404);
    }
}
else {
    if ($uri[3] == NULL) {
        if ($uri[1] == "car") {
            echo $carController->detailAction($uri[2]);
        }
        elseif ($uri[1] == "api") {
            if ($uri[2] == "get-all-cars") {
                echo $algorithmController->getAllCarsAction();
            }
            elseif ($uri[2] == "find-cars") {
                echo $algorithmController->findCarsAction();
            }
            else {
                echo $staticController->response(404);
            }
        }
        else {
            echo $staticController->response(404);
        }
    }
    else {
        echo $staticController->response(404);
    }
}