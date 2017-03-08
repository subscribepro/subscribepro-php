<?php

function registerSubscribeProSDK($className) {

    $className = str_replace("\\", '/', $className);
    $file = __DIR__ . '/src/' . $className . '.php';
    include_once($file);

}

spl_autoload_register('registerSubscribeProSDK');
require_once 'PHPUnit/Autoload.php';
include_once(__DIR__ . '/vendor/autoload.php');
