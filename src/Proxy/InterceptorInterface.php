<?php


namespace Minor\Proxy\Proxy;


interface InterceptorInterface
{
    public function intercept($class, MethodInterface $method, array $args);
}
