<?php
namespace App\Service;

class LoginRateLimiter
{
    private int $maxAttempts;
    private int $delay;

    public function __construct(int $maxAttempts = 5, int $delay = 2000000)
    {
        $this->maxAttempts = $maxAttempts;
        $this->delay = $delay; // microseconds
    }

    public function tooManyAttempts(): bool
    {
        return ($_SESSION['login_attempts'] ?? 0) >= $this->maxAttempts;
    }

    public function incrementAttempts(): void
    {
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 0;
        }
        $_SESSION['login_attempts']++;
    }

    public function resetAttempts(): void
    {
        unset($_SESSION['login_attempts']);
    }

    public function delay(): void
    {
        usleep($this->delay);
    }
}
