<?php

use Phalcon\Mvc\Router;

$router = new Router( false );

$router->addGet(
    "/",
    [
        "namespace"  => "Qodr\\Controllers",
        "controller" => "Index",
        "action"     => "index"
    ]
)->setName("index");

return $router;