<?php


namespace Minor\Proxy\Main;


interface MethodInterface
{
    public function invoke($class, array $args);

    public function __toString();
}
