# Revatics — PHP OOP + Design Principles Assessment (Akhil)

> **Goal:** This assessment checks your understanding of object-oriented design *and* your ability to apply it in a small real-world PHP codebase.

---

## What you’ve learned (scope of this test)
This test is based on the OOP principles from your learning:
- **Abstraction, Encapsulation, Inheritance, Polymorphism**
- **UML** (high-level class modeling)
- **Requirements thinking** (Functional + Non‑Functional using **FURPS+**)
- **Use cases** (actors, scenarios, user stories)
- **CRC cards** (Class–Responsibility–Collaborator)
- **Interfaces & dependency management** (reduce coupling; swap implementations)
- **DTOs + types** (clear, explicit data shapes)
- **Composition vs inheritance** (prefer composition when it fits)

References (for revision / help):
- FURPS / FURPS+ overview: https://en.wikipedia.org/wiki/FURPS  
- CRC cards intro: https://agilemodeling.com/artifacts/crcmodel.htm  
- DTO pattern: https://martinfowler.com/eaaCatalog/dataTransferObject.html  
- PHP OOP visibility: https://www.php.net/manual/en/language.oop5.visibility.php  
- PHP interfaces: https://www.php.net/manual/en/language.oop5.interfaces.php  
- PHP abstract classes: https://www.php.net/manual/en/language.oop5.abstract.php  
- UML class diagram (practical article): https://developer.ibm.com/articles/the-class-diagram/  

If you get stuck, you can also refer to the official **PHP documentation**:
- https://www.php.net/manual/en/

---

## Submission rules
1. Create a folder named: `revatics-oop-assessment-akhil/`
2. Use **Git** with meaningful commits (at least 6–10 commits).
3. Include a `README.md` with:
   - Setup steps
   - How to run the project (commands)
   - Short explanation of your architecture
4. No frameworks (no Laravel) — plain PHP only.
5. Use `declare(strict_types=1);` in all PHP files you create.
6. Prefer **typed properties** and **type hints** wherever reasonable.
7. You **must not** use `die()` / `var_dump()` in final submission (logging is okay during dev but remove it).

---

# The Assessment
You will build a mini project called:

## ✅ “Revatics Asset Checkout” (Mini System)

### Problem statement
In our office, assets like laptops, monitors, keyboards, and test devices are shared. We need a small internal tool to:
- list assets
- allow employees to request/check out an asset
- return assets
- view checkout history

This should be designed using OOP principles and a clean architecture mindset.

---

## Part A — Requirements (15%)

### A1) Functional requirements (minimum 10)
Write at least **10 functional requirements**. Example format:

- FR-01: As a user, I can view the list of assets.
- FR-02: As a user, I can check out an available asset.
- ...

### A2) Non-functional requirements using FURPS+ (minimum 8)
Write at least **8 non-functional requirements**, categorized under **FURPS+**.
Example:

- **Usability:** The app should be usable on mobile screens.
- **Reliability:** No checkout should be duplicated for the same asset at the same time.
- **Performance:** Asset list loads under 300ms for 500 assets.
- **Supportability:** Code should be organized in modules and easy to extend.
- **+ Constraints:** PHP version, no framework, etc.

Deliverable: `docs/requirements.md`

---

## Part B — Use Cases + User Stories (15%)

### B1) Actors
Identify at least **3 actors** (examples: Employee, Admin, System/Time Scheduler).

### B2) Use cases (minimum 6)
Write at least **6 use cases** with:
- name
- primary actor
- preconditions
- main flow (steps)
- alternate flows (at least 1)
- postconditions

Deliverable: `docs/use-cases.md`

### B3) User stories (minimum 8)
Write at least **8 user stories** with acceptance criteria.
Format:

**US-01:** As an employee, I can check out an asset so that I can use it for work.  
**Acceptance Criteria:**
- Given the asset is available, when I submit checkout, then it becomes “checked out”.
- If it’s not available, I should see an error message.
- A checkout record is stored.

Deliverable: `docs/user-stories.md`

---

## Part C — CRC Cards + Class Design (15%)

### C1) CRC Cards (minimum 6 classes)
Create CRC cards for at least 6 classes you plan to implement.

Each card should include:
- Class name
- Responsibilities (3–5)
- Collaborators (other classes it works with)

Deliverable: `docs/crc-cards.md`

### C2) UML Class Diagram (high-level)
Create a UML class diagram (even a simple one). You can:
- draw it in **draw.io** / **FigJam** / **Whimsical** / **Figma**, or
- write a text version (PlantUML is okay).

