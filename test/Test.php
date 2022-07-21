<?php

require './vendor/autoload.php';

use Minor\Proxy\Demo\Logger;
use Minor\Proxy\Demo\UserService;
use Minor\Proxy\Proxy\Proxy;
use Minor\Proxy\Proxy\UndefinedInterceptorException;


Proxy::setGlobalInterceptor(new Logger());

/** @var UserService $proxy */
try {
    $proxy = Proxy::create(new UserService());
} catch (UndefinedInterceptorException $e) {
    die($e->getMessage());
}

var_dump($proxy->queryUsername1(10086));
echo "-----------------------\n";
var_dump($proxy->queryUsername2(10087));
