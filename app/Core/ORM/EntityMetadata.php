<?php
namespace App\Core\ORM;

use ReflectionClass;

class EntityMetadata
{
    private string $entityClass;
    private string $tableName;
    private array $properties = [];

    public function __construct(string $entityClass)
    {
        $this->entityClass = $entityClass;
        $this->tableName = $this->resolveTableName();
        $this->properties = $this->resolveProperties();
    }

    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function getProperties(): array
    {
        return $this->properties;
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

    private function resolveProperties(): array
    {
        $reflection = new ReflectionClass($this->entityClass);
        $props = [];
        foreach ($reflection->getProperties() as $property) {
            $props[] = $property->getName();
        }
        return $props;
    }
}
