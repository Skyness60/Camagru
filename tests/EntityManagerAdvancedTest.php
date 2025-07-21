<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Core\ORM\EntityManager;
use App\Model\Entity\User;
use App\Model\Entity\UserRole;
use App\Config\EnvLoader;

final class EntityManagerAdvancedTest extends TestCase
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
        $this->pdo = new \PDO($dsn, $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASSWORD']);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
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

    public function testPaginateReturnsCorrectResultsAndTotal(): void
    {
        // Insert 15 users
        for ($i = 1; $i <= 15; $i++) {
            $user = new User(
                email: "user{$i}@example.com",
                firstName: "First{$i}",
                lastName: "Last{$i}",
                username: "user{$i}",
                password: "pass{$i}",
                role: UserRole::USER
            );
            $this->em->persist($user);
        }
        $this->em->flush();

        $result = $this->em->paginate(User::class, [], 10, 0);
        $this->assertEquals(15, $result['total']);
        $this->assertCount(10, $result['results']);

        $result2 = $this->em->paginate(User::class, [], 10, 10);
        $this->assertCount(5, $result2['results']);
    }

    public function testPaginateOutOfBoundsReturnsEmpty(): void
    {
        $result = $this->em->paginate(User::class, [], 10, 100);
        $this->assertEquals(0, count($result['results']));
    }

    public function testValidateReturnsErrorsForInvalidEntity(): void
    {
        $user = new User(
            email: '', // NotNull, Length
            firstName: '',
            lastName: '',
            username: '',
            password: '',
            role: UserRole::USER
        );
        $errors = $this->em->validate($user);
        $this->assertIsArray($errors);
        $this->assertNotEmpty($errors);
    }

    public function testPersistThrowsOnInvalidEntity(): void
    {
        $user = new User(
            email: '',
            firstName: '',
            lastName: '',
            username: '',
            password: '',
            role: UserRole::USER
        );
        $this->expectException(InvalidArgumentException::class);
        $this->em->persist($user);
    }

    // Pour la gestion des relations, il faudrait ajouter une entité liée (ex: Post, Comment, etc.)
    // et tester la récupération via hydrate. À ajouter si tu as des entités liées.
}
