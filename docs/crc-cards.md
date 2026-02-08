CRC-01: User
Responsibilities:
Store user information (id, name, role)
Identify user role (admin or employee)
Initiate login and logout actions
Perform user-level actions (checkout, return, reserve assets)

Collaborators:
AuthService
CheckoutService
Asset


CRC-02: Asset
Responsibilities:
Store asset information (asset ID, name, category, status)
Track asset availability (available, checked out, reserved, retired)
Update asset status during checkout and return
Provide asset details for viewing

Collaborators:
AssetService
Checkout
AssetRepository


CRC-03: Checkout
Responsibilities:
Store checkout information (asset, user, dates, condition)
Track checkout and return history
Record checkout duration
Record asset condition on return

Collaborators:
User
Asset
CheckoutService


CRC-04: AuthService
Responsibilities:
Authenticate users using credentials
Verify user roles (admin or employee)
Create and destroy user sessions
Enforce role-based access control

Collaborators:
User
UserRepository
SessionManager


CRC-05: AssetService
Responsibilities:
Manage asset lifecycle (create, update, delete)
Retrieve asset lists and asset details
Validate asset availability
Coordinate asset checkout and return actions

Collaborators:
Asset
AssetRepository
CheckoutService


CRC-06: CheckoutService
Responsibilities:
Handle asset checkout logic
Prevent invalid or duplicate checkouts
Process asset returns
Maintain checkout history

Collaborators:
Checkout
Asset
User
CheckoutRepository


CRC-07: NotificationService
Responsibilities:
Send notifications and reminders to users
Trigger notifications for checkout and return deadlines
Support multiple notification channels using interfaces

Collaborators:
User
NotificationChannelInterface
CheckoutService


CRC-08: AssetRepository
Responsibilities:
Store and retrieve asset data
Persist asset changes
Fetch assets by ID, category, or status

Collaborators:
Asset
AssetService