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
        $this->hydrator = new EntityHydrator($this);
        $this->unitOfWork = new UnitOfWork($this);
    }

    public function find(string $entityClass, int $id): ?object
    {
        $persister = $this->getEntityPersister($entityClass);
        $data = $persister->load($id);
        
        return $data ? $this->hydrator->hydrate($data, $entityClass) : null;
    }

    private function fetchEntities(string $entityClass, ?array $criteria = null): array
    {
        $persister = $this->getEntityPersister($entityClass);
        $dataArray = $persister->loadAll($criteria ?? []);
        $this->hydrator->setEntityManager($this);
        return $this->hydrator->hydrateAll($dataArray, $entityClass);
    }

    public function paginate(string $entityClass, array $criteria = [], int $limit = 10, int $offset = 0): array
    {
        $persister = $this->getEntityPersister($entityClass);
        $pageData = $persister->loadAllPaginated($criteria, $limit, $offset);
        $this->hydrator->setEntityManager($this);
        $hydrated = $this->hydrator->hydrateAll($pageData['results'], $entityClass);
        return [
            'total' => $pageData['total'],
            'results' => $hydrated
        ];
    }
    /**
     * Valide une entité via le persister
     * Retourne un tableau d'erreurs
     */
    public function validate(object $entity): array
    {
        $persister = $this->getEntityPersister(get_class($entity));
        return method_exists($persister, 'validate') ? $persister->validate($entity) : [];
    }

    public function findAll(string $entityClass): array
    {
        return $this->fetchEntities($entityClass);
    }

    public function findBy(string $entityClass, array $criteria): array
    {
        return $this->fetchEntities($entityClass, $criteria);
    }

    public function getHydrator(): EntityHydrator
    {
        return $this->hydrator;
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
            $this->persisters[$entityClass] = new EntityPersister($this, $entityClass);
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