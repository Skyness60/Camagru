<?php
namespace App\Service;

use App\Model\Entity\User;
use App\Model\Entity\UserRole;
use App\Model\Repository\UserRepository;
use App\Core\ORM\EntityManager;

class RegisterService
{
    private EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function register(array $data): array
    {
        $repo = $this->em->getRepository(User::class, UserRepository::class);
        $errors = [];

        if ($repo->findOneBy(['email' => trim($data['email'])])) {
            $errors['email'][] = "Cet email est déjà utilisé.";
        }
        if ($repo->exists(['username' => trim($data['username'])])) {
            $errors['username'][] = "Ce nom d'utilisateur est déjà pris.";
        }
        if ($errors) {
            return $errors;
        }

        $user = new User(
            trim($data['email']),
            trim($data['firstName'] ?? ''),
            trim($data['lastName'] ?? ''),
            trim($data['username']),
            $data['password'],
            UserRole::USER
        );
        $this->em->persist($user);
        $this->em->flush();

        return [];
    }
}
