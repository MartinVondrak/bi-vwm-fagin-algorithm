<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 17.3.16
 * Time: 22:53
 */

require_once 'autoload.php';


use Fagin\Controller\FaginController;
use Fagin\Controller\StaticController;
use Fagin\Controller\TestController;
use Fagin\Data\Car;
use Fagin\Service\AbstractSearchService;

/*
$twig->addGlobal("Car::VOLUME", Car::VOLUME);
$twig->addGlobal("Car::POWER", Car::POWER);
$twig->addGlobal("Car::MILEAGE", Car::MILEAGE);
$twig->addGlobal("Car::MANUFACTURE_YEAR", Car::MANUFACTURE_YEAR);
$twig->addGlobal("Car::TOP_SPEED", Car::TOP_SPEED);
$twig->addGlobal("Car::ACCELERATION", Car::ACCELERATION);
$twig->addGlobal("Car::PRICE", Car::PRICE);
$twig->addGlobal("AbstractSearchService::MIN", AbstractSearchService::MIN);
$twig->addGlobal("AbstractSearchService::MAX", AbstractSearchService::MAX);
$twig->addGlobal("AbstractSearchService::AVG", AbstractSearchService::AVG);
*/

$uri = explode('/',$_SERVER['REQUEST_URI'],4 );

$faginController = new FaginController($twig);
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
            echo $faginController->findFormAction();
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
            echo $staticController->carDetailAction($uri[2]);
        }
        elseif ($uri[1] == "api") {
            if ($uri[2] == "get-all-cars") {
                echo $faginController->getAllCarsAction();
            }
            elseif ($uri[2] == "find-cars") {
                echo $faginController->findCarsAction();
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