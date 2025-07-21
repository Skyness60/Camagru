<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\User;
use App\Model\Entity\UserRole;
use App\Model\Repository\UserRepository;
use App\Core\ORM\EntityManager;
use App\Config\EnvLoader;

class RegisterController
{
    public function showForm(): void
    {
        require __DIR__ . '/../View/register.php';
    }

    public function handleRegister(): void
    {
        EnvLoader::load(__DIR__ . '/../../.env');
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = trim($_POST['firstName'] ?? '');
            $lastName = trim($_POST['lastName'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmPassword'] ?? '';

            if (!$firstName || !$lastName || !$email || !$username || !$password || !$confirmPassword) {
                $errors[] = "Tous les champs sont obligatoires.";
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email invalide.";
            }
            if ($password !== $confirmPassword) {
                $errors[] = "Les mots de passe ne correspondent pas.";
            }

            if ($errors) {
                // Pass $errors to the view (simple way)
                $GLOBALS['register_errors'] = $errors;
                require __DIR__ . '/../View/register.php';
                return;
            }

            // Create PDO connection
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=utf8mb4',
                $_ENV['MYSQL_HOST'],
                $_ENV['MYSQL_DATABASE']
            );
            $pdo = new \PDO($dsn, $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASSWORD']);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $em = new EntityManager($pdo);
            $userRepo = new UserRepository($em);

            if ($userRepo->findByEmail($email)) {
                $errors[] = "Cet email est déjà utilisé.";
            }
            if ($userRepo->exists(['username' => $username])) {
                $errors[] = "Ce nom d'utilisateur est déjà pris.";
            }

            if ($errors) {
                $GLOBALS['register_errors'] = $errors;
                require __DIR__ . '/../View/register.php';
                return;
            }

            $user = new User($email, $firstName, $lastName, $username, $password, UserRole::USER);
            $em->persist($user);
            $em->flush();
            header('Location: /login');
            exit;
        }
        $GLOBALS['register_errors'] = $errors;
        require __DIR__ . '/../View/register.php';
    }
}
