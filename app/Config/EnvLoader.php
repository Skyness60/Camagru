<?php
// app/Config/EnvLoader.php

namespace App\Config;

final class EnvLoader
{
    public static function load(string $path = __DIR__ . '/../../.env'): void
    {
        if (!file_exists($path)) {
            throw new \RuntimeException(".env file not found at $path");
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            [$name, $value] = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if (!array_key_exists($name, $_ENV)) {
                $_ENV[$name] = $value;
                putenv("$name=$value");
            }
        }
    }
}
