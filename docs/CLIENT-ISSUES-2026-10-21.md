# Hello Transport / CrazyRays: client issues, 20–21 Oct 2026

**Sources:** the client's email (7 items, 9 screenshots).

**How each item was checked:**
- the code of `washinton_agent` (Hello `main`, CrazyRays branch `crazyrays`) and `washinton_hr`;
- the live HR log (read only);
- the 13 Oct table measurements in `docs/split-2026-10/`.

**Progress (21 Oct):** S1–S3 and items 1–7 are done and pushed. Items 1–6 are live; item 7 is waiting for its deploy. S4 and S5 still need a decision.

**Status:** 🔴 urgent or blocks testing · 🟠 bug · 🟡 change request · ⚪ needs a decision

| # | Item | Cause, in one line | Fix size | State |
|---|---|---|---|---|
| S | **Security holes found while investigating** | Sign-in backdoor live on both sites, view-as and HR admin shortcut open to any signed-in user, card numbers stored in plain text | small (hours) | ✅ S1–S3 live (1e7e726); S4, S5 open |
| 1 | Mobile menu has no "Earn With Us" | The link is the desktop button, which the theme hides below 992 px | small | ✅ live (94a69d6) |
| 2 | Documents Pending: no upload form | Upload form **and** upload endpoint only allow status 7 | small | ✅ live (HR af09010) |
| 3 | Check-in refused for a New York agent | Shortlisting never copies the applicant's time zone, so HR uses Pakistan time | small, plus a one-time correction | ✅ live (HR 0aeae6d) |
| 4 | "Subcontractor not found or status unchanged!" on In Progress | The agent's questionnaire had already moved her to In Progress; the page was stale | small | ✅ live (HR 0aeae6d) |
| 5 | Reset all CRM and HR data | Scope prepared below; it conflicts with "do not remove any orders" | medium, needs answers | ✅ done 21 Oct: accounts removed except admins/managers, orders kept |
| 6 | Real-time alert for new campaign leads in HR | HR has no alerts at all; Pusher is not set up anywhere | small–medium | ✅ live (HR 525f7ef) |
| 7 | Device restriction: go ahead with the proposal | Plan below; no device tracking exists yet | about 1–1.5 weeks | ✅ pushed (main 3aa51af + 1a5e2a2, crazyrays c308496 + 6525b12); deploy pending |

---

## S. Security holes found during the investigation (fix before anything else)

These were not in the client's list. They were found while tracing the sign-in flows for item 7, and the first
three were confirmed in the code and, where possible, on the live sites.

### S1 🔴 Sign-in backdoor `/login_mohsin` is live on hellotransport.com and florida
- **Where:**
  - route `washinton_agent/routes/web.php:114`, outside every middleware group;
  - code `WelcomeController::login_mohsin` (`app/Http/Controllers/WelcomeController.php:547-557`).
- **What it does:** with the parameters `id` and `password=78601`, it calls `Auth::login()` on that user id. There is no emailed code and no IP check, and it works for **any user, including the admin (id 1)**.
- **Live check, done without the password:** `https://hellotransport.com/login_mohsin` returns 200 and
  `florida.crazyrayssolutions.com.pk/login_mohsin` returns 200. A path that doesn't exist returns 404, so the route
  is deployed on both.
- **Fix:** delete the route and the method on `main` and on `crazyrays`, deploy both. Size: minutes.

### S2 🔴 Any signed-in user can "view as" any account, including the admin
- **Where:**
  - `ScopeController::enter` (`app/Http/Controllers/ScopeController.php:11-27`) has no role check;
  - its route `web.php:213` sits in a group that only requires being signed in (`web.php:173`, `['auth']`).
- **Effect:** an agent who opens `/scope/enter/1` becomes the admin. View-as also skips the single-session and
  IP checks.
- **Fix:** allow it for Admin (and Manager, if the client wants) only, in the controller and as route middleware.
  Size: small.

### S3 🔴 Any signed-in CRM user can open the HR portal as the HR super admin
- **Where:** `HrPortalRedirectController::adminRedirect` (`app/Http/Controllers/HrPortalRedirectController.php:21-35`,
  route `web.php:184`).
- **Why:** the controller has no role check. The HR side then logs in `Admin::first()` whoever clicked
  (`washinton_hr/app/Http/Controllers/Bridge/HrBridgeController.php:477-482`).
- **Fix:**
  - role check on the CRM side;
  - sign the payload with the bridge key;
  - log in the matching HR admin, not the first one.

  Size: small.

