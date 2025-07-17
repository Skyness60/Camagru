<?php
// app/Core/ORM/EntityManager.php

namespace App\Core\ORM;

use PDO;
use ReflectionClass;

class EntityManager
{
    private string $entityClass;

    private PDO $connection;

    public function __construct(string $entityClass, PDO $connection)
    {
        if (!class_exists($entityClass)) {
            throw new \InvalidArgumentException("Entity class {$entityClass} does not exist.");
        }

        $this->entityClass = $entityClass;
        $this->connection = $connection;
    }

    public function find(int $id): ?object
    {
        $table = $this->resolveTableName();

        $stmt = $this->connection->prepare("SELECT * FROM {$table} WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            return null;
        }

        return $data ? $this->mapToEntity($data) : null;
    }

    private function resolveTableName(): string
    {
        $reflection = new ReflectionClass($this->entityClass);
        $attributes = $reflection->getAttributes(Table::class);

        if (count($attributes) === 0) {
            throw new \RuntimeException("Missing #[Table] attribute on class {$this->entityClass}");
        }

        /** @var Table $tableAttr */
        $tableAttr = $attributes[0]->newInstance();

        return $tableAttr->name;
    }

    private function mapToEntity(array $data): object
    {
        $refl = new ReflectionClass($this->entityClass);
        $constructor = $refl->getConstructor();

        $args = [];
        if ($constructor) {
            foreach ($constructor->getParameters() as $param) {
                $name = $param->getName();
                $value = $data[$name] ?? null;
                
                // Handle enum types
                $type = $param->getType();
                if ($type && !$type->isBuiltin() && enum_exists($type->getName())) {
                    $enumClass = $type->getName();
                    $value = $enumClass::from($value);
                }
                
                $args[] = $value;
            }
        }

        $instance = $refl->newInstanceArgs($args);

        foreach ($data as $key => $value) {
            if ($refl->hasProperty($key)) {
                $prop = $refl->getProperty($key);
                $type = $prop->getType();
                
                // Handle enum types for properties
                if ($type && !$type->isBuiltin() && enum_exists($type->getName())) {
                    $enumClass = $type->getName();
                    $value = $enumClass::from($value);
                }
                
                // Handle DateTimeImmutable types
                if ($type && ($type->getName() === 'DateTimeImmutable' || $type->getName() === '\DateTimeImmutable') && is_string($value)) {
                    $value = new \DateTimeImmutable($value);
                }
                
                $prop->setAccessible(true);
                $prop->setValue($instance, $value);
            }
        }

        return $instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    public function resolveTableNameForEntity(string $entityClass): string
    {
        $reflection = new ReflectionClass($entityClass);
        $attributes = $reflection->getAttributes(Table::class);

        if (count($attributes) === 0) {
            throw new \RuntimeException("Missing #[Table] attribute on class {$entityClass}");
        }

        /** @var Table $tableAttr */
        $tableAttr = $attributes[0]->newInstance();

        return $tableAttr->name;
    }

    public function mapToEntityForClass(array $data, string $entityClass): object
    {
        $reflection = new ReflectionClass($entityClass);
        $constructor = $reflection->getConstructor();

        $args = [];
        if ($constructor) {
            foreach ($constructor->getParameters() as $param) {
                $name = $param->getName();
                $value = $data[$name] ?? null;
                
                // Handle enum types
                $type = $param->getType();
                if ($type && !$type->isBuiltin() && enum_exists($type->getName())) {
                    $enumClass = $type->getName();
                    $value = $enumClass::from($value);
                }
                
                $args[] = $value;
            }
        }

        $instance = $reflection->newInstanceArgs($args);

        foreach ($data as $key => $value) {
            if ($reflection->hasProperty($key)) {
                $prop = $reflection->getProperty($key);
                $type = $prop->getType();
                
                // Handle enum types for properties
                if ($type && !$type->isBuiltin() && enum_exists($type->getName())) {
                    $enumClass = $type->getName();
                    $value = $enumClass::from($value);
                }
                
                // Handle DateTimeImmutable types
                if ($type && ($type->getName() === 'DateTimeImmutable' || $type->getName() === '\DateTimeImmutable') && is_string($value)) {
                    $value = new \DateTimeImmutable($value);
                }
                
                $prop->setAccessible(true);
                $prop->setValue($instance, $value);
            }
        }

        return $instance;
    }

}
