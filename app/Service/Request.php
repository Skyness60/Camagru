<?php
namespace App\Service;

class Request
{
    public function isMethod(string $method): bool
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? '') === strtoupper($method);
    }

    public function input(): array
    {
        return $_POST;
    }
}
