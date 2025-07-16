<?php
// app/Model/User.php
declare(strict_types=1);

namespace App\Models;

enum UserPermission: string {
    case USER = 'user';
    case ADMIN = 'admin';
}

class User
{
    public int $id;

    private string $email;
    private string $firstName;
    private string $lastName;
    private string $username;
    private string $password;
    private UserPermission $permission;

    private \DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $updatedAt;

    public function __construct(string $email, string $firstName, string $lastName, string $username, string $password, UserPermission $permission)
    {
        $this->id = random_int(1, 1000); // Pour l'instant on a pas de table de base de données
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->username = $username;
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        $this->permission = $permission;
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = null;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
    public function getFirstName(): string
    {
        return $this->firstName;
    }
    public function getLastName(): string
    {
        return $this->lastName;
    }
    public function getUsername(): string
    {
        return $this->username;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function getPermission(): UserPermission
    {
        return $this->permission;
    }
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
