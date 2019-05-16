<?php
namespace Saaiph\Router\Method;

class Method
{
    protected static $get;
    protected static $post;
    protected static $put;
    protected static $delete;

    public static function get($pattern, $struct)
    {
        self::$get[$pattern] = $struct;
    }

    public static function post($pattern, $struct)
    {
        self::$post[$pattern] = $struct;
    }

    public static function put($pattern, $struct)
    {
        self::$put[$pattern] = $struct;
    }

    public static function delete($pattern, $struct)
    {
        self::$delete[$pattern] = $struct;
    }

    protected static function error404($structFunction)
    {
        return $structFunction();
    }
}