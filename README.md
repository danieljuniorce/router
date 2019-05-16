# Router
> Saaiph\Router é uma simples biblioteca para manipulação de rotas com funções e usando também, controllers e action.

## Iniciado com Router

> 1. Necessário tem composer instalado.
> 2. Clone do project

```git
//Clone do projeto
$ git clone https://github.com/saaiph/dotenv.git router

//Acessando a pasta do projeto.
$ cd router

//Gerando o autoload do project em PSR-4 com composer.
$ composer install
```

### Instânciando a classe Router
```php
    use \Saaiph\Router\Router;

    //$filename é o local onde vai ficar armazenado os verbos usados no http padrão, GET, POST, PUT e Delete;
    //$namespaceController argumentos opcional, se for usar controllers no seus projeto é necessário colocar o namespace inicial onde ficarar armazenado o seus controllers;
    $router = new Router($filename, $namespaceController);

    //Exemplo de uso
    $router = new Router(__DIR__."/router/web.php", "\Controllers\\");
```

### Estrutura do arquivo web.php (ou outros)

> É necessário criar um arquivo onde irá armazenar os verbos da rotas que você irá criar;

1. Criando uma pastar na raiz do projeto: Ex: router;
2. Criando o arquivo onde irar armazenar os verbos: Ex: web.php;

> verbos que podem disponíveis
```php
//Instanciando o verbos
    Use \Saaiph\Router\Router;

    //Verbos
    Router::get($url, $struct);
    Router::post($url, $struct);
    Router::put($url, $struct);
    Router::delete($url, $struct);

    //Verbos utilizando function
    Router::get("/home", function () {
        #Código
    });
    Router::post("/home", function () {
        #Código
    });
    Router::put("/home", function () {
        #Código
    });
    Router::delete("/home", function () {
        #Código
    });

    //Verbos utilizando Controllers;
    Router::get("/home", "Controller@action");
    Router::post("/home", "Controller@action");
    Router::put("/home", "Controller@action");
    Router::delete("/home", "Controller@action");
```

## Baseado na Lincensa MIT
> leia o arquivo LINCESE para mais informações.