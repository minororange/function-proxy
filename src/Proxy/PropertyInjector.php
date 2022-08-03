<?php

namespace Minor\Proxy\Proxy;

use Minor\Proxy\Di\ObjectFactory;

class PropertyInjector
{

    private ObjectFactory $objectFactory;

    public function __construct()
    {
        $this->objectFactory = new ObjectFactory();
    }

    public function inject($class)
    {
        $reflectionClass = new \ReflectionClass($class);

        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            $type = $property->getType();
            if (null === $type) {
                continue;
            }
            $name = $type->getName();
            if ($this->setPropertyByAttribute($property, $class, $name)) {
                continue;
            }


            $propertyObject = $this->objectFactory->create($name);
            $this->setProperty($property, $class, $propertyObject);
        }

        return $class;
    }

    /**
     * @param \ReflectionProperty $property
     * @param $class
     */
    protected function setPropertyByAttribute(\ReflectionProperty $property, $class, $propertyClass): bool
    {
        $attributes = $property->getAttributes();

        if (count($attributes) > 0) {
            $name = $attributes[0]->getName();
            $factory = $this->objectFactory->create($name);
            if ($factory instanceof PropertyFactory) {
                $this->setProperty($property, $class, $factory->create($propertyClass));
            }

            return true;
        }

        return false;
    }

    /**
     * @param \ReflectionProperty $property
     * @param $class
     * @param mixed $propertyObject
     */
    protected function setProperty(\ReflectionProperty $property, $class, mixed $propertyObject): void
    {
        $property->setAccessible(true);
        $property->setValue($class, $propertyObject);
    }
}