### S4 🔴 Card numbers and CVVs are stored in plain text
- **Where:** `creditcards` (48 of 146 rows) and `orderpayments` (48 of 257 rows) hold 19-digit card numbers and 3-digit codes. Only the lengths were checked; no values were read.
- **Why it matters:**
  - Storing a CVV after authorisation is not allowed under the card rules (PCI DSS).
  - Every database backup carries these values, including the old local `hellobackup` copy.
- **Fix:** stop storing CVVs, and mask or encrypt card numbers. The existing rows need a decision, ideally deletion
  of the CVV column contents. Size: small for new writes; the data clean-up needs the client's OK.

### S5 🟠 Sign-in weaknesses to close alongside item 7
- **Login code:** it is always `123456` (`WelcomeController.php:225`, `:254`); it should be random.
- **Password in the URL:** the base64 password is put in the verify URL (`:220`), so it ends up in browser history and server logs.
- **`POST /login`:** from `Auth::routes()` (`web.php:41`), it signs in with a password only, skipping the emailed code.

---

## 1. 🟡 Mobile menu has no "Earn With Us"
*Screenshot "mobile view no earn with us page"*

- **Cause:**
  - "Earn With Us" is not a menu item. It is the desktop button inside `.others-option`
    (`resources/views/layouts/partials/new-header.blade.php:246-261`).
  - The mobile menu is built by the meanmenu plugin from the desktop markup (`public/frontend/newtheme-assets/js/custom.js:5-7`).
  - `responsive.css:1148-1150` hides `.others-option` at ≤991 px, so the copied button never shows.
  - On mobile the page is only reachable from the footer and the home-page teaser.
- **Fix:** add a mobile-only `<li class="nav-item d-lg-none">` with "Earn With Us" (or "Dashboard" when signed in)
  after "Get a Quote", at `new-header.blade.php:237`. Also let the mobile list grow: today it is fixed at 306 px, so a
  sixth item would be clipped (one rule in `hello-theme.css`).
- **CrazyRays branch:** there is no Earn page (`Frontend.earn` route) there, so wrap the item in
  `@if (Route::has('Frontend.earn'))` before merging. The same CSS also hides **Login/Signup** in florida's mobile menu.
- **Size:** small (one file and one CSS rule).

## 2. 🔴 Documents Pending: no form to upload documents
*Screenshots "documents pending status from hr", "documents pending status no document drop down"*

- **What the client sees:** the agent at Documents Pending sees "0/9 required uploaded … upload the required
  documents above", but there is no upload form. After HR switched her to Documents Verification Pending, it worked.
- **Cause:**
  - The agent profile page shows the upload block only when `employee_status_id == 7` (Documents *Verification* Pending): `washinton_hr/resources/views/employee/profile.blade.php:291`.
  - The same check is at `:243` (warning badge), `:285` (locked notice) and `:433` / `:452` (equipment add and remove).
  - The upload endpoint refuses everything except 7 as well: `app/Http/Controllers/Employee/EmployeeDashboardController.php:254`.
  - Documents Pending (14) is the stage *for* uploading. It came with the pipeline on 13 Oct
    (`database/migrations/2026_10_13_140000_add_onboarding_employee_statuses.php`), but these checks were written in
    August, when 7 was the only upload stage.
- **Fix:**
  - One name-based list, "upload stages = Documents Pending + Documents Verification Pending", in
    `app/Models/EmployeeStatus.php`, used by the view and the controller. Names, not ids: ids differ between the Hello
    and CrazyRays databases.
  - Optional: move the agent from 14 to 7 automatically once all required documents are in. HR did this by hand, and
    the "We have received your documents" email already exists for that step.
- **Same stale list elsewhere:**
  - HR's sign-on from the CRM allows only `[1, 7]` (`app/Http/Controllers/Bridge/HrBridgeController.php:410-413`).
  - The CRM's `PENDING_HR_STATUSES = [7, 8]` (`washinton_agent/app/Http/Middleware/AllRoles.php:14`).
- **Size:** small.

## 3. 🔴 Check-in refused: "Check-in time is outside your shift hours"
*Screenshots "checkin issue", "checkin issue 1", "checkin issue 2"*

- **What the client sees:** Anna (New York time zone on her application) has a 09:00–17:00 shift. At 2:01 PM New
  York time (11:01 PM in Pakistan) the portal blocks her with "Please Check In" and refuses the check-in.
