<?php

namespace Minor\Proxy\Demo;

use Attribute;
use Minor\Proxy\Proxy\PropertyFactory;
use Minor\Proxy\Tools\DynamicClassGenerator;

/**
 * 属性工厂
 */
#[Attribute]
class RpcProperty implements PropertyFactory
{
    private DynamicClassGenerator $classGenerator;

    public function __construct(DynamicClassGenerator $classGenerator)
    {
        $this->classGenerator = $classGenerator;
    }

    public function create($propertyClass)
    {
        return $this->classGenerator->create($propertyClass, new RpcProxy());
    }
}