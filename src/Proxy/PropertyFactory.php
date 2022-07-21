<?php

namespace Minor\Proxy\Proxy;

#[\Attribute]
interface PropertyFactory
{

    public function create($propertyClass);
}