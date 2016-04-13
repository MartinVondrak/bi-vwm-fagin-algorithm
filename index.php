<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 17.3.16
 * Time: 22:53
 */

require_once 'autoload.php';

use Fagin\Controller\TestController;

$uri = explode('/',$_SERVER['REQUEST_URI'] );

if ($uri[1] == "test") {
    $controller = new TestController($twig);
    echo $controller->testAction();
}
else {
    echo $twig->render("index.html.twig");
}