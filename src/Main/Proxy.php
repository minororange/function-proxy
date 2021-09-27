<?php


namespace Minor\Proxy\Main;


class Proxy
{

    /**
     * @var string
     */
    private $class;
    /**
     * @var InterceptorInterface
     */
    private $interceptor;

    public function __construct($class, InterceptorInterface $interceptor)
    {
        $this->class = $class;
        $this->interceptor = $interceptor;
    }

    public function __call($name, $args)
    {
        return $this->interceptor->intercept($this->class, new MethodCaller($name), $args);
    }

    public static function create($class, InterceptorInterface $interceptor)
    {
        return new Proxy($class, $interceptor);
    }
}

