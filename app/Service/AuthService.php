<?php
namespace App\Service;


use App\Core\ORM\EntityManager;
use App\Core\ORM\EntityRepository;

class AuthService
{
    private EntityManager $em;
    private EntityRepository $repo;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(\App\Model\Entity\User::class);
    }

    public function getUser(string $username)
    {
        return $this->repo->findOneBy(['username' => trim($username)]);
    }

    public function verifyPassword($user, string $password): bool
    {
        if (!$user) return false;
        return password_verify($password, $user->getPassword());
    }
}
