<?php
namespace App\Service;

class RegisterValidator
{
    private array $data;
    private array $errors = [];
    private $em;

    public function __construct(array $data, $entityManager = null)
    {
        $this->data = $data;
        $this->em = $entityManager;
    }

    public function validate(): bool
    {
        $username = trim($this->data['username'] ?? '');
        $email = trim($this->data['email'] ?? '');
        $password = $this->data['password'] ?? '';
        $confirm = $this->data['confirmPassword'] ?? '';
        $blacklist = ['admin', 'root', 'test'];

        // Username: required, length, format, blacklist
        if ($username === '' || strlen($username) < 3 || strlen($username) > 32) {
            $this->errors['generic'][] = "Informations invalides.";
        }
        if ($username !== '' && !preg_match('/^[a-zA-Z0-9_\-]+$/', $username)) {
            $this->errors['generic'][] = "Informations invalides.";
        }
        if ($username !== '' && in_array(strtolower($username), $blacklist, true)) {
            $this->errors['generic'][] = "Informations invalides.";
        }

        // Email: required, format
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['generic'][] = "Informations invalides.";
        }

        // Password: required, length
        if ($password === '' || strlen($password) < 8 || strlen($password) > 64) {
            $this->errors['generic'][] = "Informations invalides.";
        }

        // Confirm password: required, match
        if ($confirm === '' || $confirm !== $password) {
            $this->errors['generic'][] = "Informations invalides.";
        }

        // Unicité username/email (si EntityManager fourni)
        if (empty($this->errors['generic']) && $this->em) {
            try {
                $repo = $this->em->getRepository(\App\Model\Entity\User::class, \App\Model\Repository\UserRepository::class);
                if ($repo->findOneBy(['username' => $username])) {
                    $this->errors['generic'][] = "Informations invalides.";
                }
                if ($repo->findOneBy(['email' => $email])) {
                    $this->errors['generic'][] = "Informations invalides.";
                }
            } catch (\Throwable $e) {
                $this->errors['generic'][] = "Informations invalides.";
            }
        }

        return empty($this->errors['generic']);
    }

    public function getErrors(): array
    {
        // Toujours retourner la clé 'generic' pour cohérence
        return !empty($this->errors['generic']) ? ['generic' => $this->errors['generic']] : [];
    }
}
