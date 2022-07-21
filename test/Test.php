<?php

require './vendor/autoload.php';

use Minor\Proxy\Proxy\InterceptorInterface;
use Minor\Proxy\Proxy\MethodInterface;
use Minor\Proxy\Proxy\Proxy;
use Minor\Proxy\Proxy\UndefinedInterceptorException;


// 具体业务逻辑类
class UserService
{

    private UserQuery $userQuery1;

    #[UserQueryFactory]
    private UserQuery $userQuery2;

    public function queryUsername1($id)
    {
        echo "执行queryUserName1\n";

        return $this->userQuery1->getUsernameById($id);
    }

    public function queryUsername2($id)
    {
        echo "执行queryUserName2\n";

        return $this->userQuery2->getUsernameById($id);
    }

}

/**
 * 查询数据库类
 */
class UserQuery
{
    public function getUsernameById($id)
    {
        return "我是用户ID为{$id}的用户名";
    }
}

/**
 * 属性工厂
 */
#[Attribute]
class UserQueryFactory implements \Minor\Proxy\Proxy\PropertyFactory
{

    public function create()
    {
        return new class extends UserQuery {
            public function getUsernameById($id)
            {
                return "通过 Attribute 代理工厂实现的 getUsernameById 方法，ID：{$id}";
            }
        };
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
        echo "before ===> 类名：{$className},方法名：{$method},参数：{$argString}\n";
    }

    private function after($class, MethodInterface $method, $result)
    {
        $resultString = json_encode($result, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $className = get_class($class);
        echo "after ===> 类名：{$className},方法名：{$method},结果：{$resultString}\n";
    }
}

Proxy::setGlobalInterceptor(new LogInterceptor());

/** @var UserService $proxy */
try {
    $proxy = Proxy::create(new UserService());
} catch (UndefinedInterceptorException $e) {
    die($e->getMessage());
}

var_dump($proxy->queryUsername1(10086));
var_dump($proxy->queryUsername2(10087));
