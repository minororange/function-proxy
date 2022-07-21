<?php

namespace Minor\Proxy\Proxy;

class PropertyInjector
{
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


            $propertyObject = new $name(); //todo di
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

            $factory = $attributes[0]->newInstance(); //todo di
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