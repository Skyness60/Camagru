<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Core\ORM\EntityManager;
use App\Model\Entity\User;
use App\Model\Entity\UserRole;
use App\Config\EnvLoader;
use App\Config\Database;

final class ORMFullLifecycleTest extends TestCase
{
    private EntityManager $em;
    private PDO $pdo;

    public static function setUpBeforeClass(): void
    {
        $envPath = __DIR__ . '/../.env';
        // Ensure .env file exists for tests
        if (!file_exists($envPath)) {
            file_put_contents($envPath, "DB_HOST=localhost\nDB_NAME=test_db\nDB_USER=root\nDB_PASS=\n");
        }
        EnvLoader::load($envPath);
    }

    protected function setUp(): void
    {
        $this->pdo = Database::getPdo();
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

    public function testFullLifecycle(): void
    {
        // 1. Création et persistance
        $user = new User(
            email: 'test@example.com',
            firstName: 'John',
            lastName: 'Doe',
            username: 'johndoe',
            password: 'password123',
            role: UserRole::USER
        );
        $this->em->persist($user);
        $this->em->flush();
        $this->assertNotNull($user->getId());

        // 2. Mise à jour
        $user->setFirstName('Jane');
        $this->em->persist($user); // Simule update
        $this->em->flush();
        $found = $this->em->find(User::class, $user->getId());
        $this->assertEquals('Jane', $found->getFirstName());

        // 3. Doublon (username/email unique)
        $user2 = new User(
            email: 'test@example.com',
            firstName: 'Other',
            lastName: 'User',
            username: 'johndoe',
            password: 'password456',
            role: UserRole::USER
        );
        try {
            $this->em->persist($user2);
            $this->em->flush();
            $this->fail('Should not allow duplicate email/username');
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }

        // 4. Validation
        $invalid = new User(
            email: '',
            firstName: '',
            lastName: '',
            username: '',
            password: '',
            role: UserRole::USER
        );
        $errors = $this->em->validate($invalid);
        $this->assertIsArray($errors);
        $this->assertNotEmpty($errors);
        $this->expectException(InvalidArgumentException::class);
        $this->em->persist($invalid);

        // 5. Suppression
        $this->em->remove($user);
        $this->em->flush();
        $deleted = $this->em->find(User::class, $user->getId());
        $this->assertNull($deleted);

        // 6. Pagination
        for ($i = 1; $i <= 15; $i++) {
            $u = new User(
                email: "user{$i}@example.com",
                firstName: "First{$i}",
                lastName: "Last{$i}",
                username: "user{$i}",
                password: "pass{$i}",
                role: UserRole::USER
            );
            $this->em->persist($u);
        }
        $this->em->flush();
        $result = $this->em->paginate(User::class, [], 10, 0);
        $this->assertEquals(15, $result['total']);
        $this->assertCount(10, $result['results']);
        $result2 = $this->em->paginate(User::class, [], 10, 10);
        $this->assertCount(5, $result2['results']);

        // 7. Cas limite (find inexistant)
        $notFound = $this->em->find(User::class, 99999);
        $this->assertNull($notFound);
    }
}

