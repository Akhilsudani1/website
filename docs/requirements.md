US-01: User Login
As a user or admin, I can log in using my credentials
so that I can access the system based on my role.

US-02: Manage Assets (Admin)
As an admin, I can create, update, view, and delete assets
so that the asset inventory is always accurate and up to date.

US-03: View & Filter Assets
As a user, I can view the asset list and filter it by category
so that I can quickly find the asset I need.

US-04: Search Assets
As a user, I can search assets by name or asset ID
so that I can easily locate a specific asset.

US-05: View Asset Details
As a user, I can view full details of an asset
so that I understand its condition and availability before requesting it.

US-06: Request & Checkout Asset
As a user, I can request and check out an available asset
so that I can use it for my work.

US-07: Prevent Invalid Checkout
As a system, I must prevent checkout of an asset that is already checked out, reserved, or retired
so that asset conflicts and misuse are avoided.

US-08: Return Asset
As a user, I can return a checked-out asset
so that it becomes available for others.

US-09: Asset Condition Report
As a user, I can report the condition of an asset during return
so that the admin is aware of any damage or issues.

US-10: View Asset History
As a user or admin, I can view the checkout history of an asset
so that asset usage can be tracked.

US-11: Notifications & Reminders
As a user, I receive notifications and return reminders
so that I do not miss asset return deadlines.

US-12: Reserve Asset
As a user, I can reserve an asset for a future date
so that I am guaranteed availability when I need it.

US-13: Export Reports (Admin)
As an admin, I can export asset usage and checkout reports
so that I can analyze asset utilization.








F — Functionality (Security & Compliance)

NFR-01: Security (Authentication)
The system must authenticate users using email/username and password, and passwords must be securely hashed using password_hash() and verified using password_verify().

NFR-02: Authorization (Role-Based Access)
The system must enforce role-based access control so that only admins can manage assets and export reports, while users can only request, checkout, and return assets.

U — Usability

NFR-03: Ease of Use
The system should be easy to use with clear navigation and readable messages so that users can perform asset checkout and return without training.

NFR-04: User Feedback
The system should display clear success and error messages for actions such as login, checkout, return, and invalid operations.

R — Reliability

NFR-05: Data Consistency
The system must ensure that an asset cannot be checked out by more than one user at the same time.

NFR-06: Fault Handling
If an operation fails (e.g., invalid checkout or return), the system should not change the asset’s state and should show an appropriate error message.

P — Performance

NFR-07: Response Time
The system should load asset lists and asset details within acceptable response time for normal usage (e.g., under 1 second for standard asset lists).

S — Supportability

NFR-08: Maintainability
The codebase must follow object-oriented principles with proper separation of concerns, making it easy to extend or modify features such as storage type or notification method.

NFR-09: Extensibility
The system should support swapping implementations (e.g., storage or notification channels) using interfaces without changing core business logic.


+ Constraints (Design & Technical Constraints)

NFR-10: Technical Constraints
The system must be developed using plain PHP (no frameworks).
Composer autoloading (PSR-4) must be used.
The application must not use die() or var_dump() in the final submission.