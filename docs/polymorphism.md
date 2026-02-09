# Part E — Polymorphism Challenge Implementation

## Overview
This project implements **Option 1: Notification Channels** as the polymorphism challenge. The system uses an interface-based design to support multiple notification channels that can be swapped at runtime without changing core business logic.

---

## Design Pattern: Strategy Pattern via Polymorphism

### Interface Definition
```php
namespace App\Notification;

interface NotificationChannel
{
    public function send(string $message): void;
}
```

The `NotificationChannel` interface defines a contract that all notification implementations must follow. Any class implementing this interface can be used interchangeably.

---

## Implementations

### 1. LogNotificationChannel
**File:** `src/Notification/LogNotificationChannel.php`

- Writes notifications to a log file (`data/notifications.log`)
- Useful for audit trails and development
- Timestamped entries for tracking

```php
public function send(string $message): void
{
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[{$timestamp}] {$message}\n";
    file_put_contents($this->logFile, $logEntry, FILE_APPEND);
}
```

**Use case:** Development, testing, audit logs

### 2. EmailNotificationChannel
**File:** `src/Notification/EmailNotificationChannel.php`

- Simulates email sending to `data/emails.log`
- In production, would integrate with SMTP or a service like PHPMailer, SendGrid, or AWS SES
- Allows users to receive notifications about checkouts

```php
public function send(string $message): void
{
    $logFile = __DIR__ . '/../../data/emails.log';
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[{$timestamp}] EMAIL: {$message}\n";
    file_put_contents($logFile, $logEntry, FILE_APPEND);
}
```

**Use case:** Production email notifications to users

---

## Integration with CheckoutService

### Constructor Injection
The `CheckoutService` accepts a `NotificationChannel` as a dependency:

```php
public function __construct(
    private AssetRepositoryInterface $assetRepo,
    private CheckoutRepositoryInterface $checkoutRepo,
    private AuthService $auth,
    ?NotificationChannel $notificationChannel = null
) {
    $this->notificationChannel = $notificationChannel ?? new LogNotificationChannel();
}
```

**Key benefits:**
- Dependency inversion: Service doesn't create its own dependencies
- Default to `LogNotificationChannel` if none provided
- Can be swapped for any `NotificationChannel` implementation

### Usage in Business Logic

Notifications are sent during checkout and return:

```php
public function checkout(CheckoutAssetDTO $dto): void
{
    // ... validation and asset update ...
    
    // Send notification
    $message = "Checkout confirmed: User {$userEmail} checked out asset {$asset->getName()} "
             . "(ID: {$asset->getId()})";
    $this->notificationChannel->send($message);
}

public function returnAsset(string $assetId): void
{
    // ... validation and asset return ...
    
    // Send notification
    $message = "Asset returned: User {$userEmail} returned asset {$asset->getName()} "
             . "(ID: {$asset->getId()})";
    $this->notificationChannel->send($message);
}
```

---

## Configuration

### Configuration File: `config/app.php`

The notification channel is configured via a configuration file, allowing runtime swapping without changing service code:

```php
return [
    'notification_channel' => 'log',  // Options: 'log' or 'email'
];
```

### Factory Pattern: `NotificationChannelFactory`

A factory class reads the configuration and creates the appropriate channel instance:

```php
namespace App\Notification;

class NotificationChannelFactory
{
    public static function createFromConfig(): NotificationChannel
    {
        $config = require __DIR__ . '/../../config/app.php';
        return match ($config['notification_channel']) {
            'log' => new LogNotificationChannel(),
            'email' => new EmailNotificationChannel(),
            default => throw new Exception("Unknown channel type"),
        };
    }
}
```

### Usage in `public/index.php`

The factory is used to create the notification channel from configuration:

```php
use App\Notification\NotificationChannelFactory;

// Create notification channel from configuration
// To swap channels, edit config/app.php and change 'notification_channel' value
$notificationChannel = NotificationChannelFactory::createFromConfig();

$checkoutService = new CheckoutService(
    $assetRepo, 
    $checkoutRepo, 
    $authService, 
    $notificationChannel
);
```

### Swapping Channels

**To change notification channels, simply edit `config/app.php`:**

1. **For Log Channel (Development):**
   ```php
   'notification_channel' => 'log',
   ```

2. **For Email Channel (Production):**
   ```php
   'notification_channel' => 'email',
   ```

**No service code changes required!** The factory handles the instantiation based on configuration.

---

## How Polymorphism Works Here

### Substitutability (Liskov Substitution Principle)
Both `EmailNotificationChannel` and `LogNotificationChannel` implement `NotificationChannel`, so they are interchangeable:

```php
// Both work identically from CheckoutService's perspective
$channel1 = new EmailNotificationChannel();
$channel2 = new LogNotificationChannel();

// Both can be passed to CheckoutService
$service1 = new CheckoutService($assetRepo, $checkoutRepo, $auth, $channel1);
$service2 = new CheckoutService($assetRepo, $checkoutRepo, $auth, $channel2);
```

### Runtime Behavior Variation
- **With LogNotificationChannel:** Notifications go to `data/notifications.log` (silent, audit-friendly)
- **With EmailNotificationChannel:** Notifications go to `data/emails.log` (simulation of email sending)

The **same `CheckoutService` code** behaves differently based on which implementation is injected.

---

## Extending With New Channels

To add a new notification channel (e.g., SMS):

1. Create a new class implementing `NotificationChannel`:
```php
<?php
declare(strict_types=1);

namespace App\Notification;

class SmsNotificationChannel implements NotificationChannel
{
    public function send(string $message): void
    {
        // Integration with SMS provider (Twilio, etc.)
    }
}
```

2. Add it to the factory's `create()` method:
```php
public static function create(string $channelType): NotificationChannel
{
    return match (strtolower($channelType)) {
        'log' => new LogNotificationChannel(),
        'email' => new EmailNotificationChannel(),
        'sms' => new SmsNotificationChannel(),  // Add new channel here
        default => throw new Exception("Unknown notification channel type"),
    };
}
```

3. Update `config/app.php`:
```php
'notification_channel' => 'sms',
```

**No service code changes needed!** This demonstrates the true power of polymorphism and the Open/Closed Principle.

---

## Benefits of This Design

| Benefit | Description |
|---------|-------------|
| **Decoupling** | CheckoutService doesn't depend on specific notification implementations |
| **Testability** | Easy to create mock channels for unit tests |
| **Extensibility** | New channels can be added without modifying existing code |
| **Flexibility** | Switch channels at runtime via configuration file without code changes |
| **Configuration-Driven** | Channel selection is externalized to config file, enabling easy environment-specific settings |
| **SRP** | Each channel class has one responsibility: send notifications via its method |
| **DRY** | Notification logic is centralized, avoids duplication |

---

## Testing

To verify polymorphism works:

1. **With LogNotificationChannel:**
   - Check `data/notifications.log` after checkout/return
   - Entries should have timestamps and asset details

2. **With EmailNotificationChannel:**
   - Check `data/emails.log` after checkout/return
   - Entries should be marked with `EMAIL:` prefix

Both should log the same information, but via different channels.

---

## Conclusion

This implementation demonstrates **polymorphism through interface-based design**, allowing the system to support multiple notification strategies without violating the Open/Closed Principle or Liskov Substitution Principle. The `NotificationChannel` interface is the abstraction that enables runtime behavior variation.
