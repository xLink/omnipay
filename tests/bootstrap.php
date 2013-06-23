<?php

error_reporting(E_ALL | E_STRICT);

define('TESTS_DIR', __DIR__);

// include the composer autoloader
$autoloader = require __DIR__.'/../vendor/autoload.php';

// autoload abstract TestCase classes in test directory
$autoloader->add('Omnipay', __DIR__);
