<?php

namespace Minor\Proxy\Tools;

use Minor\Proxy\Proxy\CallableInterface;

class DynamicClassGenerator
{

    public function create($class, CallableInterface $callable)
    {
        $ref = new \ReflectionClass($class);
        $functionCode = $this->getFunctionCodeByRef($ref, $class);
        if ($ref->isInterface()) {
            return $this->createClass($class, $callable, $functionCode, 'implements');
        }

        if ($ref->isAbstract()) {
            return $this->createClass($class, $callable, $functionCode, 'extends');
        }

        return new $class;
    }

    private function createClass($class, $callable, $functionCode, $extendName)
    {
        $classSegment = explode('\\', $class);
        $className = end($classSegment) . '_DynamicCreated';
        $code = $this->getClassCode($className, $class, $extendName, $functionCode);

        $o = ClassLoader::loadClass($code, $className);

        $o->callable = $callable;
        return $o;
    }


    /**
     * @param string $className
     * @param $class
     * @return string
     */
    private function getClassCode(string $className, $class, $extendName, $functionCode): string
    {
        return
            "class {$className} {$extendName} \\$class" . '{
                    public $callable;

                    ' . $functionCode . '
            }
';
    }


    private function getFunctionCode($method, $argsName, $class)
    {
        $argsName = implode(',', $argsName);
        return "function $method($argsName)" . '{return $this->callable->call("' . $class . '","' . $method . '",func_get_args());}';
    }

    private function getFunctionCodeByRef(\ReflectionClass $ref, $class)
    {
        $methods = $ref->getMethods();

        $code = '';

        foreach ($methods as $method) {
            $code .= $this->getFunctionCode(
                $method->getName(),
                array_map(function ($item) {
                    return '$' . $item->getName();
                }, $method->getParameters()),
                $class
            );
        }

        return $code;
    }

}