US-01: User Login
As a user or admin, I can log in using my credentials
so that I can access the system based on my role.

Acceptance Criteria:
Given valid credentials, when I log in, then I am redirected to my dashboard.
Given invalid credentials, when I log in, then I see an error message.
A session is created after successful login.

US-02: View Asset List
As a user, I can view the list of assets
so that I know which assets are available.

Acceptance Criteria:
Asset list is displayed after login.
Each asset shows basic details such as name, category, and status.

US-03: Filter Assets by Category
As a user, I can filter assets by category
so that I can quickly find the required asset.

Acceptance Criteria:
Given a selected category, only assets of that category are displayed.
If no assets exist in a category, a message is shown.

US-04: Search Assets
As a user, I can search assets by name or asset ID
so that I can easily locate a specific asset.

Acceptance Criteria:
Search returns matching assets.
If no match is found, an appropriate message is shown.

US-05: View Asset Details
As a user, I can view complete details of an asset
so that I can decide whether to request it.

Acceptance Criteria:
Asset details page shows status, category, and condition.
Asset availability is clearly displayed.

US-06: Request & Checkout Asset
As a user, I can request and check out an available asset
so that I can use it for my work.

Acceptance Criteria:
Given the asset is available, checkout is successful.
Asset status changes to “checked out”.
A checkout record is created.

US-07: Prevent Invalid Checkout
As a system, I must prevent checkout of an asset that is already checked out, reserved, or retired
so that asset conflicts are avoided.

Acceptance Criteria:
System blocks checkout for unavailable assets.
An error message is shown if checkout is attempted.

US-08: Return Asset
As a user, I can return a checked-out asset
so that it becomes available for others.

Acceptance Criteria:
Asset status changes to “available” after return.
Return action is recorded in history.

US-09: Asset Condition Report
As a user, I can report the condition of an asset during return
so that the admin is informed about any issues.

Acceptance Criteria:
User can submit condition notes during return.
Admin can view the condition report.

US-10: View Asset History
As a user or admin, I can view asset checkout history
so that asset usage can be tracked.

Acceptance Criteria:
History shows checkout and return records.
If no history exists, a message is displayed.

US-11: Notifications & Reminders
As a user, I receive return reminders and notifications
so that I do not miss asset return deadlines.

Acceptance Criteria:
Notification is sent before return due date.
Notification is logged or displayed to the user.

US-12: Reserve Asset
As a user, I can reserve an asset for a future date
so that it is available when I need it.

Acceptance Criteria:
Reservation is created for a valid future date.
System prevents overlapping reservations.

US-13: Export Reports
As an admin, I can export asset usage and checkout reports
so that I can analyze asset utilization.

Acceptance Criteria:
Report can be exported successfully.
Exported data includes asset and checkout details.