It must show:
- main classes
- key properties/methods (high-level only)
- relationships (association / aggregation / composition) where meaningful
- one example of **inheritance** OR explain why you avoided it

Deliverable: `docs/uml/` (image or `.puml`)

---

# Part D — Implementation (45%)

## D0) Suggested folder structure
Use this structure (you can improve it if you have a strong reason):

```
revatics-oop-assessment-akhil/
  public/
    index.php
  src/
    Core/
    Http/
    Domain/
    Storage/
    Support/
  views/
  data/
  docs/
  composer.json
  README.md
```

✅ Use Composer autoloading (PSR-4).  
Deliverable: `composer.json` with an autoload section and `vendor/` ignored in `.gitignore`.

---

## D1) Core features (must build)
### 1) Assets
- List assets
- View a single asset
- Add an asset (Admin only)
- Mark asset status: `available` / `checked_out` / `retired`

### 2) Checkouts
- Check out an asset (Employee)
- Return an asset
- View checkout history per asset
- Prevent checkout if asset is already checked out

### 3) Users (simple auth)
- Register
- Login
- Logout
- Roles: `admin`, `employee`

**Security requirement:** passwords must be hashed using `password_hash()` and verified using `password_verify()`.

### 4) Minimal Routing
- Create a basic router that supports at least:
  - GET, POST
  - and **method spoofing** for PATCH/DELETE using `_method` hidden input

---

## D2) OOP requirements (this is what we are grading)
### Must have
1. **Encapsulation**
   - private/protected properties
   - behavior through methods (avoid “data bag” objects unless it’s a DTO)
2. **Interfaces**
   - At least 1 interface that has 2 implementations you can swap.
   - Example: `StorageInterface` with `FileStorage` and `SQLiteStorage` (or `MySqlStorage`) OR `LoggerInterface` with `FileLogger` and `NullLogger`.
3. **Composition**
   - At least 2 places where you use composition (a class holds a reference to another class).
4. **DTO**
   - At least 2 DTOs for request data, e.g. `CreateAssetDTO`, `CheckoutAssetDTO`.
5. **Validation**
   - Create a small `Validator` (can be simple) and use it in controllers/services.
6. **Low coupling**
   - No controller should directly create PDO or file handlers in-line.
   - Dependencies should be injected (constructor injection preferred).
7. **Readable code**
   - Names should communicate intent.
   - Don’t use “God classes”.

### Optional (bonus)
- A tiny service container to resolve dependencies.
- Flash messages in session.
- Simple tests (Pest or PHPUnit) for at least 3 core rules.
- Use `enum` for status/role if your PHP version supports it.

---

## Part E — Polymorphism Challenge (10%)

Implement one “swappable behavior” using polymorphism.

Choose one:

### Option 1: Notification channels
- `NotificationChannel` interface
- `EmailNotificationChannel` (fake email sending is OK)
- `LogNotificationChannel` (writes to a log file)
When checkout happens, notify through a channel configured in your config.

### Option 2: Asset ID strategies
- `AssetIdGenerator` interface
- `SequentialIdGenerator`
- `UuidGenerator`
You can swap strategy via configuration.

Deliverable: Working code + short explanation in `docs/polymorphism.md`

---

## Part F — Reflection (optional but strongly recommended)
Write 10–20 lines answering:
- Where did you use composition and why?
- Where did you use interfaces and why?
- What tradeoffs did you make?
- What would you improve if this became a real internal tool?

Deliverable: `docs/reflection.md`

---

# Marking Rubric (summary)
- **Requirements (FURPS+ + functional clarity):** 15
- **Use cases + user stories quality:** 15
- **CRC + UML correctness + relationships clarity:** 15
- **Implementation completeness + code quality:** 45
- **Polymorphism challenge:** 10
**Total:** 100

---

## Quick checklist before you submit
- [ ] `README.md` has setup + run commands
- [ ] Requirements, use cases, CRC, UML are included
- [ ] Auth works + passwords hashed
- [ ] Checkouts prevent invalid states
- [ ] Interface + 2 implementations exists and is usable
- [ ] DTOs exist and are used
- [ ] No debug dumps remain
- [ ] Clean structure + meaningful names
- [ ] At least 6–10 commits

---

## Help allowed
- You may use PHP official docs and your notes.
- You may refer to:
  - PHP docs: https://www.php.net/manual/en/
  - MDN (general web concepts): https://developer.mozilla.org/
- Do **not** copy-paste complete solutions from the internet.

Good luck — focus on clean design first, then code.
