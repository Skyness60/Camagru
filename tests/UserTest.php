<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Model\Entity\User;
use PHPUnit\Framework\Attributes\CoversClass;
use App\Model\Entity\UserRole;

#[CoversClass(User::class)]
final class UserTest extends TestCase
{
    public function testUserIsProperlyConstructed(): void
    {
        $user = new User(
            email: 'test@example.com',
            firstName: 'John',
            lastName: 'Doe', 
            username: 'johndoe',
            password: 'password123',
            role: UserRole::USER
        );

        $this->assertNull($user->getId());
        $this->assertEquals('test@example.com', $user->getEmail());
        $this->assertEquals('John', $user->getFirstName());
        $this->assertEquals('Doe', $user->getLastName());
        $this->assertEquals('johndoe', $user->getUsername());
        $this->assertEquals(UserRole::USER, $user->getRole());
        $this->assertInstanceOf(\DateTimeImmutable::class, $user->getCreatedAt());
    }
}