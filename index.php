<?php
require 'vendor/autoload.php';
require 'app/controllers/Router.php';

$router = new Router;
$router->routeReq();
