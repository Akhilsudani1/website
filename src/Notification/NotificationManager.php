<?php
declare(strict_types=1);

namespace App\Notification;

class NotificationManager
{
    private NotificationChannel $channel;

    public function __construct(string $driver = 'log')
    {
        $this->channel = $this->resolveDriver($driver);
    }

    private function resolveDriver(string $driver): NotificationChannel
    {
        return match($driver) {
            'log' => new LogNotificationChannel(),
            'email' => new EmailNotificationChannel(),
            default => throw new \InvalidArgumentException("Unknown notification driver: {$driver}")
        };
    }

    public function send(string $message): void
    {
        $this->channel->send($message);
    }

    public function setDriver(NotificationChannel $channel): void
    {
        $this->channel = $channel;
    }

    public function getDriver(): NotificationChannel
    {
        return $this->channel;
    }
}
