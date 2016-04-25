<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 17.3.16
 * Time: 23:01
 */

require_once 'autoload.php';

use Fagin\Data\Car;
use Fagin\Service\FaginSearchService;

$fakin = new FaginSearchService();
$cars = $fakin->getKProductsWithParams(array(Car::ACCELERATION, Car::MANUFACTURE_YEAR, Car::MILEAGE), 5);
print '<pre>';
var_dump($cars);
print '</pre>';