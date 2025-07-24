<?php
namespace App\Config;

use PDO;

final class Database
{
    private static ?PDO $pdo = null;

    public static function getPdo(): PDO
    {
        if (self::$pdo === null) {
            self::$pdo = new PDO(
                sprintf(
                    "mysql:host=%s;dbname=%s;charset=utf8mb4",
                    $_ENV['MYSQL_HOST'],
                    $_ENV['MYSQL_DATABASE']
                ),
                $_ENV['MYSQL_USER'],
                $_ENV['MYSQL_PASSWORD'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        }
        return self::$pdo;
    }
}
