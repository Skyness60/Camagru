<?php
// app/Core/ORM/SchemaGenerator.php
namespace App\Core\ORM;

use ReflectionClass;
use ReflectionProperty;

class SchemaGenerator
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function generateTable(string $entityClass): void
    {
        $reflection = new ReflectionClass($entityClass);
        $attributes = $reflection->getAttributes(Table::class);

        if (count($attributes) === 0) {
            throw new \RuntimeException("Missing #[Table] attribute on class {$entityClass}");
        }

        /** @var Table $tableAttr */
        $tableAttr = $attributes[0]->newInstance();
        $table = $tableAttr->name;

        $columnsSql = [];

        /** @var ReflectionProperty $property */
        foreach ($reflection->getProperties() as $property) {
            $name = $property->getName();
            $type = $property->getType()?->getName();

            $sqlType = match ($type) {
                'int' => 'INT',
                'string' => 'VARCHAR(255)',
                'DateTimeImmutable', '\DateTimeImmutable' => 'DATETIME',
                'bool' => 'TINYINT(1)',
                default => null,
            };

            // Handle enum types
            if (!$sqlType && $type && enum_exists($type)) {
                // For enums, use VARCHAR with appropriate length
                $sqlType = 'VARCHAR(50)';
            }

            if (!$sqlType) {
                continue; // skip unknown or unsupported types (e.g. objects)
            }

            $null = $property->getType()?->allowsNull() ? 'NULL' : 'NOT NULL';

            // Primary key special case
            if ($name === 'id' && $sqlType === 'INT') {
                $columnsSql[] = "`id` INT AUTO_INCREMENT PRIMARY KEY";
                continue;
            }

            $columnsSql[] = "`$name` $sqlType $null";
        }

        $sql = sprintf(
            "CREATE TABLE IF NOT EXISTS `%s` (%s) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
            $table,
            implode(", ", $columnsSql)
        );

        $this->pdo->exec($sql);
    }
}
