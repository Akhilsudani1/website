# Part F — Reflection

## Design Decisions and Tradeoffs

### 1. Composition Over Inheritance

**Decision:** Use composition throughout the project (CheckoutService holds references to repositories, services hold references to other services) rather than class hierarchies.

**Where Used:**
- `CheckoutService` contains (`AssetRepositoryInterface`, `CheckoutRepositoryInterface`, `AuthService`, `NotificationChannel`)
- `AssetController` contains (`AssetService`, `CheckoutService`)
- Each service is injected as a dependency

**Why:**
- More flexible and testable than inheritance
- Avoids fragile base class problem
- Easier to swap implementations
- Better follows Composition over Inheritance principle

**Tradeoff:**
- Requires more explicit constructor parameters
- Slightly more verbose than inheritance
- But worth it for flexibility and clarity

---

### 2. Interface-Based Abstraction (Repositories)

**Decision:** Create interfaces (`AssetRepositoryInterface`, `CheckoutRepositoryInterface`, `UserRepositoryInterface`) with file-based implementations.

**Interfaces:**
```php
AssetRepositoryInterface
CheckoutRepositoryInterface
UserRepositoryInterface
```

**Why:**
- Decouples services from storage implementation
- Could easily swap to SQLite, MySQL, or NoSQL without changing service code
- Makes unit testing easier (can mock repositories)
- Follows Dependency Inversion Principle

**Tradeoff:**
- Currently only one implementation (File), so might seem over-engineered
- But preparation for future growth is good practice

---

### 3. Polymorphism via Notification Channels

**Decision:** Create `NotificationChannel` interface with two implementations: `LogNotificationChannel` and `EmailNotificationChannel`.

**Why:**
- Demonstrates the Strategy pattern
- Allows notifications to be sent via different channels without changing checkout logic
- Easy to add new channels (SMS, Slack, Discord) in future
- Follows Open/Closed Principle

**Tradeoff:**
- Email channel is simulated (writes to log) rather than actually sending emails
- In production, would integrate with SMTP or third-party service
- For assessment purposes, simulating is sufficient to show polymorphism

---

### 4. DTO Pattern (Data Transfer Objects)

**Decision:** Create DTOs for request data: `CreateAssetDTO`, `CheckoutAssetDTO`

**Why:**
- Type safety: Properties are public but immutable (used for data transfer only)
- Clear contract between controllers and services
- Easier validation and error handling
- Reduced coupling: Controllers don't pass raw `$_POST` arrays to services

**Tradeoff:**
- Requires creating additional classes
- Slightly more code, but better structure
- Worth it for larger projects

---

### 5. Authorization: Return Asset Only By Owner

**Decision:** Only the user who checked out an asset can return it. This is enforced at two levels:
1. **Service level:** `CheckoutService::returnAsset()` verifies user ID
2. **Controller level:** `AssetController::index()` only shows return button for owner

**Why:**
- Prevents data corruption (wrong user returning wrong asset)
- Matches real-world behavior (you can only return what you checked out)
- Security: Prevent unauthorized returns

**Implementation:**
```php
public function returnAsset(string $assetId): void
{
    // ...
    if ($checkout->getUserId() !== $userId) {
        throw new Exception('You can only return assets you checked out');
    }
    // ...
}
```

**Tradeoff:**
- More complex authorization logic
- Need to track which user checked out each asset
- But essential for data integrity

---

### 6. File-Based Storage (JSON)

**Decision:** Use JSON files for persistence instead of database.

**Why:**
- Meets assessment requirements ("no frameworks")
- Simple to implement and understand
- Easy to inspect data (plain JSON)
- Good for learning/teaching
- Version control friendly

**Tradeoff:**
- Not suitable for production with high traffic
- No built-in concurrency control
- Manual file handling (no ORM)
- Performance: Reading entire file for each query

**For Production:**
Would replace with:
- SQLite (simplicity, no server)
- MySQL/PostgreSQL (scale)
- MongoDB (flexible schema)
- With proper ORM (Doctrine, Eloquent clone)

---

### 7. Dependency Injection via Constructor

**Decision:** All dependencies injected through constructor, not setter methods or Service Locator.

**Why:**
- Explicit: Dependencies are clear from constructor signature
- Immutable: Once set, can't be changed (prevents bugs)
- Testable: Easy to provide mock dependencies for tests
- Follows constructor injection best practice

**Example:**
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

**Tradeoff:**
- Requires managing all dependencies upfront (in `public/index.php`)
- More code in bootstrap
- But prevents runtime errors and circular dependencies

