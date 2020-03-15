<?php


namespace core;

class Helpers
{
    public static function getValue($array, $key, $defaultValue = null)
    {
        if (key_exists($key, $array)) {
            return $array[$key];
        }
        return $defaultValue;
    }

    public static function camel2id($name, $separator = '-', $strict = false)
    {
        $regex = $strict ? '/\p{Lu}/u' : '/(?<!\p{Lu})\p{Lu}/u';
        if ($separator === '_') {
            return mb_strtolower(trim(preg_replace($regex, '_\0', $name), '_'));
        }

        return mb_strtolower(trim(str_replace('_', $separator, preg_replace($regex, $separator . '\0', $name)), $separator));
    }

    public static function basename($path, $suffix = '')
    {
        if (($len = mb_strlen($suffix)) > 0 && mb_substr($path, -$len) === $suffix) {
            $path = mb_substr($path, 0, -$len);
        }
        $path = rtrim(str_replace('\\', '/', $path), '/\\');
        if (($pos = mb_strrpos($path, '/')) !== false) {
            return mb_substr($path, $pos + 1);
        }

        return $path;
    }
}