<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\User;
use App\Model\Entity\UserRole;
use App\Model\Repository\UserRepository;
use App\Core\ORM\EntityManager;
use App\Config\Database;

class RegisterController
{
    public function showForm(): void
    {
        require __DIR__ . '/../View/register.php';
    }

    public function handleRegister(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require __DIR__ . '/../View/register.php';
            return;
        }
        require_once __DIR__ . '/../Service/Validator.php';
        $v = new \Validator($_POST, [
            'username' => ['required'],
            'password' => ['required', 'min:8'],
            'confirmPassword' => ['required', 'match:password'],
        ]);
        if (!$v->isValid()) {
            $this->renderErrors($v->getErrors());
            return;
        }
        $pdo = Database::getPdo();
        $em = new EntityManager($pdo);
        $repo = new UserRepository($em);
        $errors = [];
        if ($repo->findByEmail(trim($_POST['email']))) $errors['email'][] = "Cet email est déjà utilisé.";
        if ($repo->exists(['username' => trim($_POST['username'])])) $errors['username'][] = "Ce nom d'utilisateur est déjà pris.";
        if ($errors) {
            $this->renderErrors($errors);
            return;
        }
        $user = new User(trim($_POST['email']), trim($_POST['firstName']), trim($_POST['lastName']), trim($_POST['username']), $_POST['password'], UserRole::USER);
        $em->persist($user); $em->flush(); header('Location: /login'); exit;
    }

    private function renderErrors(array $errors): void
    {
        $GLOBALS['register_errors'] = $errors;
        require __DIR__ . '/../View/register.php';
    }
}
