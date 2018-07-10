<?php

require 'vendor/autoload.php';
// require '../app/.heroku/php/lib/php';
require 'app/controllers/Router.php';
// use Laurent\App\Controllers\Router;

$router = new Router;
$router->routeReq();