- **Cause:**
  - The attendance code already works per agent time zone: `Employee::tz()` (`washinton_hr/app/Models/Employee.php:132-142`), used by `EmployeeAttendanceController.php:146`.
  - But when HR **shortlists** an applicant, `AdminCampaignLeadController::shortlist` (`:141-182`) copies name, email,
    phone, country and so on, and **never copies the time zone** the applicant chose (`cr_applications.time_zone`).
  - `hr_employees.timezone` therefore falls back to its default **Asia/Karachi**
    (`database/migrations/2026_08_02_000001_add_hello_onboarding_columns.php:38`).
  - Her 09:00–17:00 is then checked in Pakistan time and refused (`app/Traits/AttendanceServiceTrait.php:169-171`).
  - The older sign-up path did copy it (`HrBridgeController.php:290-292`). The shortlist path, added 13 Oct, does not, so **every Hello hire since 13 Oct** has the same problem.
  - The daily job also marks them Absent at 18:05 Pakistan time (`app/Console/Commands/MarkDailyAttendance.php`).
  - The CRM's check-in gate reads the same column (`washinton_agent/resources/views/partials/agent_checkin_gate.blade.php:20`), so it has the same problem.
- **Fix:**
  1. Copy the applicant's time zone in `shortlist()` (a few lines).
  2. One-time correction for existing hires, from the time zone on their application:
     ```sql
     -- preview
     SELECT e.id, e.full_name, e.timezone, a.time_zone
     FROM hr_employees e JOIN cr_applications a ON a.id = e.cr_application_id
     WHERE a.brand = 'hello' AND a.time_zone IS NOT NULL AND a.time_zone <> COALESCE(e.timezone, '');
     ```
  3. Add a Time zone field to the admin add and edit subcontractor screens, which have none today.
  4. Optional: send the time zone to the CRM too, so the CRM clock matches.
- **Also worth changing:** the check-in box blocks the **whole portal** outside shift hours, including tickets and payslips, for Pakistan agents too. Block only around the shift window and show a notice otherwise.
- **To fix alongside, because US time zones become real once this is fixed:**
  - Break look-ups compare against Pakistan-time `created_at` (`EmployeeBreakController.php:77-78, 102-104, 158-160`), so a break started after Pakistan midnight can't be ended.
  - Active-time and daily-activity dates use Pakistan dates.
  - The admin attendance list defaults to "today" in Pakistan time.
- **Size:** small for steps 1–3; medium for the follow-ups.

## 4. 🟠 "Subcontractor not found or status unchanged!" when choosing In Progress
*Screenshots "in progress status error", "in progress status error 2"*

- **What happened (live HR log, 19 Oct):**
  ```
  22:33:43  Questionnaire submitted {"employee_id":206,"answers":2,"status":"In Progress"}
  ```
  Submitting the questionnaire moves the agent to In Progress by itself (`QuestionnaireController.php:87-99`).
  The admin's page had been opened before that and still showed Questionnaire Pending. At 22:34 the admin chose
  In Progress. The server found she was **already** In Progress and returned that message
  (`AdminEmployeeController.php:1270-1278`). No data was lost; she later went through the remaining stages to Active
  (22:55).
- **Fix:**
  - If the status is already the chosen one, reply "Already In Progress" and reload the page.
  - Reload after any failed change, so the badge is never stale.
  - Record the automatic move in the status history, which only manual changes write today.
  - Make the message say which case happened.
- **Related, small:**
  - On "Add Subcontractor" the status picked is always overwritten with 7 (`AdminEmployeeController.php:541`, `:564`).
  - The badge colours only cover statuses 1–10, so all pipeline statuses show grey.
- **Size:** small.

## 5. ⚪ Reset all previous data in the CRM and HR portal
*"order ids, admin payment, carrier update, payroll, subcontractors, in-house employees etc."*

### Before anything: decisions needed
1. **Conflict:** on 13 Oct the instruction was *"do not remove any orders, keep it there, on both sides"*. This request removes orders. Please confirm.
2. **Which database:** Hello only? The CrazyRays database (`crazyrayssolutio_crazzy`) still holds a full copy of Hello's history up to 13 Oct, including all 268 orders.
3. **Accounts to keep:**
   - Admin (id 1) and Managers (373, and 416, a CrazyRays manager).
   - HR Executives (417, 428, 442) and other staff roles?
   - The two template accounts new sign-ups copy permissions from must stay: 53 (Dispatcher) and 130 (Order Taker).
   - HR admin test accounts?
