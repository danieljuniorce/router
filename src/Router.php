<?php
namespace Saaiph\Router;

class Router extends Method
{
    //Namespace dos controllers
    private static $namespaceController;

    //HTTP
    protected static $http;

    public function __construct(string $router_file, bool $use_controller = false, string $namespace_controller = '')
    {
        $this->read_file($router_file);
        $this->use_controller($use_controller, $namespace_controller);
        $this->struct_router();
    }

    //Read file router
    private function read_file($filename)
    {
        if (file_exists($filename)) {

            require $filename;

        } else {
            throw new \Exception("File not found");
        }
    }

    //Verificação da utilização do controller
    private function use_controller($verify, $namespaceController)
    {
        if ($verify === true) {

            if (is_string($namespaceController) && !empty($namespaceController)) {
                self::$namespaceController = $namespaceController;
            } else {
                throw new \Exception("Necessário a inserção do namespace, a o declara true no construct do Router.");
            }
        }
    }

    private function struct_router()
    {
        $url = \explode("index.php", $_SERVER['PHP_SELF']);
        $url = end($url);

        switch ($_SERVER["REQUEST_METHOD"]) {
            case 'GET':
                default:
                    self::$http = self::$get;
                break;
            case 'POST':
                    self::$http = self::$post;
                break;
            case 'PUT':
                    self::$http = self::$put;
                break;
            case 'DELETE':
                    self::$http = self::$delete;
                break;
        }

        if (self::$http !== null) {

            //Estrutura de leitura da routas
            foreach (self::$http as $pt => $struct) {

                $pattern = \preg_replace('(\{[a-z0-9]{0,}\})', '([a-z0-9]{0,})', $pt);

                if (preg_match('#^('.$pattern.')*$#i', $url, $matches) === 1) {
                    array_shift($matches);
                    array_shift($matches);

                    $itens = [];
                    if (preg_match_all('(\{[a-z0-9]{0,}\})', $pt, $m)) {

                        $itens = \preg_replace('(\{|\})', '', $m[0]);
                    }
                    
                    $arg = [];
                    foreach ($matches as $key => $match) {
                        $arg[$itens[$key]] = $match;
                    }

                    $this->instance_router($struct, $arg);
                    break;
                }
            }

        } else {
            throw new \Exception("Rout not found");
        }
    }

    private function instance_router($struct, $arg)
    {
        if (is_string($struct)) {

            $controllerAndAction = explode("@", $struct);

            $controller = self::$namespaceController.$controllerAndAction[0];
            $action = $controllerAndAction[1];

            \call_user_func_array([new $controller(), $action], $arg);

        } else if (is_callable($struct)) {
            $struct($arg);
        }
    }
}