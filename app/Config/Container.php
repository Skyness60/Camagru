<?php
namespace App\Config;

use App\Core\ORM\EntityManager;
use App\Service\Request;
use App\Service\RegisterService;

class Container
{
    private array $instances = [];

    public function get(string $id): mixed
    {
        return $this->instances[$id] ??= $this->build($id);
    }

    private function build(string $id): mixed
    {
        return match ($id) {
            \PDO::class => Database::getPdo(),
            EntityManager::class => new EntityManager($this->get(\PDO::class)),
            Request::class => new Request(),
            RegisterService::class => new RegisterService($this->get(EntityManager::class)),
            default => throw new \RuntimeException("Service non trouvé : $id"),
        };
    }
}
