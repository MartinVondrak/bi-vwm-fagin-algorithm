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


$uri = explode('/', $_SERVER['REQUEST_URI'], 4);

$algorithmController = new AlgorithmController($twig);
$carController = new CarController($twig);
$staticController = new StaticController($twig);

if (count($uri) == 2) {
    if ($uri[1] == null) {
        echo $staticController->indexAction();
    }
} elseif (count($uri) == 3) {
    if ($uri[2] == null) {
        if ($uri[1] == "vyhledat") {
            echo $algorithmController->findFormAction();
        } elseif ($uri[1] == "vlozit-automobil") {
            echo $carController->insertAction();
        } elseif ($uri[1] == "o-projektu") {
            echo $staticController->aboutAction();
        } else {
            echo $staticController->response(404);
        }
    } else {
        echo $staticController->response(404);
    }
} else {
    if ($uri[3] == null) {
        if ($uri[1] == "car") {
            echo $carController->detailAction($uri[2]);
        } elseif ($uri[1] == "api") {
            if ($uri[2] == "get-all-cars") {
                echo $algorithmController->getAllCarsAction();
            } elseif ($uri[2] == "find-cars") {
                echo $algorithmController->findCarsAction();
            } elseif ($uri[2] == "normalize") {
                echo $carController->normalizeAction();
            } else {
                echo $staticController->response(404);
            }
        } else {
            echo $staticController->response(404);
        }
    } else {
        echo $staticController->response(404);
    }
}