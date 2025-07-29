<?php
namespace App\Controller;

class LogoutController
{
    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        error_log(date('c') . " LOGOUT: " . ($_SESSION['user_id'] ?? '-') . " IP: " . ($_SERVER['REMOTE_ADDR'] ?? '-') . "\n", 3, '/tmp/audit.log');
        $token = $_POST['csrf_token'] ?? null;
        if (!\App\Service\Csrf::checkToken($token)) {
            http_response_code(400);
            echo 'CSRF token invalide.';
            exit;
        }
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'],
                $params['domain'],
                true,
                true
            );
            header('Set-Cookie: ' . session_name() . '=; expires=' . gmdate('D, d-M-Y H:i:s T', time() - 42000) . '; path=' . $params['path'] . '; domain=' . $params['domain'] . '; Secure; HttpOnly; SameSite=Strict', false);
        }
        usleep(2000000);
        error_log(date('c') . " SESSION DESTROYED: " . ($_SERVER['REMOTE_ADDR'] ?? '-') . "\n", 3, '/tmp/audit.log');
        session_destroy();
        header('Location: /login');
        exit;
    }
}
