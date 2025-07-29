<?php
namespace App\Service;

class AuditLogger
{
    private string $logFile;

    public function __construct(string $logFile = '/tmp/audit.log')
    {
        $this->logFile = $logFile;
    }

    public function log(string $action, string $username = '-', string $ip = '-'): void
    {
        $date = date('c');
        $line = "$date $action: $username IP: $ip\n";
        error_log($line, 3, $this->logFile);
    }
}
