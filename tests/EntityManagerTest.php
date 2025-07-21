<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use App\Core\ORM\EntityManager;
use App\Core\ORM\SchemaGenerator;
use App\Model\Repository\UserRepository;
use App\Model\Entity\User;
use App\Config\Database;
use App\Config\EnvLoader;

#[CoversClass(EntityManager::class)]

final class EntityManagerTest extends TestCase
{
    private EntityManager $em;

    public static function setUpBeforeClass(): void
    {
        EnvLoader::load(__DIR__ . '/../.env');
    }

    protected function setUp(): void
    {
        $connection = Database::getPdo();
        $schema = new SchemaGenerator($connection);
        $schema->generateTable(User::class);
        
        $this->insertTestData($connection);
        
        $this->em = new EntityManager($connection);
    }

    private function insertTestData(\PDO $connection): void
    {
        $sql = "INSERT INTO users (email, firstName, lastName, username, password, role, createdAt) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->execute([
            'sami@example.com',
            'Sami',
            'Test',
            'Sami',
            password_hash('password123', PASSWORD_BCRYPT),
            'user',
            (new \DateTimeImmutable())->format('Y-m-d H:i:s')
        ]);
    }

    public function testFindReturnsEntity(): void {
        $repo = new UserRepository($this->em);
        
        $user = $repo->find(1);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Sami', $user->getUsername());
    }

    public function testFindReturnsNullWhenNoEntityFound(): void {
        $repo = new UserRepository($this->em);
        
        $result = $repo->find(9999); // 9999 does not exist

        $this->assertNull($result);
    }

    public function testFindAllReturnsArrayOfEntities()
    {
        $repo = new UserRepository($this->em);
        $result = $repo->findAll();
        $this->assertIsArray($result);
        foreach ($result as $entity) {
            $this->assertInstanceOf(User::class, $entity);
        }
    }

}