<?php

namespace Minor\Proxy\Demo;

use Minor\Proxy\Proxy\CallableInterface;

class RpcProxy implements CallableInterface
{
    public function call($target, $method, $args)
    {
        $args = implode(',', $args);
        return "RPC 调用 {$target}@{$method}({$args})";
    }
}