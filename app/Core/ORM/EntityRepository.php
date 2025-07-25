<?php
namespace App\Core\ORM;

class EntityRepository
{
    public function __construct(
        private readonly EntityManager $em,
        private readonly string $entityClass
    ) {}

    public function find(int $id): ?object
    {
        return $this->em->find($this->entityClass, $id);
    }

    public function findAll(): array
    {
        return $this->findBy([]);
    }

    public function findBy(array $criteria): array
    {
        $dataArray = $this->em->getEntityPersister($this->entityClass)->loadAll($criteria);
        return $this->em->getHydrator()->hydrateAll($dataArray, $this->entityClass);
    }

    public function findOneBy(array $criteria): ?object
    {
        $data = $this->em->getEntityPersister($this->entityClass)->loadOneBy($criteria);
        return $data ? $this->em->getHydrator()->hydrate($data, $this->entityClass) : null;
    }

    public function exists(array $criteria): bool
    {
        return $this->findOneBy($criteria) !== null;
    }
}