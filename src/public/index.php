<?php
// src/public/index.php
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 1 : 0);
ini_set('session.cookie_samesite', 'Strict');

require_once __DIR__ . '/../vendor/autoload.php';

use App\Middleware\AuthMiddleware;
use App\Config\EnvLoader;
use App\Controller\LoginController;
use App\Controller\ErrorController;
use App\Controller\HomeController;
use App\Core\Router;
use App\Controller\RegisterController;
use App\Config\Container;

AuthMiddleware::checkAuth();
EnvLoader::load(__DIR__ . '/../.env');

$router = new Router();
$container = new Container();

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

$router->post('/logout', function () {
    (new \App\Controller\LogoutController())->logout();
});

// Route 404
$router->setNotFoundHandler(function () use ($container) {
    (new ErrorController($container))->notFound();
});

// Dispatch selon la requête
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);