<?php
// tests/EntityManagerTest.php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use App\Core\ORM\EntityManager;
use App\Core\ORM\SchemaGenerator;
use PHPUnit\Framework\Attributes\CoversClass;
use App\Config\Database;
use App\Config\EnvLoader;
use App\Model\Repository\UserRepository;

use App\Model\Entity\User;

#[CoversClass(User::class)]
#[CoversClass(EntityManager::class)]
final class EntityManagerTest extends TestCase
{

    public static function setUpBeforeClass(): void
    {
        EnvLoader::load(__DIR__ . '/../.env');
    }

    public function testFindReturnsEntity(): void {
        $connection = Database::getPdo();
        $schema = new SchemaGenerator($connection);
        $schema->generateTable(User::class);
        $em = new EntityManager(User::class, $connection);
        $repo = new UserRepository($em);
        $user = $repo->find(1);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Sami', $user->getUsername());
    }

    public function testFindReturnsNullWhenNoEntityFound(): void {
        $connection = Database::getPdo();
        $schema = new SchemaGenerator($connection);
        $schema->generateTable(User::class);
        $em = new EntityManager(User::class, $connection);
        $repo = new UserRepository($em);
        $result = $repo->find(9999); // 9999 does not exist

        $this->assertNull($result);
    }
}