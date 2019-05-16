<?php
namespace Saaiph\Router;

use Saaiph\Router\Method\Method;

class Router extends Method
{
    //Leitura o filename
    private static $filename;

    //argumentos passado pela args
    private static $args;

    //prefix do namespace para leitura dos controllers
    private static $namespaceControlles;

    /*
    * @param $filename incluar o diretório relacionado a o diretório!
    * @param $namespaceControlles é optional, relacionado a leitura ao namespace dos controllers baseado no namespace com autoload em PSR-4
    */
    public function __construct($filename, $namespaceControlles = '')
    {
        self::$filename = $filename;
        self::$namespaceControlles = $namespaceControlles;
    }
    
    public static function load()
    {
        self::read_file(self::$filename);
        self::struct();
    }

    /*
    *----------------------------------------------
    *
    *   Leitura do arquivo onde irá fica armazenado
    *   as routas;
    *   @param $filename obrigatório
    *----------------------------------------------
    */
    private static function read_file($filename)
    {
        if (file_exists($filename)) {
            require($filename);
        } else {
            new \ErrorException("Nome do arquivo/diretório está errado ou não existe.");
        }
    }

    /*
    *----------------------------------------------
    *
    * Estrutura de leitura das rotas baseadas url
    *
    *----------------------------------------------
    */
    private static function struct()
    {
        //Armazenamento da url
        $url = explode("index.php", $_SERVER['PHP_SELF']);
        $url = end($url);

        //Array para armazena o pattern com a url e paramentros e struct do method requerido;
        $type = [];
        switch ($_SERVER['REQUEST_METHOD']) {
            case "GET":
                $type = self::$get;
                break;
            case "POST":
                $type = self::$post;
                break;
            case "PUT":
                $type = self::$put;
                break;
            case "DELETE":
                $type = self::$delete;
                break;
        }
        
        //Estrutura baseado
        foreach ($type as $pt => $struct) {
            $pattern = preg_replace('(\{[a-z0-9]{0,}\})', '([a-z0-9]{0,})', $pt);

            if (preg_match('#^('.$pattern.')*$#i', $url, $matches) === 1) {
                array_shift($matches);
                array_shift($matches);

                $itens = [];
                if (preg_match_all('(\{[a-z0-9]{0,}\})', $pt, $m)) {
                    $itens = \preg_replace('(\{|\})', '', $m[0]);
                }
                
                $args = [];
                foreach ($matches as $key => $value) {
                    $args[$itens[$key]] = $value;
                }
            }
            self::$args = $args;
            self::instace($struct);           
            break;
        }
    }

    private static function instace($struct)
    {
        $args = self::filter_arg();

        /*
            verificação usada ser for passa uma string com @ com nome do controller e a função
        */
        if (is_string($struct) && strstr($struct, "@")) {
            $struct = explode("@", $struct);

            $controller = self::$namespaceControlles.$struct[0];
            $action = $struct[1];

            return \call_user_func_array([new $controller(), $action], $args);

        } 
        /*
            verificação usa para executar quando for passado no struct uma função;
        */
        else if (is_callable($struct)) {

            return $struct($args);
        } else {
            self::error404(function () {
                echo "Router not found!";
            });
        }
    }
    private static function filter_arg()
    {
        if (\count(self::$args)) {
            return self::$args;
        } else {
            return [];
        }
    }
}