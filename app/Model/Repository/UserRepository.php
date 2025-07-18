<?php
declare(strict_types=1);
namespace App\Model\Repository;

use App\Model\Entity\User;
use App\Core\ORM\EntityManager;

class UserRepository extends BaseRepository
{
    public function __construct(EntityManager $em)
    {
        parent::__construct($em, User::class);
    }

    // Méthodes spécifiques aux utilisateurs
    public function findByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }
}