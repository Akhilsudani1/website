<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Notification\NotificationManager;
use App\Notification\LogNotificationChannel;
use App\Notification\EmailNotificationChannel;

class NotificationManagerTest extends TestCase
{
    public function testLogDriverIsDefault(): void
    {
        $manager = new NotificationManager('log');
        $this->assertInstanceOf(LogNotificationChannel::class, $manager->getDriver());
    }

    public function testEmailDriverCanBeResolved(): void
    {
        $manager = new NotificationManager('email');
        $this->assertInstanceOf(EmailNotificationChannel::class, $manager->getDriver());
    }

    public function testInvalidDriverThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new NotificationManager('invalid_driver');
    }

    public function testCanSwitchDriver(): void
    {
        $manager = new NotificationManager('log');
        $this->assertInstanceOf(LogNotificationChannel::class, $manager->getDriver());

        $emailChannel = new EmailNotificationChannel();
        $manager->setDriver($emailChannel);
        
        $this->assertInstanceOf(EmailNotificationChannel::class, $manager->getDriver());
    }
}
