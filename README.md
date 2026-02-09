# Revatics Asset Checkout System

A small, elegant OOP-based asset checkout management system built with plain PHP. Demonstrates SOLID principles, design patterns, and clean architecture.

---

## 📋 Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Running the Application](#running-the-application)
- [Project Structure](#project-structure)
- [Architecture Overview](#architecture-overview)
- [Key OOP Concepts](#key-oop-concepts)
- [Part E: Polymorphism](#part-e-polymorphism)

---

## ✨ Features

### Core Functionality
- **User Authentication:** Email/password login with role-based access control
- **Asset Management:** Create, view, and manage shared assets (Admin only)
- **Asset Checkout:** Request and checkout available assets
- **Asset Return:** Return checked-out assets (only by user who checked them out)
- **Checkout History:** Track asset usage history
- **Role-Based Access:** Admin and Employee roles with different permissions

### OOP & Design Principles
- ✅ **Encapsulation:** Private properties with public methods
- ✅ **Interfaces & Polymorphism:** NotificationChannel with 2 implementations
- ✅ **Composition:** Services composed of repositories and sub-services
- ✅ **DTOs:** Type-safe data transfer objects for request/response
- ✅ **Dependency Injection:** Constructor-based dependency injection
- ✅ **Separation of Concerns:** Controllers, Services, Repositories clearly separated
- ✅ **Authorization:** Only checkout owners can return their assets

---

## 📋 Requirements

- **PHP:** 8.0 or higher
- **Composer:** For autoloading
- **No frameworks:** Plain PHP with PSR-4 autoloading

---

## 🚀 Installation

### Step 1: Clone or navigate to the project
```bash
cd revatics-oop-assessment-akhil
```

### Step 2: Install dependencies with Composer
```bash
composer install
```

This generates the `vendor/autoload.php` for PSR-4 autoloading.

### Step 3: Create data directory and files (if not exists)
```bash
mkdir -p data
```

### Step 4: Initialize data files

Create `data/users.json` with test users:
```json
[
  {
    "id": "u_001",
    "email": "admin@test.com",
    "password": "$2y$10$eIZR8TJzl7G3jvzFl6Q0yOPHDJE/D9G/KqS.3Lmt1FN0j5f5Z3i0G",
    "role": "admin"
  },
  {
    "id": "u_002",
    "email": "user@test.com",
    "password": "$2y$10$eIZR8TJzl7G3jvzFl6Q0yOPHDJE/D9G/KqS.3Lmt1FN0j5f5Z3i0G",
    "role": "employee"
  }
]
```

Both users have password: `password123`

Create `data/assets.json`:
```json
[]
```

Create `data/checkouts.json`:
```json
[]
```

### Step 5: Verify files exist
- `data/users.json` ✓
- `data/assets.json` ✓
- `data/checkouts.json` ✓

---

## ▶️ Running the Application

### Start the PHP Built-in Server
```bash
php -S localhost:8000 -t public
```

### Access the Application
Open your browser and go to:
```
http://localhost:8000
```

### Test Credentials

**Admin Account:**
- Email: `admin@test.com`
- Password: `password123`

**Employee Account:**
- Email: `user@test.com`
- Password: `password123`

---

## 📁 Project Structure

```
revatics-oop-assessment-akhil/
├── public/
│   ├── index.php              # Application entry point & routing
├── src/
│   ├── Domain/
│   │   ├── Entity/            # Business entities (User, Asset, Checkout)
│   │   ├── Service/           # Business logic (AssetService, CheckoutService, AuthService)
│   │   ├── DTO/               # Data Transfer Objects
│   │   └── Validation/        # Validation logic
│   ├── Http/
│   │   ├── Router.php         # (Placeholder for future routing)
│   │   └── Controller/        # HTTP controllers (AssetController, etc.)
│   ├── Notification/          # POLYMORPHISM: Notification channels
│   │   ├── NotificationChannel.php      # Interface
│   │   ├── EmailNotificationChannel.php # Implementation 1
│   │   └── LogNotificationChannel.php   # Implementation 2
│   ├── Storage/               # Data persistence layer
│   │   ├── *RepositoryInterface.php  # Interfaces
│   │   └── File/              # File-based implementation
│   └── Support/               # Helper classes
├── data/
│   ├── users.json             # User data
│   ├── assets.json            # Asset inventory
│   ├── checkouts.json         # Checkout history
│   ├── notifications.log      # (Generated) Notification logs
│   └── emails.log             # (Generated) Email logs
├── docs/
│   ├── requirements.md        # Functional & non-functional requirements
│   ├── use-cases.md           # Use cases with flows
│   ├── user-stories.md        # User stories with acceptance criteria
│   ├── crc-cards.md           # Class responsibility collaboration cards
│   ├── c2.md                  # UML class diagram (text based)
│   ├── polymorphism.md        # Part E: Polymorphism explanation
│   └── reflection.md          # Design reflection
├── views/                     # (Placeholder for future views)
├── vendor/                    # Composer dependencies (gitignored)
├── composer.json              # Project metadata & PSR-4 autoload
├── README.md                  # This file
└── .gitignore                 # Git ignore configuration
```

---

## 🏗️ Architecture Overview

### Layered Architecture

```
┌─────────────────────────────────────┐
│        HTTP Layer                   │
│  Controllers (form handling)        │
└─────────────────┬───────────────────┘
                  ↓
┌─────────────────────────────────────┐
│   Domain/Business Logic Layer       │
│  Services (CheckoutService, etc.)   │
│  Entities (User, Asset, Checkout)   │
└─────────────────┬───────────────────┘
                  ↓
┌─────────────────────────────────────┐
│     Storage/Persistence Layer       │
│  Repositories (File-based)          │
│  Data: users.json, assets.json      │
└─────────────────────────────────────┘
```

### Key Components

1. **Entities:** Represent domain objects (`User`, `Asset`, `Checkout`)
2. **Services:** Enforce business rules (`AssetService`, `CheckoutService`, `AuthService`)
3. **Repositories:** Abstract data access (`AssetRepositoryInterface`, implementations)
4. **Controllers:** Handle HTTP requests and delegate to services
5. **DTOs:** Type-safe data containers for inter-service communication
6. **Notifications:** Polymorphic notification channels

---

## 🎯 Key OOP Concepts

### 1. Encapsulation
- **Private properties:** `private string $id`, `private string $status`
- **Public methods:** `getId()`, `getStatus()`, behavior through methods
- **Example:** Asset status changes only through `markCheckedOut()` and `markAvailable()`

### 2. Interfaces & Polymorphism
- **Interface:** `NotificationChannel` with contract `send(string $message): void`
- **Implementation 1:** `LogNotificationChannel` writes to log files
- **Implementation 2:** `EmailNotificationChannel` simulates email sending
- **Benefit:** Swap implementations without changing `CheckoutService` code

### 3. Composition
- `CheckoutService` **composes** `AssetRepositoryInterface`, `CheckoutRepositoryInterface`, `AuthService`, `NotificationChannel`
- `AssetController` **composes** `AssetService` and `CheckoutService`
- Preferred over inheritance for flexible design

### 4. Dependency Injection
- All dependencies injected via **constructor**
- No hard dependencies on concrete classes
- Easy to test with mock implementations

### 5. Abstraction via Interfaces
- `AssetRepositoryInterface` abstracts data storage (could be File, SQL, etc.)
- Controllers depend on abstractions, not concrete implementations
- Enables swapping implementations at runtime

### 6. Authorization & Business Rules
- Only the user who checked out an asset can return it
- `canUserReturnAsset()` enforces this rule
- `returnAsset()` validates user ID matches checkout user ID

---

## Part E: Polymorphism

See [docs/polymorphism.md](docs/polymorphism.md) for detailed explanation.

### Quick Overview
- **Interface:** `NotificationChannel`
- **Implementation 1:** `LogNotificationChannel` (audit logs)
- **Implementation 2:** `EmailNotificationChannel` (email simulation)
- **Usage:** Injected into `CheckoutService` via constructor
- **Behavior:** Triggered on checkout and return actions
- **Switching:** Change one line in `public/index.php`

To use Email notifications instead of logs:
```php
// In public/index.php, change:
$notificationChannel = new LogNotificationChannel();
// To:
$notificationChannel = new EmailNotificationChannel();
```

---

## 🔐 Security Features

1. **Password Hashing:** Uses `password_hash()` with default algorithm (bcrypt)
2. **Password Verification:** Uses `password_verify()` for secure comparison
3. **Role-Based Access Control:** Admin vs Employee roles enforced
4. **Session Management:** Stores user info in `$_SESSION`
5. **Authorization Checks:** Assets can only be returned by their checkout owner

---

## 📊 Data Persistence

### File-Based Storage
- Uses JSON files instead of database for simplicity
- Located in `data/` directory
- Easy to version control and inspect

### Files
- `users.json` - User accounts and credentials
- `assets.json` - Asset inventory
- `checkouts.json` - Checkout history with timestamps

---

## 🧪 How to Test

### 1. Create Assets (Admin)
1. Login as admin (`admin@test.com` / `password123`)
2. Create a new asset with name and category
3. Asset appears in list as "available"

### 2. Checkout Asset (Employee)
1. Login as employee (`user@test.com` / `password123`)
2. Click "Checkout" on an available asset
3. Asset status changes to "checked out"
4. When logged in, employee sees "Return" button for their checkout

### 3. Return Asset
1. Still logged in as employee
2. Click "Return" button on checked-out asset
3. Asset becomes available again

### 4. Authorization Test
1. Login as employee #1, checkout asset A
2. Logout, login as employee #2
3. Employee #2 should NOT see "Return" button for asset A (it's checked out by #1)
4. Employee #2 trying to return asset A via URL/form should get error

### 5. Notifications
- Check `data/notifications.log` for LogNotificationChannel events
- Check `data/emails.log` if using EmailNotificationChannel

---

## 📚 Documentation

See the `docs/` folder:
- `requirements.md` - Functional & FURPS+ requirements
- `use-cases.md` - Use case flows
- `user-stories.md` - User stories with acceptance criteria
- `crc-cards.md` - Class responsibility collaboration
- `c2.md` - UML class diagram
- `polymorphism.md` - Part E explanation
- `reflection.md` - Design decisions and reflection

---

## 🔄 Git Commits

Key commits should cover:
1. Initial project structure
2. Entity and DTO creation
3. Repository implementations
4. Service layer (business logic)
5. HTTP controllers
6. Authentication system
7. Authorization checks
8. Notification system (Part E)
9. Documentation completed
10. Final testing and polish

---

## 🎓 Learning Outcomes

By studying this project, you'll understand:
- ✅ SOLID principles in PHP (especially Dependency Inversion)
- ✅ Repository pattern for data abstraction
- ✅ Service layer for business logic
- ✅ Polymorphism through interfaces
- ✅ Constructor-based dependency injection
- ✅ Role-based access control
- ✅ DTO pattern for data safety
- ✅ Separation of concerns

---

## 🚦 Troubleshooting

### "Class not found" errors
- Run `composer dump-autoload -o`
- Verify namespace matches file path

### "File not found" errors in data/
- Create `data/` directory
- Create empty JSON files: `users.json`, `assets.json`, `checkouts.json`

### Session errors
- Ensure `session_start()` is called in `public/index.php`
- Clear browser cookies if session issues persist

### Cannot return asset
- Verify you're logged in as the user who checked it out
- Check `CheckoutService::canUserReturnAsset()` logic

---

## 📝 License

Educational project - no license specified.

---

## ✍️ Author

Akhil - Revatics OOP Assessment

---

**Happy coding! Focus on clean design and SOLID principles.** 🎯
