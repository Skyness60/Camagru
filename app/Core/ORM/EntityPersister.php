<?php
namespace App\Core\ORM;

use PDO;
use ReflectionClass;

class EntityPersister
{
    private PDO $connection;
    private string $entityClass;
    private string $tableName;

    public function __construct(PDO $connection, string $entityClass)
    {
        $this->connection = $connection;
        $this->entityClass = $entityClass;
        $this->tableName = $this->resolveTableName();
    }

    public function load(int $id): ?array
    {
        $stmt = $this->connection->prepare("SELECT * FROM `{$this->tableName}` WHERE id = :id");
        $stmt->execute(['id' => $id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function loadAll(array $criteria = []): array
    {
        $sql = "SELECT * FROM `{$this->tableName}`";
        $params = [];

        if (!empty($criteria)) {
            $conditions = [];
            foreach ($criteria as $field => $value) {
                $conditions[] = "`$field` = :$field";
                $params[$field] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function loadOneBy(array $criteria): ?array
    {
        $results = $this->loadAll($criteria);
        return $results[0] ?? null;
    }

    public function insert(object $entity): void
    {
        $reflection = new ReflectionClass($entity);
        $data = $this->extractEntityData($entity, $reflection);
        
        if ($data['id'] === null) {
            unset($data['id']);
        }
        
        $columns = array_keys($data);
        $placeholders = array_map(fn($col) => ":$col", $columns);
        
        $sql = sprintf(
            "INSERT INTO `%s` (%s) VALUES (%s)",
            $this->tableName,
            implode(', ', array_map(fn($col) => "`$col`", $columns)),
            implode(', ', $placeholders)
        );
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($data);
        
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($entity, (int)$this->connection->lastInsertId());
    }

    public function update(object $entity): void
    {
        $reflection = new ReflectionClass($entity);
        $data = $this->extractEntityData($entity, $reflection);
        
        $id = $data['id'];
        unset($data['id']);
        
        $setClause = implode(', ', array_map(fn($col) => "`$col` = :$col", array_keys($data)));
        
        $sql = sprintf(
            "UPDATE `%s` SET %s WHERE id = :id",
            $this->tableName,
            $setClause
        );
        
        $data['id'] = $id;
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($data);
    }

    public function delete(object $entity): void
    {
        $reflection = new ReflectionClass($entity);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $id = $idProperty->getValue($entity);
        
        $sql = "DELETE FROM `{$this->tableName}` WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id' => $id]);
    }

    private function extractEntityData(object $entity, ReflectionClass $reflection): array
    {
        $data = [];
        
        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($entity);
            
            // Handle different types
            if ($value instanceof \DateTimeImmutable) {
                $value = $value->format('Y-m-d H:i:s');
            } elseif ($value instanceof \BackedEnum) {
                $value = $value->value;
            }
            
            $data[$property->getName()] = $value;
        }
        
        return $data;
    }

    private function resolveTableName(): string
    {
        $reflection = new ReflectionClass($this->entityClass);
        $attributes = $reflection->getAttributes(Table::class);

        if (count($attributes) === 0) {
            throw new \RuntimeException("Missing #[Table] attribute on class {$this->entityClass}");
        }

        return $attributes[0]->newInstance()->name;
    }
}