<?php
namespace App\Core\ORM;

class EntityRepository
{
    protected EntityManager $em;
    protected string $entityClass;

    public function __construct(EntityManager $em, string $entityClass)
    {
        $this->em = $em;
        $this->entityClass = $entityClass;
    }

    public function find(int $id): ?object
    {
        return $this->em->find($this->entityClass, $id);
    }

    public function findAll(): array
    {
        return $this->em->findAll($this->entityClass);
    }

    public function findBy(array $criteria): array
    {
        return $this->em->findBy($this->entityClass, $criteria);
    }

    public function findOneBy(array $criteria): ?object
    {
        return $this->em->findOneBy($this->entityClass, $criteria);
    }
}