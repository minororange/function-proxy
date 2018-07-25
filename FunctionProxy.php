<?php
/**
 * Created by PhpStorm.
 * User: ycz
 * Date: 2018/07/25
 * Time: 11:43
 */

namespace minor;


use \Exception;

class FunctionProxy
{
    private $className = null;

    /**
     * FunctionProxy constructor.
     * @param string $className
     * @throws Exception
     */
    public function __construct(string $className)
    {
        if (!class_exists($className)) {
            throw new \Exception('class not found');
        }

        $this->className = $className;
    }


    /**
     * @param string $method
     * @param array $args
     * @return mixed
     * @throws \ReflectionException
     * @date 2018/07/25
     * @author ycz
     * @throws Exception
     */
    public function __invoke(string $method, array $args = [])
    {
        $this->runFunction($method, $args);
    }

    /**
     * @param string $method
     * @param array $args
     * @return mixed
     * @date 2018/07/25
     * @author ycz
     * @throws \ReflectionException
     * @throws Exception
     */
    private function runFunction(string $method, array $args = [])
    {
        $comment = $this->getComment($method);
        $class = new $this->className;
        array_unshift($args, $class);

        if ($code = static::getReplaceCode($comment)) {
            return static::replace($code, $args);
        }

        static::before($comment, ...$args);

        try {
            $res = $class->$method(...$args);

            static::after($comment, ...$args);

            return $res;
        } catch (\Exception $e) {
            array_unshift($args, $e);
            static::exception($comment, ...$args);
        }
    }

    /**
     * @param string $name
     * @param array $arguments
     * @date 2018/07/25
     * @author ycz
     * @throws \ReflectionException
     * @throws Exception
     */
    public function __call(string $method, array $args)
    {
        $this->runFunction($method, $args);
    }

    /**
     * @param $method
     * @return bool|string
     * @date 2018/07/25
     * @author ycz
     * @throws \ReflectionException
     */
    private function getComment($method)
    {
        $ref = new \ReflectionClass($this->className);
        return $ref->getMethod($method)->getDocComment();
    }

    /**
     * @param string|array $code
     * @return mixed
     * @date 2018/07/25
     * @author ycz
     * @throws Exception
     */
    private static function replace($code)
    {
        $args = func_get_args();
        unset($args[0]);

        if (is_array($code)) {
            return static::executeArrayFunction($code, $args);
        }

        return eval($code);
    }

    /**
     * @param $comment
     * @date 2018/07/25
     * @author ycz
     * @throws Exception
     */
    private static function before(string $comment): void
    {
        $code = static::getBeforeCode($comment);
        $args = func_get_args();
        unset($args[0]);

        if (is_array($code)) {
            static::executeArrayFunction($code, $args);
            return;
        }

        eval($code);
    }

    /**
     * @param $functionArr
     * @param $args
     * @param bool $proxy
     * @return mixed
     * @date 2018/07/25
     * @author ycz
     * @throws Exception
     */
    private static function executeArrayFunction($functionArr, $args, $proxy = false)
    {
        $class = $proxy ? new static($functionArr[0]) : new $functionArr[0];

        return call_user_func_array([$class, $functionArr[1]], $args);
    }

    /**
     * @param $comment
     * @date 2018/07/25
     * @author ycz
     * @throws Exception
     */
    private static function after(string $comment): void
    {
        $code = static::getAfterCode($comment);
        $args = func_get_args();
        unset($args[0]);

        if (is_array($code)) {
            static::executeArrayFunction($code, $args);
            return;
        }

        eval($code);
    }

    /**
     * @param string $comment
     * @date 2018/07/25
     * @author ycz
     * @throws Exception
     */
    private static function exception(string $comment)
    {
        $code = static::getExceptionCode($comment);
        $args = func_get_args();
        unset($args[0]);

        if (is_array($code)) {
            static::executeArrayFunction($code, $args);
            return;
        }

        eval($code);
    }

    /**
     * @param string $comment
     * @return string
     * @date 2018/07/25
     * @author ycz
     */
    private static function getReplaceCode(string $comment)
    {
        return static::rewriteCode(Tools::getDocComment($comment, 'replace'));
    }

    /**
     * @param string $comment
     * @return string
     * @date 2018/07/25
     * @author ycz
     */
    private static function getBeforeCode(string $comment)
    {
        return static::rewriteCode(Tools::getDocComment($comment, 'before'));
    }

    /**
     * @param string $comment
     * @return string
     * @date 2018/07/25
     * @author ycz
     */
    private static function getAfterCode(string $comment)
    {
        return static::rewriteCode(Tools::getDocComment($comment, 'after'));
    }

    /**
     * @param $code
     * @return array|string
     * @date 2018/07/25
     * @author ycz
     */
    private static function rewriteCode($code)
    {
        if (empty($code)) {
            return '';
        }

        if (!preg_match('/\*/', $code)) {
            return static::addSemicolon($code);
        }

        if (!preg_match('/->/', $code)) {
            return static::addSemicolon($code);
        }

        $functionArr = explode('->', $code);

        if (!class_exists($functionArr[0])) {
            return static::addSemicolon($code);
        }

        $functionArr[1] = preg_replace('/\(\*\);*/', '', $functionArr[1]);

        return $functionArr;
    }

    /**
     * @param string $comment
     * @return string
     * @date 2018/07/25
     * @author ycz
     */
    private static function getExceptionCode(string $comment)
    {
        return static::rewriteCode(Tools::getDocComment($comment, 'exception'));
    }

    /**
     * @param string $code
     * @return string
     * @date 2018/07/25
     * @author ycz
     */
    private static function addSemicolon(string $code): string
    {
        return Tools::endWith($code, ';') ? $code : $code . ';';
    }

}