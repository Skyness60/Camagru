<?php
namespace App\Core\ORM;

use PDO;
use ReflectionClass;

class SchemaGenerator
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function generateTable(string $entityClass): void
    {
        $reflection = new ReflectionClass($entityClass);
        $attributes = $reflection->getAttributes(Table::class);

        if (count($attributes) === 0) {
            throw new \RuntimeException("Missing #[Table] attribute on class {$entityClass}");
        }

        $tableName = $attributes[0]->newInstance()->name;
        $columns = [];

        foreach ($reflection->getProperties() as $property) {
            $columnName = $property->getName();
            $type = $this->getColumnType($property);
            
            if ($columnName === 'id') {
                $columns[] = "`id` INT AUTO_INCREMENT PRIMARY KEY";
            } else {
                $columns[] = "`$columnName` $type";
            }
        }

        $dropSql = "DROP TABLE IF EXISTS `$tableName`";
        $this->connection->exec($dropSql);

        $sql = sprintf(
            "CREATE TABLE `%s` (%s)",
            $tableName,
            implode(', ', $columns)
        );

        $this->connection->exec($sql);
    }

    private function getColumnType(\ReflectionProperty $property): string
    {
        $type = $property->getType();
        
        if (!$type instanceof \ReflectionNamedType) {
            return 'TEXT';
        }

        return match ($type->getName()) {
            'int' => 'INT',
            'float' => 'FLOAT',
            'bool' => 'BOOLEAN',
            'string' => 'VARCHAR(255)',
            \DateTimeImmutable::class => 'DATETIME',
            default => $this->handleEnumType($type) ?: 'VARCHAR(255)'
        };
    }

    private function handleEnumType(\ReflectionNamedType $type): ?string
    {
        $typeName = $type->getName();
        
        if (enum_exists($typeName)) {
            $reflection = new \ReflectionEnum($typeName);
            if ($reflection->isBacked()) {
                $backingType = $reflection->getBackingType();
                return match ($backingType->getName()) {
                    'int' => 'INT',
                    'string' => 'VARCHAR(50)',
                    default => 'VARCHAR(255)'
                };
            }
        }
        
        return null;
    }
}