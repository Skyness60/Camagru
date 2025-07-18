<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Core\ORM\EntityManager;
use App\Model\Entity\User;
use PHPUnit\Framework\Attributes\CoversClass;
use App\Model\Entity\UserRole;
use PDO;

#[CoversClass(EntityManager::class)]
final class EntityManagerCompleteTest extends TestCase
{
    private EntityManager $em;

    protected function setUp(): void
    {
        // Utiliser SQLite en mémoire pour les tests
        $pdo = new PDO('sqlite::memory:');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Créer la table avec syntaxe SQLite
        $this->createSQLiteTable($pdo);
        
        $this->em = new EntityManager($pdo);
    }

    private function createSQLiteTable(PDO $pdo): void
    {
        $sql = "
            CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                email TEXT NOT NULL,
                firstName TEXT NOT NULL,
                lastName TEXT NOT NULL,
                username TEXT NOT NULL,
                password TEXT NOT NULL,
                role TEXT NOT NULL,
                createdAt TEXT NOT NULL,
                updatedAt TEXT
            )
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