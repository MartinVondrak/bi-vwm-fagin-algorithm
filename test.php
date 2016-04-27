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

$fakin = new \Fagin\Service\FaginSearchService();
$db = new \Fagin\Data\Database();
$car = $db->fetchCar(2);
var_dump(json_encode($car));
$cars = json_encode($fakin->getKProductsWithParams(array(Car::VOLUME, Car::POWER), 4, 'max'));
print '<pre>';
var_dump($cars);
print '</pre>';