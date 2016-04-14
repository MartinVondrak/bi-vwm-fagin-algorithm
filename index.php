<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 17.3.16
 * Time: 22:53
 */

require_once 'autoload.php';

use Fagin\Controller\FaginController;
use Fagin\Controller\TestController;

$uri = explode('/',$_SERVER['REQUEST_URI'],3 );

if ($uri[2] == NULL) {
    if ($uri[1] == "test") {
        $controller = new TestController($twig);
        echo $controller->testAction();
    }
    elseif ($uri[1] == "vyhledat") {
        $controller = new FaginController($twig);
        echo $controller->findAction();
    }
    else {
        echo $twig->render("index.html.twig");
    }
}
else {
    echo $twig->render("index.html.twig");
}