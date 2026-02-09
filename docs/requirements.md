# Requirements: Revatics Asset Checkout System

## Part A: Functional Requirements (FR)

### FR-01: User Authentication
**As a** user  
**I want to** log in using valid credentials  
**So that** I can access system features based on my role  

- System authenticates users securely  
- On successful login, a user session is created  
- On failure, a generic error message is displayed  
- Access permissions are determined by user role  

---

### FR-02: User Registration
**As a** new employee  
**I want to** register with valid credentials  
**So that** I can access the system independently  

- Users can register using a valid and unique email address  
- Passwords must meet minimum strength requirements  
- New users are assigned the **employee** role by default  

---

### FR-03: User Logout
**As a** logged-in user  
**I want to** log out  
**So that** my session is securely terminated  

- Session data is cleared  
- User is redirected to the login page  

---

### FR-04: Asset Creation (Admin Only)
**As an** admin  
**I want to** add new assets  
**So that** the inventory can be maintained  

- Asset includes a name and category  
- Each asset is uniquely identifiable  
- Newly created assets are marked as **available** by default  

---

### FR-05: Asset Listing
**As a** logged-in user  
**I want to** view all assets  
**So that** I know their availability  

- Asset list displays name, category, and current status  
- Assets can be viewed regardless of availability  

---

### FR-06: Asset Details
**As a** logged-in user  
**I want to** view detailed information about an asset  
**So that** I can decide whether to use it  

- Displays asset ID, category, and status  
- Available assets can be checked out  
- Checked-out assets can be returned by the same user  
- Admin users may retire assets  

---

### FR-07: Asset Checkout
**As an** employee  
**I want to** check out an available asset  
**So that** I can use it for work  

- Checkout is allowed only if asset is available  
- Checkout is blocked for retired or unavailable assets  
- Asset status updates to **checked_out**  
- Checkout event is recorded  

---

### FR-08: Asset Return
**As an** employee  
**I want to** return an asset I checked out  
**So that** it becomes available again  

- Only the original user may return the asset  
- Asset status updates to **available**  
- Return event is recorded  

---

### FR-09: Asset Retirement (Admin Only)
**As an** admin  
**I want to** retire an asset  
**So that** it cannot be used again  

- Retired assets cannot be checked out  
- Retirement action is permanent  

---

### FR-10: Checkout History Viewing
**As a** logged-in user  
**I want to** view checkout history  
**So that** asset usage is transparent  

- History can be viewed globally  
- History can be filtered by specific asset  
- Each record shows checkout and return timestamps  

---

### FR-11: System Notifications
**As a** system  
**I want to** notify configured channels on checkout or return  
**So that** operations can be monitored  

- Notifications are triggered on checkout and return  
- Notification mechanism is configurable  

---

## Part B: Non-Functional Requirements (FURPS+)

> **Scope Note:**  
> The following non-functional requirements apply within the scope of this mini internal tool and do not represent enterprise-scale guarantees.

---

### F — Functionality & Security

**NFR-01: Authentication Security**
- User credentials must be securely stored and verified  
- Plain-text passwords must never be persisted  

**NFR-02: Authorization**
- Admin users manage assets  
- Employee users checkout and return assets  
- Unauthorized actions are blocked  

**NFR-03: Data Protection**
- User-controlled output must be safely rendered  
- Sensitive data must not be exposed  

---

### U — Usability

**NFR-04: Responsiveness**
- Asset list loads within **300ms** for up to **100 assets**  
- Asset detail view loads within **200ms**  

**NFR-05: User Feedback**
- All actions provide clear success or error messages  
- Messages are user-friendly and non-technical  

---

### R — Reliability

**NFR-06: Data Consistency**
- Only one active checkout per asset is allowed  
- Partial failures must not corrupt system state  

**NFR-07: Error Handling**
- Errors provide meaningful reasons without revealing internals  

---

### P — Performance

**NFR-08: Retrieval Speed**
- Checkout history loads within **100ms**  

---

### S — Supportability

**NFR-09: Maintainability**
- Code follows single-responsibility principles  
- System is modular and extensible  

---

### + Constraints

**NFR-10: Technical Constraints**
- PHP 8+  
- No external frameworks  
- Composer autoloading required  

---

## Summary

- **Functional Requirements:** 11  
- **Non-Functional Requirements:** 10  

All requirements are:
- Clear  
- Measurable  
- Testable  
- Aligned with assessment scope  
