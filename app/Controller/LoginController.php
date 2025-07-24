<?php
namespace App\Controller;

use App\Model\Repository\UserRepository;
use App\Core\ORM\EntityManager;
use App\Config\Database;

class LoginController
{
    public function showForm(): void
    {
        require __DIR__ . '/../View/login.php';
    }

    public function handleLogin(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showForm();
            return;
        }
        require_once __DIR__ . '/../Service/Validator.php';
        $v = new \Validator($_POST, [
            'username' => ['required'],
            'password' => ['required']
        ]);
        if (!$v->isValid()) {
            $this->renderErrors($v->getErrors());
            return;
        }
        $pdo = Database::getPdo();
        $repo = new UserRepository(new EntityManager($pdo));
        $user = $repo->findOneBy(['username' => trim($_POST['username'])]);
        if (!$user || !password_verify($_POST['password'], $user->getPassword())) {
            $this->renderErrors(['login' => ['Identifiants invalides.']]);
            return;
        }
        $_SESSION['user_id'] = $user->getId();
        header('Location: /');
        exit;
    }

    private function renderErrors(array $errors): void
    {
        $GLOBALS['login_errors'] = $errors;
        require __DIR__ . '/../View/login.php';
    }
}

