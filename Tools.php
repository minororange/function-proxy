<?php
/**
 * Created by PhpStorm.
 * User: ycz
 * Date: 2018/07/25
 * Time: 19:10
 */

namespace minor;


class Tools
{
    /**
     * 获取指定注释的值
     *
     * @param $comment
     * @param $tag
     * @return string
     * @date 2018/07/10
     * @author ycz
     */
    public static function getDocComment($comment, $tag)
    {
        if (empty($tag)) {
            return $comment;
        }

        $matches = [];
        preg_match("/" . $tag . "(.*)(\\r\\n|\\r|\\n)/U", $comment, $matches);

        if (isset($matches[1])) {
            return trim($matches[1]);
        }

        return '';
    }

    /**
     * 检测字符串是否出现在另一个字符串的结尾
     * @param string $str1 参照的字符串
     * @param string $str2 出现的字符串
     * @return boolean 返回布尔值
     * @author longli
     */
    public static function endWith($str1, $str2)
    {
        return strrpos($str1, $str2) === strlen($str1) - strlen($str2);
    }
}