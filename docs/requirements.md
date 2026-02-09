# Requirements: Revatics Asset Checkout System

## Part A: Functional Requirements (FR)

### FR-01: User Authentication
**As a** user  
**I want to** log in with my email and password  
**So that** I can access the system based on my role

- User provides email and password
- System verifies credentials using password_verify()
- If valid, session is created with user data
- If invalid, user is shown error message

### FR-02: User Registration
**As a** new employee  
**I want to** self-register with email and password  
**So that** I can access the system without admin intervention

- User provides email and password
- Email must be valid format and unique
- Password must be at least 6 characters
- Password is hashed using password_hash(PASSWORD_BCRYPT)
- User is created with 'employee' role by default

### FR-03: User Logout
**As a** logged-in user  
**I want to** log out  
**So that** my session is terminated

- User clicks logout
- Session is destroyed
- User is redirected to login page

### FR-04: Asset Creation (Admin Only)
**As an** admin  
**I want to** create new assets with name and category  
**So that** the inventory can be populated

- Admin provides asset name and category
- System generates unique asset ID
- Asset status defaults to 'available'
- Asset is persisted to storage

### FR-05: Asset List View
**As a** logged-in user  
**I want to** view all available and checked-out assets  
**So that** I can see what assets exist in the system

- User sees list of all assets
- Each asset shows name, category, and current status
- Users can click 'View' to see asset details

### FR-06: Asset Details View
**As a** logged-in user  
**I want to** view full details of a specific asset  
**So that** I know its condition and current checkout status

- User can view asset name, ID, category, and status
- If available, user can checkout the asset
- If checked out by user, user can return it
- Admin sees retire option if asset not retired

### FR-07: Asset Checkout
**As a** employee  
**I want to** check out an available asset  
**So that** I can use it for my work

- System verifies asset exists and is available
- System prevents checkout if retired or already checked out
- Checkout record is created with current timestamp
- Asset status changes to 'checked_out'
- Notification is sent (log or email)

### FR-08: Asset Return
**As an** employee  
**I want to** return a checked-out asset  
**So that** others can use it

- System verifies asset was checked out by current user
- System prevents return if not checked out
- Checkout record is marked as returned with timestamp
- Asset status changes back to 'available'
- Notification is sent

### FR-09: Asset Retirement
**As an** admin  
**I want to** retire an asset  
**So that** it can no longer be checked out

- Admin navigates to asset page
- Admin clicks 'Retire Asset' button
- Asset status changes to 'retired'
- Future checkout attempts are blocked

### FR-10: Global Checkout History
**As a** logged-in user  
**I want to** view the global checkout history across all assets  
**So that** I can see all checkout and return events

- User views history page
- All checkout/return events are listed
- Each entry shows asset ID, user, checkout date, return date (or "Not returned")

### FR-11: Per-Asset Checkout History
**As a** logged-in user  
**I want to** view checkout history for a specific asset  
**So that** I can see how often it has been used

- User navigates to asset page
- User clicks 'View full history'
- Only checkout/return events for that asset are shown

### FR-12: Checkout Notifications
**As a** system  
**I want to** send notifications on checkout/return  
**So that** operations are logged and can be verified

- On checkout: notification sent with user, asset, timestamp
- On return: notification sent with user, asset, timestamp
- Notifications can use log file or email (swappable)

---

## Part B: Non-Functional Requirements (FURPS+)

### F — Functionality (Security & Compliance)

**NFR-01: Authentication Security**
- Passwords MUST be hashed using `password_hash(\, PASSWORD_BCRYPT)`
- Passwords MUST be verified using `password_verify()`
- Session regeneration MUST occur after successful login
- No passwords stored in plain text

**NFR-02: Authorization (Role-Based Access)**
- Only 'admin' users can create/retire assets or export data
- Only 'employee' users can checkout/return assets
- Unauthorized access attempts must return HTTP 403 error
- View operations accessible to all authenticated users

**NFR-03: Data Protection**
- All user-controlled output MUST be escaped with `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`
- File writes MUST use `LOCK_EX` flag for concurrent access safety
- Sensitive data (passwords, emails) must not appear in logs

---

### U — Usability

**NFR-04: Interface Responsiveness**
- Asset list must render in 300ms for 100 assets
- Asset detail page must load in 200ms
- Checkout/return operations must complete in 100ms
- All pages must be responsive on mobile and desktop

