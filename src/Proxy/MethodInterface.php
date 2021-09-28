<?php


namespace Minor\Proxy\Proxy;


interface MethodInterface
{
    public function invoke($class, array $args);

    public function __toString();
}
