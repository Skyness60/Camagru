<?php
// tests/EnvLoaderTest.php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Config\EnvLoader;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(EnvLoader::class)]
final class EnvLoaderTest extends TestCase
{
    public function testEnvLoaderLoadsRealEnv(): void
    {
        $envPath = __DIR__ . '/../.env';
        EnvLoader::load($envPath);
        $this->assertNotEmpty($_ENV['MYSQL_USER'] ?? null, 'MYSQL_USER should be set');
        $this->assertNotEmpty($_ENV['MYSQL_PASSWORD'] ?? null, 'MYSQL_PASSWORD should be set');
        $this->assertNotEmpty($_ENV['MYSQL_DATABASE'] ?? null, 'MYSQL_DATABASE should be set');
    }
}