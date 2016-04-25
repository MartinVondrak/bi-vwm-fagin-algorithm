<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 17.3.16
 * Time: 23:01
 */

require_once 'autoload.php';

use Fagin\Data\Database;
use Fagin\Service\CarOperation;
use Fagin\Data\Car;
use Fagin\Service\FaginSearchService;

$test = new CarOperation();
$test->normalize();
$db = new Database();
$fakin = new FaginSearchService();
$cars = $fakin->getKProductsWithParams(array(Car::ACCELERATION, Car::MANUFACTURE_YEAR, Car::MILEAGE), 2);
print '<pre>';
var_dump($cars);
print '</pre>';
//print $twig->render('test.html.twig', array('cars' => $cars));