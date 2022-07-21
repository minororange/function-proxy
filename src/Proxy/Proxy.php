<?php


namespace Minor\Proxy\Proxy;


class Proxy
{
    private static $globalInterceptor;

    /**
     * @var string
     */
    private $class;
    /**
     * @var InterceptorInterface
     */
    private $interceptor;

    /**
     * @var PropertyInjector;
     */
    private $propertyInjector;

    /**
     * Proxy constructor.
     * @param $class
     * @param InterceptorInterface|null $interceptor
     * @throws UndefinedInterceptorException
     */
    public function __construct($class, InterceptorInterface $interceptor = null)
    {
        $this->propertyInjector = new PropertyInjector();
        $this->class = $this->propertyInjector->inject($class);
        $this->interceptor = $interceptor ?? self::getGlobalInterceptor();
    }

    /**
     * @param mixed $globalInterceptor
     */
    public static function setGlobalInterceptor(InterceptorInterface $globalInterceptor)
    {
        self::$globalInterceptor = $globalInterceptor;
    }

    /**
     * @return mixed
     * @throws UndefinedInterceptorException
     */
    public static function getGlobalInterceptor()
    {
        if (is_null(self::$globalInterceptor)) {
            throw new UndefinedInterceptorException("Undefined interceptor,please set interceptor first!");
        }

        return self::$globalInterceptor;
    }

    public function __call($name, $args)
    {
        return $this->interceptor->intercept($this->class, new MethodCaller($name), $args);
    }

    /**
     * @param $class
     * @param InterceptorInterface|null $interceptor
     * @return Proxy
     * @throws UndefinedInterceptorException
     * @author ycz
     * @date 2021/9/28
     */
    public static function create($class, InterceptorInterface $interceptor = null)
    {
        return new Proxy($class, $interceptor);
    }
}

