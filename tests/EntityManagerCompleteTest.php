<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Core\ORM\EntityManager;
use App\Model\Entity\User;
use PHPUnit\Framework\Attributes\CoversClass;
use App\Model\Entity\UserRole;
use App\Config\EnvLoader;
use PDO;

#[CoversClass(EntityManager::class)]
final class EntityManagerCompleteTest extends TestCase
{
    private EntityManager $em;
    private PDO $pdo;

    protected function setUp(): void
    {
        EnvLoader::load(__DIR__ . '/../.env');
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=utf8mb4',
            $_ENV['MYSQL_HOST'],
            $_ENV['MYSQL_DATABASE']
        );
        $this->pdo = new PDO($dsn, $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASSWORD']);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->createMySQLTable($this->pdo);
        $this->em = new EntityManager($this->pdo);
    }

    private function createMySQLTable(PDO $pdo): void
    {
        $pdo->exec('DROP TABLE IF EXISTS users');
        $sql = "
            CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(255) NOT NULL,
                firstName VARCHAR(255) NOT NULL,
                lastName VARCHAR(255) NOT NULL,
                username VARCHAR(255) NOT NULL,
                password VARCHAR(255) NOT NULL,
                role VARCHAR(50) NOT NULL,
                createdAt DATETIME NOT NULL,
                updatedAt DATETIME NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";
        $pdo->exec($sql);
    }

    public function testCompleteLifecycle(): void
    {
        // Create new user
        $user = new User(
            email: 'test@example.com',
            firstName: 'John',
            lastName: 'Doe',
            username: 'johndoe',
            password: 'password123',
            role: UserRole::USER
        );

        // Vérifier que l'ID est null avant persist
        $this->assertNull($user->getId());

        // Persist
        $this->em->persist($user);
        $this->em->flush();

        // Vérifier que l'ID a été assigné
        $this->assertNotNull($user->getId());
        $this->assertIsInt($user->getId());

        // Find
        $foundUser = $this->em->find(User::class, $user->getId());
        $this->assertNotNull($foundUser);
        $this->assertEquals('johndoe', $foundUser->getUsername());

        // Delete
        $this->em->remove($foundUser);
        $this->em->flush();

        // Verify deletion
        $deletedUser = $this->em->find(User::class, $user->getId());
        $this->assertNull($deletedUser);
    }
}