---

### 8. Simple Validation (Validator Class)

**Decision:** Create a minimal `Validator` class with static `required()` method.

```php
public static function required(string $value, string $fieldName): void
{
    if (trim($value) === '') {
        throw new InvalidArgumentException("$fieldName is required");
    }
}
```

**Why:**
- Centralized validation logic
- Reusable across controllers
- Type hints ensure only strings validated
- Throws exceptions for clean error handling

**Tradeoff:**
- Very minimal (only checks required fields)
- Could add more rules: email, length, pattern, etc.
- For real project, would use dedicated validation library

---

## Where Composition Is Used

1. **CheckoutService** ← composes → AssetRepositoryInterface, CheckoutRepositoryInterface, AuthService, NotificationChannel
2. **AssetController** ← composes → AssetService, CheckoutService
3. **CheckoutController** ← composes → CheckoutService
4. **AuthController** ← composes → AuthService
5. **AssetService** ← composes → AssetRepositoryInterface, AuthService

Each service/controller receives its collaborators, enabling loose coupling and easy testing.

---

## Where Interfaces Are Used

1. **AssetRepositoryInterface** - Abstracts asset storage
2. **CheckoutRepositoryInterface** - Abstracts checkout storage
3. **UserRepositoryInterface** - Abstracts user storage
4. **NotificationChannel** - Abstracts notification method

Each interface allows:
- Multiple implementations (currently File-based)
- Easy testing with mocks
- Future swapping to other technologies

---

## If This Became a Real Internal Tool

### Immediate Improvements

1. **Database:** Replace JSON files with SQLite/MySQL and proper ORM
2. **Real Email:** Integrate actual SMTP or service (SendGrid, AWS SES)
3. **Web Framework:** Use Laravel or Symfony for routing, middleware, security
4. **Form Validation:** Dedicated validation library (Valitron, Respect\Validation)
5. **Error Handling:** Graceful error pages, logging, monitoring (Sentry)
6. **Authentication:** Use industry-standard (OAuth, JWT, or framework auth)
7. **CSS/JS:** Professional UI framework (Bootstrap, Tailwind) instead of inline HTML
8. **Testing:** Pest or PHPUnit tests for critical features

### Mid-term Enhancements

1. **Asset Reservations:** Allow booking assets in advance
2. **Damage Reporting:** Track asset condition during returns
3. **Email Notifications:** Actual email reminders before deadline
4. **Reports:** Export checkout history and asset usage analytics
5. **Admin Dashboard:** Statistics and overview
6. **Search & Filter:** Advanced asset discovery
7. **Multi-language:** i18n support for international teams
8. **API:** REST API for mobile/third-party integrations

### Long-term Scaling

1. **Caching:** Redis for session and frequently accessed assets
2. **Message Queue:** Background job processing for notifications
3. **Audit Logs:** Immutable log of all actions for compliance
4. **File Storage:** For asset photos/documents (S3, Backblaze)
5. **Monitoring:** Uptime monitoring, performance metrics
6. **Microservices:** Split into Notification Service, Asset Service, etc. (if needed)
7. **Container:** Docker for consistent deployment

---

## SOLID Principles in This Project

| Principle | Application |
|-----------|-------------|
| **S**ingle Responsibility | Each class has one reason to change (Services handle logic, Repositories handle storage, Controllers handle HTTP) |
| **O**pen/Closed | NotificationChannel interface allows extending with new implementations without modifying existing code |
| **L**iskov Substitution | Any NotificationChannel implementation can replace another |
| **I**nterface Segregation | Interfaces are focused (NotificationChannel only defines `send()`) |
| **D**ependency Inversion | Services depend on abstractions (interfaces), not concrete implementations |

---

## Key Learnings

1. **Interfaces are powerful:** The `NotificationChannel` interface demonstrates how polymorphism enables flexible design
2. **Composition > Inheritance:** Injecting dependencies is more flexible than extending classes
3. **Authorization matters:** User context must be verified at service level, not just UI level
4. **Data structures matter:** DTOs make contracts explicit and type-safe
5. **Separation of concerns:** Controllers, Services, Repositories each have clear responsibilities
6. **Testing mindset:** Architecture designed for testability (dependency injection, interfaces)

---

## Conclusion

This project demonstrates OOP principles and clean architecture in a small, understandable codebase. While using plain PHP and JSON storage, it follows industry best practices that would scale to larger systems. The polymorphic notification system shows how interfaces enable flexible design without tight coupling.

The key insight: **Good architecture is about making changes easy, not making the initial code perfect.**
