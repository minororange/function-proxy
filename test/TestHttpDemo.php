<?php

use Minor\Proxy\Demo\Http\Demo;
use Minor\Proxy\Proxy\PropertyInjector;

require './vendor/autoload.php';
$demo = new Demo();
$propertyInjector = new PropertyInjector();
$propertyInjector->inject($demo);

$demo->demo();