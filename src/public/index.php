<?php
require_once __DIR__ . '/../vendor/autoload.php';


use App\Core\Router;
use App\Controller\RegisterController;

$router = new Router();

$router->get('/login', function () {
    require __DIR__ . '/../app/View/login.php';
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