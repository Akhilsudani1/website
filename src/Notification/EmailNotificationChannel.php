<?php
declare(strict_types=1);

namespace App\Notification;

class EmailNotificationChannel implements NotificationChannel
{
    public function send(string $message): void
    {
        // Simulated email sending (in production, use mail() or a library like PHPMailer)
        // For testing purposes, we'll write to a log file instead of actually sending emails
        $logFile = __DIR__ . '/../../data/emails.log';
        
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[{$timestamp}] EMAIL: {$message}\n";
        
        file_put_contents($logFile, $logEntry, FILE_APPEND);
    }
}
