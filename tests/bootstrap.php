<?php
error_reporting(-1);
date_default_timezone_set('UTC');

// PHP Unit backward compatibility
if (class_exists('\PHPUnit\Framework\TestCase') &&
    !class_exists('\PHPUnit_Framework_TestCase')) {
    class_alias('\PHPUnit\Framework\TestCase', '\PHPUnit_Framework_TestCase');
}

// Include the composer autoloader
$loader = require __DIR__ . '/../vendor/autoload.php';
