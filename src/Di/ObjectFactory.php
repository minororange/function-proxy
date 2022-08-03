<?php

namespace Minor\Proxy\Di;

class ObjectFactory
{

    private $instances = [];

    public function create($class)
    {
        $reflectionClass = new \ReflectionClass($class);

        $reflectionMethod = $reflectionClass->getConstructor();

        if (is_null($reflectionMethod)) {
            return new $class;
        }
        $reflectionParameters = $reflectionMethod->getParameters();

        if (empty($reflectionParameters)) {
            return new $class;
        }
        $arguments = [];

        foreach ($reflectionParameters as $parameter) {
            $reflectionUnionType = $parameter->getType();
            $name = $reflectionUnionType->getName();
            if ($this->hasInstance($name)) {
                $arguments[] = $this->getInstance($name);
                continue;
            }
            $instance = $this->create($name);
            $this->addInstance($name, $instance);
            $arguments[] = $instance;
        }

        return new $class(...$arguments);
    }

    private function addInstance($name, $instance)
    {
        $this->instances[$name] = $instance;
    }

    private function getInstance($name)
    {
        return $this->instances[$name] ?? null;
    }

    private function hasInstance($name)
    {
        return isset($this->instances[$name]);
    }
}