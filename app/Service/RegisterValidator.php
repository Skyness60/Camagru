<?php
namespace App\Service;

class RegisterValidator
{
    private array $data;
    private array $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function validate(): bool
    {
        if (empty($this->data['username'])) {
            $this->errors['username'][] = "Le nom d'utilisateur est requis.";
        }
        if (empty($this->data['password']) || strlen($this->data['password']) < 8) {
            $this->errors['password'][] = "Le mot de passe doit faire au moins 8 caractères.";
        }
        if (empty($this->data['confirmPassword']) || $this->data['confirmPassword'] !== $this->data['password']) {
            $this->errors['confirmPassword'][] = "Les mots de passe ne correspondent pas.";
        }
        if (empty($this->data['email'])) {
            $this->errors['email'][] = "L'email est requis.";
        }
        // Ajoute d'autres règles si besoin
        return empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
