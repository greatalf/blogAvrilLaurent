<?php
session_start();
require_once 'app/Autoload.php';
require 'app/controllers/Router.php';

Autoloader::register();
$router = new Router;
$router->routeReq();
