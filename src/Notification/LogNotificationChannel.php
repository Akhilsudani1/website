<?php
declare(strict_types=1);

namespace App\Notification;

class LogNotificationChannel implements NotificationChannel
{
    private string $logFile;

    public function __construct(string $logFile = '')
    {
        $this->logFile = $logFile ?: __DIR__ . '/../../data/notifications.log';
    }

    public function send(string $message): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[{$timestamp}] {$message}\n";
        file_put_contents($this->logFile, $logEntry, FILE_APPEND);
    }
}
