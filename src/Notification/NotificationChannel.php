<?php
declare(strict_types=1);

namespace App\Notification;

interface NotificationChannel
{
    public function send(string $message): void;
}
