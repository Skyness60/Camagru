<?php
// app/Model/Entity/User.php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\Entity\UserRole;
use App\Core\ORM\Table;

#[Table('users')]
class User
{
    private ?int $id = null;

    private string $email;

    private string $firstName;

    private string $lastName;

    private string $username;

    private string $password;

    private UserRole $role;

    private \DateTimeImmutable $createdAt;

    private ?\DateTimeImmutable $updatedAt;

    public function __construct(string $email, string $firstName, string $lastName, string $username, string $password, UserRole $role)
    {
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->username = $username;
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        $this->role = $role;
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = null;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRole(): UserRole
    {
        return $this->role;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function setRole(UserRole $role): void
    {
        $this->role = $role;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getIdOrFail(): int
    {
        if ($this->id === null) {
            throw new \RuntimeException('User ID is not set');
        }
        return $this->id;
    }
}
