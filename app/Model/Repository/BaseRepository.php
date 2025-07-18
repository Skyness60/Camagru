<?php
namespace App\Model\Repository;

use App\Core\ORM\EntityRepository;

class BaseRepository extends EntityRepository
{
    // Méthodes communes optionnelles
    public function count(array $criteria = []): int
    {
        return count($this->findBy($criteria));
    }
    
    public function exists(array $criteria): bool
    {
        return $this->findOneBy($criteria) !== null;
    }
}