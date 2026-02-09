<?php
declare(strict_types=1);

namespace App\Notification;

use Exception;

/**
 * Factory for creating NotificationChannel instances based on configuration.
 * 
 * This factory reads the notification channel type from configuration
 * and returns the appropriate implementation. This allows swapping
 * notification channels at runtime without changing service code.
 */
class NotificationChannelFactory
{
    /**
     * Create a NotificationChannel instance based on configuration.
     * 
     * @param string $channelType The channel type from config ('log' or 'email')
     * @return NotificationChannel The configured notification channel instance
     * @throws Exception If the channel type is not recognized
     */
    public static function create(string $channelType): NotificationChannel
    {
        return match (strtolower($channelType)) {
            'log' => new LogNotificationChannel(),
            'email' => new EmailNotificationChannel(),
            default => throw new Exception("Unknown notification channel type: {$channelType}. Supported types: 'log', 'email'"),
        };
    }

    /**
     * Create a NotificationChannel instance from configuration file.
     * 
     * Reads the 'notification_channel' value from config/app.php
     * and creates the appropriate channel instance.
     * 
     * @param string $configPath Path to the config file (default: config/app.php)
     * @return NotificationChannel The configured notification channel instance
     * @throws Exception If config file is missing or channel type is invalid
     */
    public static function createFromConfig(string $configPath = ''): NotificationChannel
    {
        if (empty($configPath)) {
            $configPath = __DIR__ . '/../../config/app.php';
        }

        if (!file_exists($configPath)) {
            throw new Exception("Configuration file not found: {$configPath}");
        }

        $config = require $configPath;

        if (!isset($config['notification_channel'])) {
            throw new Exception("Configuration key 'notification_channel' not found in config file");
        }

        return self::create($config['notification_channel']);
    }
}
