<?php
namespace App\Controller;
use App\Service\AuthService;

use App\Model\Repository\UserRepository;
use App\Core\ORM\EntityManager;

use App\Handler\LoginHandler;

class LoginController extends BaseController
{
    public function __construct(\App\Config\Container $container)
    {
        parent::__construct($container);
    }

    public function showForm(): void
    {
        $this->render('login');
    }

    public function handleLogin(): void
    {
        $em = $this->container->get(EntityManager::class);
        $recaptchaSecret = $_ENV['RECAPTCHA_SECRET_KEY'] ?? '';
        $handler = new LoginHandler($em, $recaptchaSecret);
        $handler->process($this->request, fn($view, $params = []) => $this->render($view, $params));
    }
}
