<?php

namespace Minor\Proxy\Tools;

class ClassLoader
{

    public static function loadClass($classCode, $className)
    {
        file_put_contents($className, "<?php \n" . $classCode);
        include $className;

        return new $className;
    }
}