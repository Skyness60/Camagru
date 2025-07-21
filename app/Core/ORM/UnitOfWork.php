<?php
namespace App\Core\ORM;

class UnitOfWork
{
    private EntityManager $em;
    private array $identityMap = [];
    private array $entityStates = [];
    private array $scheduledForInsert = [];
    private array $scheduledForUpdate = [];
    private array $scheduledForDelete = [];

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function persist(object $entity): void
    {
        $oid = spl_object_id($entity);
        $persister = $this->em->getEntityPersister(get_class($entity));
        if (method_exists($persister, 'validate')) {
            $errors = $persister->validate($entity);
            if (!empty($errors)) {
                throw new \InvalidArgumentException('Validation failed: ' . implode(', ', $errors));
            }
        }
        $reflection = new \ReflectionClass($entity);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $id = $idProperty->getValue($entity);
        if ($id === null) {
            // Nouvelle entité
            if (!isset($this->entityStates[$oid])) {
                $this->entityStates[$oid] = 'NEW';
                $this->scheduledForInsert[$oid] = $entity;
            }
        } else {
            // Entité existante, planifier pour update
            $this->entityStates[$oid] = 'MANAGED';
            $this->scheduledForUpdate[$oid] = $entity;
        }
    }

    public function remove(object $entity): void
    {
        $oid = spl_object_id($entity);
        $this->scheduledForDelete[$oid] = $entity;
    }

    public function flush(): void
    {
        // Execute inserts
        foreach ($this->scheduledForInsert as $entity) {
            $this->executeInsert($entity);
        }

        // Execute updates
        foreach ($this->scheduledForUpdate as $entity) {
            $this->executeUpdate($entity);
        }

        // Execute deletes
        foreach ($this->scheduledForDelete as $entity) {
            $this->executeDelete($entity);
        }

        $this->clear();
    }

    public function clear(): void
    {
        $this->scheduledForInsert = [];
        $this->scheduledForUpdate = [];
        $this->scheduledForDelete = [];
    }

    private function executeInsert(object $entity): void
    {
        // Validation via persister
        $persister = $this->em->getEntityPersister(get_class($entity));
        if (method_exists($persister, 'validate')) {
            $errors = $persister->validate($entity);
            if (!empty($errors)) {
                throw new \InvalidArgumentException('Validation failed: ' . implode(', ', $errors));
            }
        }
        $persister->insert($entity);
    }

    private function executeUpdate(object $entity): void
    {
        $persister = $this->em->getEntityPersister(get_class($entity));
        $persister->update($entity);
    }

    private function executeDelete(object $entity): void
    {
        $persister = $this->em->getEntityPersister(get_class($entity));
        $persister->delete($entity);
    }
}