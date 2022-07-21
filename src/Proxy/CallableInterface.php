<?php

namespace Minor\Proxy\Proxy;

interface CallableInterface
{
    public function call($target, $method, $args);
}