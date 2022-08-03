<?php
require './vendor/autoload.php';

class ServiceA
{
    private ServiceB $b;
    private ServiceC $c;

    public function __construct(ServiceB $b, ServiceC $c)
    {

        $this->b = $b;
        $this->c = $c;
    }
}

class ServiceB
{
    private ServiceC $c;

    public function __construct(ServiceC $c)
    {
        $this->c = $c;
    }
}

class ServiceC
{

}

$objectFactory = new \Minor\Proxy\Di\ObjectFactory();

$serviceA = $objectFactory->create(ServiceA::class);

var_dump($serviceA);