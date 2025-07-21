<?php
// app/Core/ORM/EntityHydrator.php
namespace App\Core\ORM;

use ReflectionClass;

class EntityHydrator
{
    private EntityManager $em;

    public function __construct(?EntityManager $em = null)
    {
        if ($em) {
            $this->em = $em;
        }
    }

    public function setEntityManager(EntityManager $em): void
    {
        $this->em = $em;
    }

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
                if ($type instanceof \ReflectionNamedType) {
                    $typeName = $type->getName();
                    if (!$type->isBuiltin() && enum_exists($typeName)) {
                        $value = $typeName::from($value);
                    }
                }
                $args[] = $value;
            }
        }

        $instance = $reflection->newInstanceArgs($args);

        foreach ($reflection->getProperties() as $prop) {
            $key = $prop->getName();
            $value = $data[$key] ?? null;
            $type = $prop->getType();
            if ($type instanceof \ReflectionNamedType) {
                $typeName = $type->getName();
                if (!$type->isBuiltin() && enum_exists($typeName) && $value !== null) {
                    $value = $typeName::from($value);
                }
                if (($typeName === 'DateTimeImmutable' || $typeName === '\DateTimeImmutable') && is_string($value)) {
                    $value = new \DateTimeImmutable($value);
                }
                // Gestion des relations ManyToOne
                $manyToOneAttr = $prop->getAttributes('ManyToOne');
                if (!empty($manyToOneAttr) && isset($this->em) && isset($data[$key . '_id'])) {
                    $targetClass = $manyToOneAttr[0]->getArguments()[0] ?? $typeName;
                    $repo = $this->em->getRepository($targetClass);
                    $value = $repo->find($data[$key . '_id']);
                }
                // Gestion des relations OneToMany (collection d'entités)
                $oneToManyAttr = $prop->getAttributes('OneToMany');
                if (!empty($oneToManyAttr) && isset($this->em)) {
                    $targetClass = $oneToManyAttr[0]->getArguments()[0];
                    $mappedBy = $oneToManyAttr[0]->getArguments()[1] ?? $reflection->getShortName();
                    $repo = $this->em->getRepository($targetClass);
                    $value = $repo->findBy([$mappedBy . '_id' => $data['id'] ?? null]);
                }
            }
            $prop->setAccessible(true);
            $prop->setValue($instance, $value);
        }

        return $instance;
    }

    public function hydrateAll(array $dataArray, string $entityClass): array
    {
        return array_map(fn($data) => $this->hydrate($data, $entityClass), $dataArray);
    }
}