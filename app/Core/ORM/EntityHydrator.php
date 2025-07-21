<?php
// app/Core/ORM/EntityHydrator.php
namespace App\Core\ORM;

use ReflectionClass;

class EntityHydrator
{
    public function hydrate(array $data, string $entityClass): object
    {
        $reflection = new ReflectionClass($entityClass);
        $constructor = $reflection->getConstructor();

        $args = [];
        if ($constructor) {
            foreach ($constructor->getParameters() as $param) {
                $name = $param->getName();
                $value = $data[$name] ?? null;
                
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
                
                if ($type && !$type->isBuiltin() && enum_exists($type->getName())) {
                    $enumClass = $type->getName();
                    $value = $enumClass::from($value);
                }
                
                if ($type && ($type->getName() === 'DateTimeImmutable' || $type->getName() === '\DateTimeImmutable') && is_string($value)) {
                    $value = new \DateTimeImmutable($value);
                }
                
                $prop->setAccessible(true);
                $prop->setValue($instance, $value);
            }
        }

        return $instance;
    }

    public function hydrateAll(array $dataArray, string $entityClass): array
    {
        return array_map(fn($data) => $this->hydrate($data, $entityClass), $dataArray);
    }
}