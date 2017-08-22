<?php

use Calculator\Calculator;

/**
 * Our own simple autoloader
 */
spl_autoload_register(function ($class) {
    require_once __NAMESPACE__ .  $class . '.php';
});

/**
 * Usage from an app example
 */
$params = ''; //add your_number, subtract your_number, etc. (README.md, #possible_operations)

$calculator = new Calculator($params);

echo $calculator->getErrors() ? implode(';' . PHP_EOL, $calculator->getErrors()) : $calculator->getResult();

/**
 * Debug info
 */
//echo '<pre>';
//var_dump($calculator);exit;