**NFR-05: User Feedback**
- Every action (login, checkout, return) displays success or error message
- Error messages are user-friendly, not technical
- Form validation provides immediate feedback
- Redirects used only for flow changes, not error reporting

**NFR-06: Navigation & Clarity**
- All pages include navigation back to asset list
- Buttons and links are clearly labeled
- Forms are simple and require minimal fields
- Status indicators are color-coded (green=available, red=checked_out, gray=retired)

---

### R — Reliability & Robustness

**NFR-07: Data Consistency**
- CONSTRAINT: Only ONE user can hold a checkout for an asset at any time
- Concurrent checkout attempts must be serialized (LOCK_EX)
- If operation fails, no state change occurs

**NFR-08: Fault Handling**
- Failed checkout shows specific reason: "Asset not available", "Already checked out", "Retired", etc.
- Failed return shows reason: "Not checked out", "Not your checkout", etc.
- Failed login shows generic message: "Invalid email or password" (No user enumeration)
- All exceptions caught and user-friendly messages returned

**NFR-09: Data Integrity**
- Assets cannot be deleted (only retired)
- Checkout history is immutable (append-only log)
- User data is validated before persistence

---

### P — Performance

**NFR-10: Query/Retrieval Speed**
- Asset list retrieval: 50ms for reasonable dataset
- Asset detail retrieval: 50ms
- Checkout history retrieval: 100ms
- No N+1 query problems

**NFR-11: Storage Efficiency**
- JSON file format acceptable for current load
- File size 10MB before migration to database needed
- No redundant data storage

**NFR-12: Scalability Path**
- Repository interfaces allow swapping to MySQL/SQLite without code changes
- Service layer independent of storage implementation
- Horizontal scaling possible via stateless design (except sessions)

---

### S — Supportability & Maintainability

**NFR-13: Code Organization**
- 5-layer architecture: Router  Controllers  Services  Entities  Repositories
- No class exceeds 200 lines
- No method exceeds 30 lines
- Single Responsibility Principle applied strictly

**NFR-14: Code Quality Standards**
- `declare(strict_types=1)` on all files
- Full type hints on all parameters and return types
- Namespaces follow PSR-4 standard
- Composer autoloading used exclusively
- Zero commented-out code
- Zero debug statements (console log, var_dump, die)

**NFR-15: Extensibility**
- New notification channels added by implementing `NotificationChannel` interface
- New storage implementations added by implementing Repository interfaces
- Configuration externalized for notification driver selection
- Service layer depends on abstractions, not concrete classes

**NFR-16: Testing & Verifiability**
- All business logic in services is unit-testable
- Repositories are mockable via interfaces
- No static dependencies or singletons (except container in bootstrap)
- Test suite covers: Entity behavior, Validation, Notifications, Container

**NFR-17: Documentation**
- README includes setup and running instructions
- Inline code is self-documenting (clear naming)
- Architecture documented in PROJECT_STRUCTURE.md
- Design patterns explained in POLYMORPHISM.md

---

### + — Constraints (Design & Technical)

**NFR-18: Technical Stack**
- PHP 8.0+ required (for type hints, named arguments, enums)
- No external frameworks allowed (Laravel, Symfony, etc. forbidden)
- Composer for autoloading (PSR-4 required)
- JSON files for storage (no database required)
- Plain HTML for UI (no frontend framework required)

**NFR-19: Code Submission Requirements**
- No `die()` statements in final code
- No `var_dump()`, `print_r()`, `debug_backtrace()` in final code
- All files must pass PHP lint check
- Git history with meaningful commits required
- README with setup instructions required

**NFR-20: Security & Deployment**
- No credentials hardcoded in source
- Passwords hashed before storage
- Session-based auth (no JWT for this project)
- HTTPS recommended for production
- CSRF protection via session regeneration

---

## Summary

**Total Functional Requirements: 12**
**Total Non-Functional Requirements: 20**
**Total: 32 requirements**

All requirements are:
✓ Measurable (specific metrics provided)
✓ Testable (clear acceptance criteria)
✓ Achievable (within scope of plain PHP)
✓ Relevant (aligned with OOP assessment goals)
✓ Time-bound (performance targets specified)
