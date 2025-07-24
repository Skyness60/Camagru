<?php

use App\Controller\IndexController;
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Config/EnvLoader.php';

\App\Config\EnvLoader::load(__DIR__ . '/../.env');


use App\Core\Router;
use App\Controller\RegisterController;

$router = new Router();


use App\Controller\LoginController;

$router->get('/', function () {
    (new IndexController())->show();
});

$router->get('/login', function () {
    (new LoginController())->showForm();
});

$router->post('/login', function () {
    (new LoginController())->handleLogin();
});

$router->get('/register', function () {
    (new RegisterController())->showForm();
});

$router->post('/register', function () {
    (new RegisterController())->handleRegister();
});

// Route 404 personnalisée
$router->setNotFoundHandler(function () {
    echo '404 - Page not found';
});

// Dispatch selon la requête
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);