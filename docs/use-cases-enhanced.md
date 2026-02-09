# USE CASE SPECIFICATION - ENHANCED WITH DETAILED ALTERNATE FLOWS

## ACTORS

### Actor 1: Employee (User)
- Log in with email/password using bcrypt verification
- View asset inventory with filtering by category
- View detailed information about specific assets
- Check out available assets with file-level concurrency control
- Return previously checked-out assets
- View complete checkout history
- Receive notifications on checkout/return events

### Actor 2: Admin
- Log in with admin authentication (role=admin)
- Create new assets in the inventory system
- Retire assets from circulation permanently
- Monitor asset lifecycle and status transitions
- View complete checkout and return history
- Analyze asset utilization patterns

### Actor 3: System
- Authenticateusers using password_verify() with PASSWORD_BCRYPT
- Prevent concurrent checkout conflicts using file-level locking (LOCK_EX on JSON files)
- Track asset status transitions: available → checked_out → available → retired
- Maintain immutable checkout history records (append-only log)
- Send notifications via configurable channels (log file or email)
- Enforce role-based access control (admin vs employee restrictions)

---

## USE CASES WITH COMPREHENSIVE ALTERNATE FLOWS

### UC-01: User Login (4 alternate flows)
1. User enters credentials → password_verify() validates
2. Session created with user_id and user_role
3. Redirect to asset list
**Alternates:** Invalid password | Email not found | Already logged in | Session write error

### UC-02: View Asset List (4 alternate flows)
1. System retrieves all assets from assets.json
2. Render table with status color-coding (available/checked_out/retired)
3. User can apply category filter
**Alternates:** No results in filter | Empty library | Session timeout | File read error

### UC-03: View Asset Details (4 alternate flows)
1. User selects asset and system loads attributes
2. Display name, category, status, created_date
3. Show action buttons based on status and role:
   - If available: show Checkout button
   - If checked_out by user: show Return button
   - If admin and not retired: show Retire button
**Alternates:** Asset not found | Asset retired | Checked out by other user | Non-admin authorization

### UC-04: Checkout Asset (8 alternate flows) - CONCURRENCY CRITICAL
1. Employee clicks Checkout
2. System acquires LOCK_EX on assets.json (exclusive file lock)
3. **Re-verify** asset.status = 'available' (optimistic locking for race detection)
4. Create checkout record with user_id, asset_id, checkout_timestamp
5. Append to checkouts.json with lock
6. Update asset: status = 'checked_out'
7. Release lock
8. Send notification (log or email per config)
9. Display success message

**Alternate Flows (8 paths):**
- Asset already checked out by another user (status != available)
- Asset is retired (cannot checkout retired assets)
- **Race condition between lock acquire and re-verify** (LOCK_EX prevents duplicate checkout)
- Session timeout during checkout (lock released, rollback)
- LOCK_EX acquisition fails (file permissions issue)
- Storage write failure (disk full, JSON corruption)
- Non-employee attempts checkout (authorization denied)
- Notification send failure (non-critical, checkout succeeds)

**Concurrency Guarantee:** File-level LOCK_EX prevents two checkouts of same asset via:
- Step 2: Lock acquired before re-verification
- Step 3: Re-verify detects concurrent change
- Step 5-6: Atomic write prevents split-brain scenarios

### UC-05: Return Asset (10 alternate flows) - CONCURRENCY CRITICAL
1. Employee clicks Return on checked-out asset
2. System acquires LOCK_EX on both checkouts.json and assets.json
3. **Multi-field verification:**
   - asset.status = 'checked_out'
   - checkout record exists for this user
   - checkout.return_date = null
4. Update checkout record: return_date = NOW()
5. Update asset: status = 'available' (clear checked_out flag)
6. Write both files atomically
7. Release lock
8. Send notification
9. Display success

**Alternate Flows (10 paths):**
- Asset not currently checked out (status already available)
- Checkout belongs to different user (authorization check)
- Already marked as returned (prevents duplicate return)
- Asset status is retired (cannot process retired returns)
- Session timeout during operation (lock released, rollback)
- Lock acquisition timeout/failure
- **Concurrent return race condition** (LOCK_EX serializes, one succeeds, other sees return_date != null)
- Storage write failure (rollback, asset remains checked_out)
- Non-original employee attempts return (authorization denied)
- Notification failure (non-critical, return succeeds)

**Concurrency Guarantee:** LOCK_EX ensures only one return processes:
- Step 2: Exclusive lock acquired
- Step 3: Three-condition verification (status + record + return_date)
- Steps 4-6: Atomic multi-field update prevents split-brain

### UC-06: Create Asset (4 alternate flows)
1. Admin enters asset name and category (both required)
2. System validates inputs (non-empty strings, valid category)
3. Generate unique asset_id
4. Create asset object with status = 'available'
5. Append to assets.json with lock
6. Display success with new asset_id

**Alternates:** Validation failure (missing fields) | Non-admin access denied | Duplicate name allowed | File write error

### UC-07: Retire Asset (4 alternate flows)
1. Admin clicks Retire on active asset
2. Acquire lock on assets.json
3. Verify status != 'retired' (prevent double-retire)
4. Set asset.status = 'retired' and retired_date = NOW()
5. Write to file, release lock
6. Checkout button hidden from all users

**Alternates:** Already retired | Non-admin access | File write error | Retiring checked-out asset (allowed, employee can still return)

### UC-08: View Checkout History (4 alternate flows)
1. User clicks View History on asset detail
2. System queries checkouts.json for all records matching asset_id
3. Display table: UserEmail | CheckoutDate | ReturnDate | DaysOut
4. Sort by most recent first
5. Show "Not Returned" if return_date is null (currently checked out)

**Alternates:** No history exists | Asset not found | Malformed records | Session timeout