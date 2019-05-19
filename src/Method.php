<?php
namespace Saaiph\Router;

class Method
{
    //atributos do verbos Http
    protected static $get;
    protected static $post;
    protected static $put;
    protected static $delete;

    /* Methods para montagem das rotas */
    protected static function get($router, $struct)
    {
        self::$get[$router] = $struct;
    }

    protected static function post($router, $struct)
    {
        self::$post[$router] = $struct;
    }

    protected static function put($router, $struct)
    {
        self::$put[$router] = $struct;
    }

    protected static function delete($router, $struct)
    {
        self::$delete[$router] = $struct;
    }
}