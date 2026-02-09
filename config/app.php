<?php
declare(strict_types=1);

/**
 * Application Configuration
 * 
 * This file contains configuration settings that can be changed
 * without modifying service code, enabling runtime behavior changes.
 */

return [
    /**
     * Notification Channel Configuration
     * 
     * Options: 'log' or 'email'
     * - 'log': Uses LogNotificationChannel (writes to data/notifications.log)
     * - 'email': Uses EmailNotificationChannel (writes to data/emails.log)
     * 
     * To swap notification channels, simply change this value.
     * No service code changes required.
     */
    'notification_channel' => 'log',
];
