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

/*$fakin = new \Fagin\Service\LinearSearchService();
$db = new \Fagin\Data\Database();
var_dump($db->countCars());
$cars = $fakin->getKProductsWithParams(array(Car::VOLUME, Car::POWER), 3200, \Fagin\Service\AbstractSearchService::MAX);
print '<pre>';
var_dump($cars);
print '</pre>';*/
$carOp = new \Fagin\Service\CarOperation();
//var_dump($carOp->normalizeCarsToArray());
echo "test";