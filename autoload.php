<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 18.3.16
 * Time: 18:51
 */

require __DIR__ . '/vendor/autoload.php';

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem(__DIR__ . '/src/Views');
$twig = new Twig_Environment($loader);