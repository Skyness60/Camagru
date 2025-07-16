<?php
// tests/UserTest.php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use App\Models\User;
use App\Models\UserPermission;

#[CoversClass(User::class)]
final class UserTest extends TestCase
{
    public function testUserIsProperlyConstructed(): void
    {
        $email = 'john.doe@example.com';
        $firstName = 'John';
        $lastName = 'Doe';
        $username = 'johndoe';
        $permission = UserPermission::USER;
        $password = 'password123';

        $user = new User(email: $email, firstName: $firstName, lastName: $lastName, username: $username, password: $password, permission: $permission);

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame($user->id, $user->id);
        $this->assertSame($email, $user->getEmail());
        $this->assertSame($firstName, $user->getFirstName());
        $this->assertSame($lastName, $user->getLastName());
        $this->assertSame($username, $user->getUsername());
        $this->assertSame($permission, $user->getPermission());
        $this->assertInstanceOf(\DateTimeImmutable::class, $user->getCreatedAt());
        $this->assertNull($user->getUpdatedAt());
        $this->assertTrue(password_verify($password, $user->getPassword()));
        $this->assertNotSame($password, $user->getPassword());
    }
}
