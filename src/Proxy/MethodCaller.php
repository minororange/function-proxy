<?php


namespace Minor\Proxy\Proxy;


class MethodCaller implements MethodInterface
{

    /**
     * @var string
     */
    private $name;

    /**
     * MethodCaller constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    public function invoke($class, array $args)
    {
        return call_user_func_array([$class, $this->name], $args);
    }

    public function __toString()
    {
        return $this->name;
    }
}
