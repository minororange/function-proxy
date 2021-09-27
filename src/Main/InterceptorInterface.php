<?php


namespace Minor\Proxy\Main;


interface InterceptorInterface
{
    public function intercept($class, MethodInterface $method, array $args);
}
