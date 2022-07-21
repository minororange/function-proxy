<?php

namespace Minor\Proxy\Demo;

use Minor\Proxy\Proxy\InterceptorInterface;
use Minor\Proxy\Proxy\MethodInterface;

class Logger implements InterceptorInterface
{

    public function intercept($class, MethodInterface $method, array $args)
    {
        $this->before($class, $method, $args);
        $result = $method->invoke($class, $args);
        $this->after($class, $method, $result);
        return $result;
    }

    private function before($class, MethodInterface $method, array $args)
    {
        $argString = json_encode($args, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $className = get_class($class);
        echo "before ===> 类名：{$className},方法名：{$method},参数：{$argString}\n";
    }

    private function after($class, MethodInterface $method, $result)
    {
        $resultString = json_encode($result, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $className = get_class($class);
        echo "after ===> 类名：{$className},方法名：{$method},结果：{$resultString}\n";
    }
}