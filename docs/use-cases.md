Actor 1: Employee (User)
Description:
An employee is a normal system user who uses office assets for work purposes.

Responsibilities:
Log in to the system
View and search the asset list
View asset details
Request and check out available assets
Specify checkout duration
Return assets and report asset condition
View asset checkout history
Receive notifications and return reminders
Reserve assets for future use

Actor 2: Admin
Description:
An admin is a privileged user responsible for managing assets and monitoring asset usage.

Responsibilities:
Log in with admin privileges
Create, update, and delete assets
Manage asset categories
View asset details and full checkout history
Verify asset condition at return time
Generate and export asset usage and checkout reports

Actor 3: System
Description:
The system represents automated internal processes that enforce business rules and handle background operations.

Responsibilities:
Authenticate users and enforce role-based access control
Prevent invalid checkouts and overlapping reservations
Track asset status (available, checked out, reserved, retired)
Generate checkout history records
Send notifications and return reminders to users


b2)


UC-01: User Login
Primary Actor: User / Admin
Preconditions:
User or admin is registered in the system.
User is not already logged in.
Main Flow:
User enters email/username and password.
System validates the credentials.
System identifies the user role (admin or employee).
System creates a session.
User is redirected to the dashboard based on role.
Alternate Flow:
If credentials are invalid, system shows an error message.
Postconditions:
User is logged in with role-based access.


UC-02: View & Filter Asset List
Primary Actor: User
Preconditions:
User is logged in.
Main Flow:
User opens the asset list page.
System displays all available assets.
User selects a category filter.
System shows assets matching the selected category.
Alternate Flow:
If no assets exist in the selected category, system shows a “No assets found” message.
Postconditions:
Filtered asset list is displayed.


UC-03: View Asset Details
Primary Actor: User
Preconditions:
User is logged in.
Asset exists in the system.
Main Flow:
User selects an asset from the list.
System displays complete asset details (status, category, condition).
Alternate Flow:
If asset no longer exists, system shows an error message.
Postconditions:
Asset details are displayed to the user.


UC-04: Request & Checkout Asset
Primary Actor: User
Preconditions:
User is logged in.
Asset is available.
Main Flow:
User selects an available asset.
User specifies checkout duration.
User submits checkout request.
System verifies asset availability.
System marks asset as checked out.
System creates a checkout record.
Alternate Flow:
If asset is already checked out, reserved, or retired, system rejects the request.
Postconditions:
Asset is checked out and linked to the user.


UC-05: Return Asset
Primary Actor: User
Preconditions:
User is logged in.
Asset is currently checked out by the user.
Main Flow:
User selects the checked-out asset.
User submits a return request.
User reports the asset condition.
System marks the asset as returned.
System updates the checkout history.
Alternate Flow:
If the asset is not checked out by the user, system denies the return request.
Postconditions:
Asset becomes available for future use.


UC-06: Manage Assets (Admin)
Primary Actor: Admin
Preconditions:
Admin is logged in.
Main Flow:
Admin opens asset management panel.
Admin creates, updates, or deletes an asset.
System validates asset data.
System saves changes.
Alternate Flow:
If asset data is invalid, system shows validation errors.
Postconditions:
Asset inventory is updated.


UC-07: View Asset Checkout History
Primary Actor: User / Admin
Preconditions:
User or admin is logged in.
Asset exists.
Main Flow:
User/admin selects an asset.
System retrieves checkout and return history.
System displays the asset history.
Alternate Flow:
If no history exists, system displays an empty history message.
Postconditions:
Asset history is visible.