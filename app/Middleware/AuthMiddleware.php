<?php
namespace App\Middleware;

class AuthMiddleware
{
    public static function checkAuth(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $isLoggedIn = isset($_SESSION['user_id']);
        $uri = $_SERVER['REQUEST_URI'];

        if (preg_match('#^/logout($|\?)#', $uri)) {
            return;
        }
        if (!$isLoggedIn && !preg_match('#^/(login|register)($|\?)#', $uri)) {
            header('Location: /login');
            exit;
        }
        if ($isLoggedIn && preg_match('#^/(login|register)($|\?)#', $uri)) {
            header('Location: /');
            exit;
        }
    }
}
