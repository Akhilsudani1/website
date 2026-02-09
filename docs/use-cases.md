# Use Case Specification – Revatics Asset Checkout System

## Actors

### Actor 1: Employee
- Logs in using valid credentials
- Views asset inventory and asset details
- Checks out available assets
- Returns assets checked out by self
- Views global and per-asset checkout history
- Receives system notifications

### Actor 2: Administrator
- Logs in with admin role
- Creates new assets
- Retires assets from circulation
- Views global checkout history for audit purposes

### Actor 3: System
- Authenticates users and manages sessions
- Enforces role-based access control
- Maintains asset state transitions
- Records immutable checkout history
- Sends notifications on checkout and return events

---

## UC-01: User Authentication

**Primary Actor:** Employee / Administrator  
**Precondition:** User is not logged in  
**Postcondition:** Authenticated session is created  

### Primary Flow
1. User navigates to the login page
2. User enters email and password
3. System validates credentials
4. System creates authenticated session
5. User is redirected to asset list page

### Alternate Flows
- **1A – Invalid credentials:**  
  System displays a generic error message and remains on login page

- **1B – User already logged in:**  
  System redirects user to asset list automatically

- **1C – System error during login:**  
  System displays error and does not create session

---

## UC-02: View Asset List

**Primary Actor:** Employee / Administrator  
**Precondition:** User is logged in  
**Postcondition:** Asset list is displayed  

### Primary Flow
1. User navigates to asset list page
2. System verifies active session
3. System retrieves all assets
4. System displays assets with name, category, and status

### Alternate Flows
- **2A – No assets exist:**  
  System displays message: “No assets available”

- **2B – Session expired:**  
  System redirects user to login page

---

## UC-03: View Asset Details

**Primary Actor:** Employee / Administrator  
**Precondition:** User is logged in  
**Postcondition:** Asset details displayed  

### Primary Flow
1. User selects an asset from the list
2. System retrieves asset details
3. System displays asset information and status
4. System shows available actions based on role and status

### Alternate Flows
- **3A – Asset not found:**  
  System displays “Asset not found” message

- **3B – Session expired:**  
  User is redirected to login page

---

## UC-04: Checkout Asset

**Primary Actor:** Employee  
**Precondition:** User is logged in and asset is available  
**Postcondition:** Asset is checked out and recorded  

### Primary Flow
1. User selects “Checkout” on an available asset
2. System verifies asset availability
3. System records checkout event
4. Asset status changes to “checked_out”
5. System sends notification
6. User sees confirmation message

### Alternate Flows
- **4A – Asset already checked out:**  
  System blocks checkout and shows error

- **4B – Asset retired:**  
  System prevents checkout and displays message

- **4C – Session expired:**  
  User redirected to login page

---

## UC-05: Return Asset

**Primary Actor:** Employee  
**Precondition:** User has checked out the asset  
**Postcondition:** Asset becomes available  

### Primary Flow
1. User selects “Return” on checked-out asset
2. System verifies ownership
3. System records return event
4. Asset status changes to “available”
5. Notification is sent
6. User sees success message

### Alternate Flows
- **5A – Asset not checked out:**  
  System displays error message

- **5B – Asset checked out by another user:**  
  Return is blocked

---

## UC-06: Create Asset

**Primary Actor:** Administrator  
**Precondition:** Admin is logged in  
**Postcondition:** Asset is added to inventory  

### Primary Flow
1. Admin enters asset details
2. System validates input
3. System creates asset with status “available”
4. Asset appears in asset list

### Alternate Flows
- **6A – Non-admin user attempts action:**  
  System denies access

- **6B – Validation failure:**  
  System displays error message

---

## UC-07: Retire Asset

**Primary Actor:** Administrator  
**Precondition:** Asset exists and admin is logged in  
**Postcondition:** Asset is retired  

### Primary Flow
1. Admin selects “Retire Asset”
2. System updates asset status to “retired”
3. Asset becomes unavailable for checkout

### Alternate Flows
- **7A – Asset already retired:**  
  System performs no action

- **7B – Asset not found:**  
  System displays error message

---

## UC-08: View Global Checkout History

**Primary Actor:** Employee / Administrator  
**Precondition:** User is logged in  
**Postcondition:** Checkout history displayed  

### Primary Flow
1. User navigates to checkout history page
2. System retrieves all checkout records
3. System displays history sorted by date

### Alternate Flows
- **8A – No history exists:**  
  System displays “No checkout history”

---

## UC-09: View Per-Asset Checkout History

**Primary Actor:** Employee / Administrator  
**Precondition:** Asset exists and user is logged in  
**Postcondition:** Asset-specific history shown  

### Primary Flow
1. User selects “View History” on asset
2. System retrieves checkout history for asset
3. System displays records

### Alternate Flows
- **9A – No history for asset:**  
  System displays message

- **9B – Invalid asset ID:**  
  System shows error message
