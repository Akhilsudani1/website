<?php
declare(strict_types=1);

namespace App\Notification;

class EmailNotificationChannel implements NotificationChannel
{
    public function send(string $message): void
    {
        $logFile = __DIR__ . '/../../data/emails.log';
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[{$timestamp}] EMAIL: {$message}\n";
        file_put_contents($logFile, $logEntry, FILE_APPEND);
    }
}
