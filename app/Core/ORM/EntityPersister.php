<?php
namespace App\Core\ORM;

use PDO;
use ReflectionClass;

class EntityPersister
{
    private EntityManager $em;
    private EntityMetadata $metadata;
    private string $tableName;
    private string $entityClass;

    public function __construct(EntityManager $em, string $entityClass)
    {
        $this->em = $em;
        $this->entityClass = $entityClass;
        $this->metadata = new EntityMetadata($entityClass);
        $this->tableName = $this->resolveTableName();
    }

    public function load(int $id): ?array
    {
        $stmt = $this->em->getConnection()->prepare("SELECT * FROM `{$this->metadata->getTableName()}` WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    public function loadAll(array $criteria = []): array
    {
        $sql = "SELECT * FROM `{$this->metadata->getTableName()}`";
        $params = [];

        if (!empty($criteria)) {
            $conditions = [];
            foreach ($criteria as $field => $value) {
                $conditions[] = "`$field` = :$field";
                $params[$field] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        $stmt = $this->em->getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
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

        // Remove id if present and null, so it's auto-incremented
        if (array_key_exists('id', $data) && $data['id'] === null) {
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

        $stmt = $this->em->getConnection()->prepare($sql);
        $stmt->execute($data);

        // Set the inserted id back to the entity
        if ($reflection->hasProperty('id')) {
            $idProperty = $reflection->getProperty('id');
            $idProperty->setAccessible(true);
            $idProperty->setValue($entity, (int)$this->em->getConnection()->lastInsertId());
        }
    }

    public function update(object $entity): void
    {
        $reflection = new ReflectionClass($entity);
        $data = $this->extractEntityData($entity, $reflection);

        // Remove id from set clause
        $columns = array_filter(array_keys($data), fn($col) => $col !== 'id');
        $setClause = implode(', ', array_map(fn($col) => "`$col` = :$col", $columns));

        $sql = sprintf(
            "UPDATE `%s` SET %s WHERE id = :id",
            $this->tableName,
            $setClause
        );

        $stmt = $this->em->getConnection()->prepare($sql);
        $stmt->execute($data);
    }

    public function delete(object $entity): void
    {
        $reflection = new ReflectionClass($entity);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $id = $idProperty->getValue($entity);
        $sql = "DELETE FROM `{$this->tableName}` WHERE id = :id";
        $stmt = $this->em->getConnection()->prepare($sql);
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

    public function loadAllPaginated(array $criteria, int $limit, int $offset): array
    {
        $sql = "SELECT * FROM `{$this->tableName}`";
        $params = [];
        $where = '';
        if (!empty($criteria)) {
            $conditions = [];
            foreach ($criteria as $field => $value) {
                $conditions[] = "`$field` = :$field";
                $params[$field] = $value;
            }
            $where = " WHERE " . implode(' AND ', $conditions);
        }
        // Get total count
        $countSql = "SELECT COUNT(*) FROM `{$this->tableName}`" . $where;
        $countStmt = $this->em->getConnection()->prepare($countSql);
        foreach ($params as $k => $v) {
            $countStmt->bindValue(":$k", $v);
        }
        $countStmt->execute();
        $total = (int)$countStmt->fetchColumn();

        // Get paginated results
        $sql .= $where . " LIMIT :limit OFFSET :offset";
        $stmt = $this->em->getConnection()->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue(":$k", $v);
        }
        $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return [
            'total' => $total,
            'results' => $results
        ];
    }

    public function validate(object $entity): array
    {
        $errors = [];
        $reflection = new ReflectionClass($entity);
        foreach ($reflection->getProperties() as $prop) {
            $prop->setAccessible(true);
            $type = $prop->getType();
            if ($type instanceof \ReflectionNamedType && $type->getName() === 'string') {
                $value = $prop->getValue($entity);
                if (!is_string($value) || trim($value) === '') {
                    $errors[] = "{$prop->getName()} must not be empty";
                }
            }
        }
        return $errors;
    }
}