<?php
declare(strict_types=1);

namespace App\Notification;

class LogNotificationChannel implements NotificationChannel
{
    public function send(string $message): void
    {
        // write to log
    }
}
