<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 18.3.16
 * Time: 18:51
 */

spl_autoload_register(function ($class_name) {
    $class_name = str_replace('\\', '/', $class_name);
    $class_name = str_replace('Fagin/', '', $class_name);
    include_once $class_name . '.php';
});