4. **Test agents:** remove Sana Faisal, Alishah, Jack, Anna test?
5. **Numbering:** restart order, lead and HR numbers at 1, or continue above the old numbers (recommended, see below)?
6. **Grey-zone data:**
   - old carrier history (`carriers_archive`, 38.7k rows);
   - petty cash;
   - mailbox emails;
   - RingCentral call and SMS logs;
   - storage yards and the carrier directory;
   - blocked phone numbers;
   - website quote leads.
7. **Uploaded files:** delete or archive documents, CVs, NDAs and W-9s? And how long is the encrypted backup kept? It contains card data, see S4.
8. **Timing:** a downtime window.

### What would be cleared (Hello database `hellotransport_databases`, shared by the CRM and HR)
Counts are from the 13 Oct live measurement. A fresh count is taken before anything is deleted.

| Area | Main tables (live rows, 13 Oct) |
|---|---|
| Orders and history | `order` 268, `order_history` 7,166, `report` 993, `report_new` 1,060, `sheet_details` 944, `call_histories` 980, `not_respond_orders` 745, `qa_verify_histories` (~98k, local copy), `order_price_requests`, `authorization_form`, quotes (`shipa_query` 186, `instant_quotes`) |
| Admin and agent payments | `order_payments` 81, `order_payment_logs`, `order_payment_journeys`, `orderpayments`, `creditcards`, `invoices` 5 |
| Carrier updates | `carriers` 84 (approvals live on the order row itself, status 36) |
| Payroll | `hr_payrolls`, `hr_payroll_details` 140, `hr_payslip_items`, `hr_payslip_adjustments`, `hr_gratuity_balances` |
| Subcontractors and in-house staff | `hr_employees` 145, with documents (1,107), bank details, status history, working days, equipment, leaves, questionnaire answers, sign-on tokens; and the agents' CRM `user` rows (150) with their settings, panel access, RingCentral and mailbox accounts, logins, W-9s |
| Attendance and activity | `hr_employee_attendances` 5,352, CRM `attendances` 384, `agent_active_times` 389, breaks, daily activities, logout answers, tickets |
| Campaign leads | `cr_applications` (still holds both brands' rows) |
| Chats and notifications | `flags` (~30k), chat tables, `notifications` 202, `notes` |

**Kept:**
- roles and permissions (CRM and HR);
- all settings, templates and pricing tables;
- the status list `pstatus`, which must not be truncated: its id-0 "NEW" row nearly got lost in the split;
- all HR settings tables;
- reference lists (zip codes, dealer and shipper lists);
- `migrations`.

### Things that would break if done carelessly
- **Restarting numbers at 1 is unsafe.** Customer links carry only the base64 order id (`NewQuote.php:2363-2390`, `:2887`, `:6854`). An old emailed link would open a *new* customer's order and payment page. Upload folders named by id would also attach old files to new records.
  - **Recommendation:** empty the tables, then continue numbering from a round number above the old maximum, e.g. orders from 100001.
- **Linked records:**
  - CRM `user.hr_employee_id` ↔ HR `hr_employees.agent_id`: kept staff are linked, so keep their HR rows or clear the link;
  - `hr_employees.cr_application_id`;
  - `cr_applications.agent_id`;
  - hualt stores Hello order ids (`hualt/app/Http/Controllers/QuoteController.php:79-82`).
- **No undo inside the database.** 199 of 257 tables are MyISAM, where a transaction cannot be rolled back. The only undo is the backup.
- **The old `PRODUCTION_RESET.sql` (28 Jul) must not be run.** It claims nothing is permanent until COMMIT, which is false for MyISAM. It deletes settings (logout and Q&A questions, blocked numbers, storage yards) and admins' mailbox accounts, keeps only roles 1 and 9, and misses tables added since July.
- **Don't run `php artisan hr:fresh`.** It drops every HR table, settings included.

### Procedure (once the answers are in)
1. Put the CRM and HR into maintenance mode and pause the scheduled jobs.
2. Make an encrypted full backup of the database and the upload folders, and check it can be restored.
3. Run a dry-run count report on live and get the client's sign-off.
4. Delete child tables before parent tables, with filtered deletes on `user` to keep the agreed accounts. Set the new numbering.
5. Check for orphans, and that the kept accounts, `pstatus` and settings are intact.
6. Archive the upload folders, remove removed agents' RingCentral tokens, clear sessions.
7. Smoke test: admin sign-in, create an order, HR sign-in, and a new application through to the CRM account.

## 6. 🟡 Real-time notification for new campaign leads in the HR portal

- **Today:**
  - Leads land in `cr_applications`. The CRM writes them; HR reads the same shared database.
  - HR has no alert of any kind: its header bell is commented out (`partials/navbar-header.blade.php:221-320`).
  - Pusher is not configured in either app, and on cPanel shared hosting websockets can't run as a background service.
  - Hello applications also email **no admin**. CrazyRays applications do.
- **Proposed:** copy the CRM's proven pattern (the 20-second `nav-counts` poll):
  - a small `campaign-leads/poll` endpoint returning the pending count and any new leads since the last one seen;
  - a header bell with the count;
  - a toast naming the applicant, linking to their review page;
  - a notification sound;
  - optionally a sidebar badge and a "N new applications — refresh" banner on the list.
- **Files:** only in `washinton_hr`, about 120 lines:
  - routes (the new route must come **before** `campaign-leads/{id}`);
  - permission exclusions;
  - `AdminCampaignLeadController::poll`;
  - the header bell;
  - a new poller partial included in `layout/master`;
  - a copy of the CRM's `notification.mp3`.

  No migration and no new packages; HR deploy only. Optionally also email admins on new Hello applications.
- **Size:** small–medium.

## 7. 🟡 Device restriction: approved, go ahead with the proposal
*"For the device restriction discussion you may proceed with the proposal you shared."*

**Implemented (21 Oct), phase 1: the CRM on Hello and florida.**
- **Where to set it:** the agent's edit screen, under "💻 Device restriction". Only Admins and Managers see it, and
  admin accounts can't be restricted. The same screen lists the agent's devices with Approve,
  Approve & replace, Reject and Revoke.
- **Default:** Off for everyone. Devices are only recorded, so nothing changes until a policy is chosen.
- **Approving:** a new device shows the agent a waiting page with a 6-character code, and it opens by itself once
  approved. Admins and Managers get a 💻 badge in the top bar, plus a sound and a pop-up naming the agent and the
  code. **Device approvals** (`/device-approvals`) lists every waiting device.
- **Revoking:** the device's open page moves to the waiting screen within 20 seconds.
- **Behaviour as proposed:** a waiting device never takes over the agent's session; "view as" and admins are
  never checked; switching an agent to One device keeps their most recently used device.
- **Deploy:** run the migration `2026_10_22_000001_create_user_devices.php` on each site's database. Until it has
  run, the feature stays switched off.
- **Phase 2 (HR agent portal):** not started.

**The proposal:**
- The CRM records every device an agent signs in from.
- The admin sets a policy per agent: **Off** (default), **One device**, or **Approved devices only**.
- A new device asks for admin approval instead of signing straight in.

**What exists today:**
- single-session enforcement and per-user IP lists (`app/Http/Middleware/EnforceUserSecurity.php`);
- a login activity log (`app/Listeners/RecordLoginActivity.php`);
- a 20-second alert poll in the nav.

There is **no** device tracking in any of the three projects.

**Plan:**
- **How a device is recognised:** a long-lived random cookie, of which only a hash is stored. Fingerprinting is
  unreliable on identical call-centre PCs. Browser, OS and IP are shown to the admin as hints only. Clearing cookies
  or using another browser counts as a new device; that is the known trade-off.
- **Data:** `user.device_policy` and a `user_devices` table recording each device with its status: pending, approved, rejected or revoked.
- **Check:** inside the existing `EnforceUserSecurity`, before the single-session rule. Admin and view-as are skipped, as are API, bridge and webhook routes.
  - A pending device must **not** take over the agent's session. Otherwise anyone with the password could kick the agent off their approved PC.
  - Revoking a device signs it out within a minute.
- **Agent side:** a "This device is waiting for approval" page that checks again every 15 seconds.
- **Admin side:**
  - the policy and a device list (approve, reject, revoke, replace) on the agent's edit screen;
  - a Device approvals page;
  - the existing nav alert with sound for new requests.
  - When a policy is switched on, the admin can approve the devices already in use, so nobody is locked out.
- **Portals:** phase 1 is the CRM on Hello and florida (separate databases, so separate approvals). Phase 2, optional, is the HR agent portal, which reads the same tables through the shared database.
- **Do first:** S1–S3 and S5. Several of them would bypass a device check.
- **Size:**
  - CRM: about 4–5 days, including tests on both brands;
  - security fixes S1–S5: about 1 day;
  - HR phase 2: about 2 days.
