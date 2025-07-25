<?php
namespace App\Controller;

use App\Model\Repository\UserRepository;
use App\Core\ORM\EntityManager;

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
        if (!$this->request->isMethod('POST')) {
            $this->showForm();
            return;
        }
        $data = $this->request->input();
        $v = new \Validator($data, [
            'username' => ['required'],
            'password' => ['required']
        ]);
        if (!$v->isValid()) {
            $this->render('login', ['errors' => $v->getErrors()]);
            return;
        }
        $em = $this->container->get(EntityManager::class);
        $repo = $em->getRepository(\App\Model\Entity\User::class, UserRepository::class);
        $user = $repo->findOneBy(['username' => trim($data['username'])]);
        if (!$user || !password_verify($data['password'], $user->getPassword())) {
            $this->render('login', ['errors' => ['login' => ['Identifiants invalides.']]]);
            return;
        }
        $_SESSION['user_id'] = $user->getId();
        header('Location: /');
        exit;
    }
}
