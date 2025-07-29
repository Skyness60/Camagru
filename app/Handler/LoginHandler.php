<?php
namespace App\Handler;

use App\Service\Validator;
use App\Service\AuditLogger;
use App\Service\RecaptchaVerifier;
use App\Service\Csrf;
use App\Service\LoginRateLimiter;
use App\Service\AuthService;
use App\Core\ORM\EntityManager;

class LoginHandler
{
    private Csrf $csrf;
    private AuditLogger $logger;
    private LoginRateLimiter $rateLimiter;
    private RecaptchaVerifier $recaptcha;
    private AuthService $auth;

    public function __construct(EntityManager $em, string $recaptchaSecret)
    {
        $this->csrf = new Csrf();
        $this->logger = new AuditLogger();
        $this->rateLimiter = new LoginRateLimiter();
        $this->recaptcha = new RecaptchaVerifier($recaptchaSecret);
        $this->auth = new AuthService($em);
    }

    public function process($request, $render)
    {
        if (!$this->handleRecaptcha()) {
            $render('login', ['errors' => ['login' => ['Captcha Google invalide ou non validé.']]]);
            return;
        }
        if (!$request->isMethod('POST')) {
            $render('login');
            return;
        }
        $data = $request->input();
        if (!$this->handleCsrf($data)) {
            $render('login', ['errors' => ['csrf' => ["Le token CSRF est invalide."]]]);
            return;
        }
        if ($this->handleRateLimiting($data)) {
            $render('login', ['errors' => ['login' => ['Trop de tentatives. Réessayez plus tard.']]]);
            return;
        }
        if (!$this->validateInput($data)) {
            $this->rateLimiter->incrementAttempts();
            $this->logger->log('LOGIN FAIL', $data['username'] ?? '-', $_SERVER['REMOTE_ADDR'] ?? '-');
            $this->rateLimiter->delay();
            $render('login', ['errors' => ['login' => ['Identifiants invalides.']]]);
            return;
        }
        $user = $this->auth->getUser($data['username']);
        if (!$this->auth->verifyPassword($user, $data['password'])) {
            $this->rateLimiter->incrementAttempts();
            $this->logger->log('LOGIN FAIL', $data['username'] ?? '-', $_SERVER['REMOTE_ADDR'] ?? '-');
            $this->rateLimiter->delay();
            $render('login', ['errors' => ['login' => ['Identifiants invalides.']]]);
            return;
        }
        $this->loginUser($user);
    }

    private function handleRecaptcha(): bool
    {
        $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
        return $this->recaptcha->verify($recaptchaResponse, $_SERVER['REMOTE_ADDR'] ?? '');
    }

    private function handleCsrf(array $data): bool
    {
        return $this->csrf->checkToken($data['csrf_token'] ?? null);
    }

    private function handleRateLimiting(array $data): bool
    {
        if ($this->rateLimiter->tooManyAttempts()) {
            $this->logger->log('LOGIN BLOCKED', $data['username'] ?? '-', $_SERVER['REMOTE_ADDR'] ?? '-');
            $this->rateLimiter->delay();
            return true;
        }
        return false;
    }

    private function validateInput(array $data): bool
    {
        $v = new Validator($data, [
            'username' => ['required'],
            'password' => ['required']
        ]);
        return $v->isValid();
    }

    private function loginUser($user): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user->getId();
        $this->rateLimiter->resetAttempts();
        $this->logger->log('LOGIN OK', $user->getUsername(), $_SERVER['REMOTE_ADDR'] ?? '-');
        header('Location: /');
        exit;
    }
}
