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
    public function create($propertyClass)
    {
        return DynamicClassGenerator::create($propertyClass, new RpcProxy());
    }
}