<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\User;
use App\Model\Entity\UserRole;
use App\Model\Repository\UserRepository;
use App\Core\ORM\EntityManager;
use App\Service\RegisterValidator;
use App\Service\RegisterService;

class RegisterController extends BaseController
{
    public function showForm(): void
    {
        $this->render('register');
    }

    public function handleRegister(): void
    {
        if (!$this->request->isMethod('POST')) {
            $this->render('register');
            return;
        }

        $data = $this->request->input();

        $validator = new RegisterValidator($data);
        if (!$validator->validate()) {
            $this->render('register', ['errors' => $validator->getErrors()]);
            return;
        }

        $registerService = $this->container->get(RegisterService::class);
        $errors = $registerService->register($data);

        if (!empty($errors)) {
            $this->render('register', ['errors' => $errors]);
            return;
        }

        header('Location: /login');
        exit;
    }
}