<?php

require './vendor/autoload.php';

use Minor\Proxy\Main\InterceptorInterface;
use Minor\Proxy\Main\MethodInterface;


// 具体业务逻辑类
class UserQuery
{
    public function queryUserName($id)
    {
        return "我是用户ID为{$id}的用户名";
    }
}

// 全局日志记录操作类
class LogInterceptor implements InterceptorInterface
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
        echo "类名：{$className},方法名：{$method},参数：{$argString}\n";
    }

    private function after($class, MethodInterface $method, $result)
    {
        $resultString = json_encode($result, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $className = get_class($class);
        echo "类名：{$className},方法名：{$method},结果：{$resultString}\n";
    }
}

/** @var UserQuery $proxy */
$proxy = \Minor\Proxy\Main\Proxy::create(new UserQuery(), new LogInterceptor());

var_dump($proxy->queryUserName(10086));
