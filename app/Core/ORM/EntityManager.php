<?php
namespace App\Core\ORM;

use PDO;

class EntityManager
{
    private PDO $connection;
    private EntityHydrator $hydrator;
    private array $persisters = [];
    
    private UnitOfWork $unitOfWork;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->hydrator = new EntityHydrator();
        $this->unitOfWork = new UnitOfWork($this);
    }

    public function find(string $entityClass, int $id): ?object
    {
        $persister = $this->getEntityPersister($entityClass);
        $data = $persister->load($id);
        
        return $data ? $this->hydrator->hydrate($data, $entityClass) : null;
    }

    public function findAll(string $entityClass): array
    {
        $persister = $this->getEntityPersister($entityClass);
        $dataArray = $persister->loadAll();
        
        return $this->hydrator->hydrateAll($dataArray, $entityClass);
    }

    public function findBy(string $entityClass, array $criteria): array
    {
        $persister = $this->getEntityPersister($entityClass);
        $dataArray = $persister->loadAll($criteria);
        
        return $this->hydrator->hydrateAll($dataArray, $entityClass);
    }

    public function findOneBy(string $entityClass, array $criteria): ?object
    {
        $persister = $this->getEntityPersister($entityClass);
        $data = $persister->loadOneBy($criteria);
        
        return $data ? $this->hydrator->hydrate($data, $entityClass) : null;
    }

    public function getRepository(string $entityClass): EntityRepository
    {
        return new EntityRepository($this, $entityClass);
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    public function getEntityPersister(string $entityClass): EntityPersister
    {
        if (!isset($this->persisters[$entityClass])) {
            $this->persisters[$entityClass] = new EntityPersister($this->connection, $entityClass);
        }
        
        return $this->persisters[$entityClass];
    }


   public function persist(object $entity): void
    {
        $this->unitOfWork->persist($entity);
    }

    public function remove(object $entity): void
    {
        $this->unitOfWork->remove($entity);
    }

    public function flush(): void
    {
        $this->unitOfWork->flush();
    }

    public function clear(): void
    {
        $this->unitOfWork->clear();
    }
}