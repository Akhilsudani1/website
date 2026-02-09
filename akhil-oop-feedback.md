# Revatics — OOP (PHP) Assessment Feedback (Akhil)

_Date: 2026-02-09_  
_Project: **Office Asset Checkout System (PHP + JSON storage)**_

---

## Overall result

You have a **solid OOP foundation** in this submission: you used **interfaces**, **composition**, **DTOs**, a **service layer**, and separate **repositories** (good separation of concerns for a beginner project).

However, there are also **important gaps** where the **implementation does not match the assignment requirements and your own docs** (especially routing, registration, asset statuses, single-asset views, and history per asset).

**Score: 74/100 (Pass, but needs improvements to meet the full spec).**

---

## Score breakdown (high level)

- Documentation (requirements + use cases + CRC/UML): **13/15**
- OOP implementation (encapsulation, interfaces, composition, DTOs): **25/30**
- Routing + HTTP flow: **6/15**
- Auth + sessions + security: **8/10**
- Data layer (repositories, persistence quality): **7/10**
- Polymorphism challenge: **9/10**
- Code quality & maintainability: **6/10**

---

## What you did well ✅

### 1) Clear layering
- `Controller → Service → Repository` is a clean structure for a PHP app.
- Business rules like “only admin can create assets” are enforced in the service layer (`AuthService::requireAdmin()`).

### 2) Interfaces + polymorphism (good)
- `NotificationChannel` + `EmailNotificationChannel`/`LogNotificationChannel` is a correct polymorphic design.
- Your `docs/polymorphism.md` explains the concept clearly.

### 3) Using DTOs
- `CreateAssetDTO` and `CheckoutAssetDTO` are good examples of controlling input shape and keeping method signatures clean.

### 4) Strict types + modern PHP style
- `declare(strict_types=1);`
- Namespaces + PSR-4 autoloading in `composer.json` are correct.

---

## Biggest gaps to fix (must-do) 🔥

### 1) Missing registration (assignment requirement)
**Expected:** `GET /register` + `POST /register` that creates a user and stores a **hashed** password.  
**Current:** No registration flow (users are hard-coded in `data/users.json`).

✅ Fix:
- Add `register()` in `AuthController`.
- Add `create()` method in `UserRepositoryInterface` + implement it in `FileUserRepository`.
- Use `password_hash()` when storing passwords and `password_verify()` for login.

---

### 2) Routing is not implemented as required (router + method spoofing)
**Expected:** a `Router` class supporting:
- routes by method (GET/POST/PATCH/DELETE)
- method spoofing via `_method` for PATCH/DELETE (forms)

**Current:** routing is a set of if/else conditions in `public/index.php`.

✅ Fix:
- Create `src/Http/Router.php` and move route matching there.
- Support `_method` hidden input for PATCH/DELETE (e.g., retire asset).
- Use `http_response_code(404)` for unknown routes.

---

### 3) Asset status is incomplete (no “retired” / “reserved”)
**Expected (minimum):** `available`, `checked_out`, `retired`  
**Current:** only `available` and `checked_out`.

✅ Fix:
- Add a `status` constant list (or enum if you want) and a `retire()` method.
- Block checkout for retired assets.
- Update docs OR implement the “reserved” flow if you keep it in docs.

---

### 4) No single asset page + no per-asset history page
**Expected:**
- `/assets/{id}` or `/asset?id=...` view
- history by asset

**Current:**
- asset list only
- history is global (`/history`) and not filtered per asset

✅ Fix:
- Add repository methods:
  - `AssetRepositoryInterface::findById(string $id)`
  - `CheckoutRepositoryInterface::findByAssetId(string $assetId)`
- Add a route + controller action to render:
  - asset details
  - that asset’s checkout history

---

## Important code quality + security improvements ⚠️

### 1) Escape output (XSS risk)
You echo user-controlled values like asset name/category directly into HTML in:
- `src/Http/Controller/AssetController.php`

✅ Fix:
Use `htmlspecialchars()` whenever outputting untrusted text:

```php
<?= htmlspecialchars($asset->getName(), ENT_QUOTES, 'UTF-8') ?>
```

---

### 2) Validation should not type-error when POST fields are missing
`Validator::required(string $value, string $fieldName)` will throw a TypeError if the key is missing and `null` is passed.

✅ Fix option A (recommended): make validator accept mixed:
```php
public static function required(mixed $value, string $fieldName): void
{
    if (!is_string($value) || trim($value) === '') {
        throw new \InvalidArgumentException("$fieldName is required");
    }
}
```

✅ Fix option B: sanitize in controller:
```php
$name = trim($_POST['name'] ?? '');
Validator::required($name, 'Asset Name');
```

---

### 3) Redirects should `exit;` after sending headers
In multiple controllers you do:
```php
header('Location: /');
```
…but you don’t `exit;`. This can lead to unexpected output being sent after redirect.

✅ Fix:
```php
header('Location: /');
exit;
```

---

### 4) Session security
After successful login, you should regenerate the session ID.

✅ Fix:
```php
session_regenerate_id(true);
```

---

### 5) JSON persistence should lock writes
When writing JSON files, use `LOCK_EX` to reduce corruption risk if two requests write at the same time.

✅ Fix:
```php
file_put_contents($path, $json, LOCK_EX);
```

---

## Docs vs Code mismatch (pick one)
Your docs include features like:
- update/delete assets
- reserve asset
- report condition
- reminders

…but your code doesn’t implement them.  
This is not “wrong”, but you must do **one** of these:

✅ Option A: implement those features  
✅ Option B: simplify docs to match what you actually built

---

## Suggested improvement plan (what to do next)

### Phase 1 (2–3 hours)
1. Add `exit;` after redirects
2. Escape output with `htmlspecialchars()`
3. Fix validation to not type-error
4. Add `LOCK_EX` when writing JSON

### Phase 2 (1 day)
1. Implement `Router` class (+ `_method` spoofing)
2. Add “retired” status + retire endpoint
3. Add asset detail page + per-asset history page

### Phase 3 (1 day)
1. Implement `register` flow with `password_hash()`
2. Add basic “condition report” on return (text field), store it with checkout record
3. Align docs fully with code

---

## Helpful references (use these when fixing)
- Password hashing: https://www.php.net/manual/en/function.password-hash.php  
- Password verification: https://www.php.net/manual/en/function.password-verify.php  
- Escaping output: https://www.php.net/manual/en/function.htmlspecialchars.php  
- Preventing XSS (why output encoding matters): https://cheatsheetseries.owasp.org/cheatsheets/Cross_Site_Scripting_Prevention_Cheat_Sheet.html  
- PSR-4 autoloading spec: https://www.php-fig.org/psr/psr-4/  

---

## Final note

You are on the right track with the architecture.  
To level up, focus on **finishing the full functional spec** (router + register + statuses + detail pages) and **tightening security basics** (escape output + session hygiene).
