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
$cars = $fakin->getKProductsWithParams(array(Car::VOLUME, Car::POWER), 4, FaginSearchService::MAX);
print '<pre>';
var_dump($cars);
print '</pre>';