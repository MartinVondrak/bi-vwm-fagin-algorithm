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

$uri = explode('/',$_SERVER['REQUEST_URI'],4 );

$faginController = new FaginController($twig);
$staticController = new StaticController($twig);
$testController = new TestController($twig);

if (count($uri) == 2) {
    if($uri[1] == NULL) {
        echo $twig->render("static/index.html.twig");
    }
}
elseif (count($uri) == 3) {
    if ($uri[2] == NULL) {
        if ($uri[1] == "test") {
            echo $testController->testAction();
        }
        elseif ($uri[1] == "vyhledat") {
            echo $faginController->findAction();
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
            echo $faginController->carDetailAction($uri[2]);
        }
        elseif ($uri[1] == "api") {
            if ($uri[2] == "get-all-cars") {
                echo $faginController->getAllCarsAction();
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