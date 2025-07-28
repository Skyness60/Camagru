<?php

use App\Controller\IndexController;
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Config/EnvLoader.php';

\App\Config\EnvLoader::load(__DIR__ . '/../.env');


use App\Core\Router;
use App\Controller\RegisterController;

$router = new Router();
// Initialize the container
$container = new \App\Config\Container();


use App\Controller\LoginController;
use App\Controller\ErrorController;
use App\Controller\HomeController;

$router->get('/', function () use ($container) {
    (new HomeController($container))->show();
});

$router->get('/login', function () use ($container) {
    (new LoginController($container))->showForm();
});

$router->post('/login', function () use ($container) {
    (new LoginController($container))->handleLogin();
});

$router->get('/register', function () use ($container) {
    (new RegisterController($container))->showForm();
});

$router->post('/register', function () use ($container) {
    (new RegisterController($container))->handleRegister();
});

// Route 404
$router->setNotFoundHandler(function () use ($container) {
    (new ErrorController($container))->notFound();
});

// Dispatch selon la requête
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);