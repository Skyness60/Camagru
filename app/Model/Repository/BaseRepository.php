<?php
// app/Model/Repository/BaseRepository.php
namespace App\Core\ORM;

use PDO;

class BaseRepository
{
    protected EntityManager $em;
    protected string $entityClass;

    public function __construct(EntityManager $em, string $entityClass)
    {
        $this->em = $em;
        $this->entityClass = $entityClass;
    }

    public function find(int $id): ?object
    {
        return $this->em->find($id);
    }

    /**
     * Recherche par critère simple (ex: ['username' => 'Sami'])
     * Retourne le premier résultat ou null
     */
    public function findOneBy(array $criteria): ?object
    {
        $table = $this->em->resolveTableNameForEntity($this->entityClass);
        $fields = array_keys($criteria);
        $where = implode(' AND ', array_map(fn($f) => "$f = :$f", $fields));

        $sql = "SELECT * FROM $table WHERE $where LIMIT 1";
        $stmt = $this->em->getConnection()->prepare($sql);
        $stmt->execute($criteria);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? $this->em->mapToEntityForClass($data, $this->entityClass) : null;
    }
}
