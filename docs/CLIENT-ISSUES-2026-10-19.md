# Hello Transport: client issues, 16–18 Oct 2026

Sources: Allen's emails of 16 Oct (12:55 AM and 11:50 PM), the WhatsApp messages, and all screenshots.
Every item was checked against the live code, the live error logs and the live database (read only).
Nothing has been changed yet.

**Status:** 🔴 blocks testing · 🟠 bug · 🟡 change request · ⚪ needs a decision

## Progress, 19 Oct 2026

| Item | State | Commit |
|---|---|---|
| A1 HR sign-in for pipeline statuses | ✅ fixed | hr `a12625c` |
| A2 CRM password = HR password | ✅ fixed; existing agents need the one-time sync below | hr `a12625c`, crm `8165676` |
| A3 Check-in after the NDA | ✅ resolved by A1 (it came from setting the status to Active early) | — |
| A4 Remove "Forgot Password?" | ✅ | hr `a12625c` |
| B1 CV 404 | ✅ | hr `a12625c` |
| B2 Questionnaire answers for admins | ✅ new card on the subcontractor profile | hr `81919a6` |
| C1 Daily Activities search | ✅ | hr `a12625c` |
| C2 Working Days note | ✅ | hr `a12625c` |
| C3 NDA "State ID" → "ID", closing line | ✅ (closing line uses "I have read all the clauses…", to be confirmed by the client) | hr `81919a6` |
| C4 Questionnaire page crash | ✅ | hr `a12625c` |
| D1 Applicant confirmation email | ✅ new "Application received" email | crm `fc13fa2` |
| E1 Blog Posts 500 | ✅ | crm `8165676` |
| E2 Campaign Users and documents icons | ✅ removed on Hello, kept on CrazyRays; "normal view employee" still to be confirmed | crm `fc13fa2` |
| E3 Verification page theme | ✅ | crm `fc13fa2` |
| E4 Delete previous data | ⚪ waiting for the scope | — |
| F1 CrazyRays chat blinking | ✅ cause: the mark-read address rejected the window's GET calls (405) | crm `e6ec68c`, crazyrays `c169506` |
| G Screenshot upload crash | ✅ wrong sign-in guard | hr `24f6a7c` |
| H "Washington version update" | ⚪ waiting for details | — |

---

## A. Agent sign-in and statuses (fix first: these block the client's testing)

### A1 🔴 HR portal won't sign in agents at NDA Pending, Questionnaire Pending or Documents Pending
*Email 1, HR #4 · WhatsApp 1 and 2 · screenshot "status is shortlist and nda pending still unable to login"*

- **What the client sees:** "Login Failed: Your account is not active. Please contact HR." Sign-in only works after
  switching the status to Documents Verification Pending.
- **Cause:** the HR sign-in screen still only allows **Active (1)** and **Documents Verification Pending (7)**:
  `washinton_hr/app/Http/Controllers/Employee/EmployeeAuthController.php:67`.
  The new pipeline statuses (NDA Pending 11, Questionnaire Pending 12, In Progress 13, Documents Pending 14,
  Onboarding 15, Pending Contract 8, Management Approval Pending 9) were added to the *page* rule
  (`EmployeeAuthMiddleware`) but never to this *sign-in* check, so the agent is stopped at the door.
- **Fix:** let every pipeline status sign in (using the same list as the middleware). In Progress and Management
  Approval Pending then land on the "account on hold" screen, as designed.
- **Size:** small.

### A2 🔴 Agent can't sign in to the CRM after HR creates the account
*Email 1, HR #6 · WhatsApp 3 · screenshots "Agent is active can't login through login", "login issue"*

- **What the client sees:** Sana Faisal is Active in HR and in the CRM, but CRM sign-in says
  "The email or the password is invalid… or user is not active".
- **Evidence:** CRM user #591 has status 1, verify 1, not deleted and not frozen, so the account itself is fine.
- **Cause:** when HR creates the CRM account, the CRM sets a **random 12-character password that nobody ever sees**
  (`washinton_agent/app/Http/Controllers/Bridge/EmployeeSyncController.php:233` and `:241`). The "account ready"
  email tells the agent to *"Sign in with the same email address you used for your HR portal"*
  (`washinton_hr/app/Support/AgentStageNotifier.php:95-97`), so they try their HR password and it fails.
  This affects every agent created through the pipeline (#591 Sana, #592 Jack).
- **Fix:** give the CRM account the same password as the agent's HR account when it is created (send the HR
  password hash over the secure bridge), and copy it across again if HR changes it. Existing agents #591 and #592
  get their HR password copied once.
- **Size:** small to medium (both repos).

### A3 🟠 "Please Check In" appears right after signing the NDA
*Email 1, HR #5 · screenshot "check in right after signing nda form"*

- **Cause:** the check-in popup only shows for **Active** agents (`washinton_hr/resources/views/partials/checkin_gate.blade.php:9`).
  The status history shows Alishah (#202) was set to **Active** at 01:25:19, the same second her CRM account was
  created, which was presumably done to get past A1. An Active agent must check in, so the popup appeared.
- **Fix:** A1 removes the need to set Active early. As a safeguard, the popup will also stay hidden until the
  agent has finished onboarding.
- **Size:** small (comes with A1).

### A4 🟡 Remove "Forgot Password?" from the HR agent sign-in
*Email 2, #3 · screenshot "hr signin forget password isn't working"*

- **Cause:** the link points nowhere (`href="javascript:void(0)"`): `washinton_hr/resources/views/employee/auth/login.blade.php:97`.
- **Fix:** remove it. Only admins change passwords.
- **Size:** tiny.

---

## B. HR portal: admin

### B1 🟠 CV "Download" gives 404
*Email 1, HR #1 · screenshots "cv hr error 1 and 2"*

- **Cause:** CVs are saved on the **website's** server (hellotransport.com, confirmed in
  `public_html/storage/app/public/applications/resumes/`), but the HR link uses the **HR** server's address:
  `washinton_hr/resources/views/admin/campaign_leads/show.blade.php:55` builds `asset('storage/…')`, which points to
  hr.hellotransport.com.
- **Fix:** point the link at hellotransport.com, where the file lives.
- **Size:** tiny.

### B2 🟡 Where are submitted questionnaires? (WhatsApp)

- **Finding:** admins can only add and edit the *questions* (Settings → Onboarding Questionnaire). **No admin screen
  shows an agent's answers.**
- **Fix:** add a "Questionnaire answers" section to the subcontractor profile, plus a "View answers" link from
  Campaign Leads.
- **Size:** small to medium.

---

## C. HR portal: agent

### C1 🟠 Daily Activities search shows "DataTables warning … Ajax error"
*Email 2, #2*

- **Cause (live log, 16 Oct 22:49):** `Method Yajra\DataTables\CollectionDataTable::where does not exist` at
  `washinton_hr/app/Http/Controllers/Employee/EmployeeDailyActivityController.php:54`. The search filter uses database
  syntax on a list that is already loaded.
- **Fix:** filter the loaded list instead.
- **Size:** tiny.

### C2 🟠 Working Days note always says "6 days a week"
*Email 2, #1 · screenshot "5 working days … role requires 6 days"*

- **Cause:** the note is fixed text: `washinton_hr/resources/views/employee/profile.blade.php:148`.
- **Fix:** show the number of days the admin actually selected, and list them (for example "5 days a week, Mon–Fri").
- **Size:** tiny.

### C3 🟡 NDA: remove "State" from the ID labels and change the closing sentence
*Email 1, HR #2 · screenshot "nda changes"*

- **Where:** "State ID Number", "State ID Front" and "State ID Back" in `washinton_hr/resources/views/nda/modal.blade.php:21`
  and in the signed PDF (`nda/pdf.blade.php:66, 82, 88`). The closing sentence is at `nda/modal.blade.php:199`
  ("I have read and understood all 26 clauses…").
- **Fix:** use "ID Number / ID Front / ID Back" (worldwide agents). Change the closing sentence to the client's wording,
  which starts *"I have read all the clauses…"*. **The full sentence is needed from the client.**
- **Size:** tiny.

### C4 🟠 (found in the log) Questionnaire page crashes after the agent submits it

- **Cause (live log, 15 Oct 01:35):** `Call to a member function format() on string` at
  `washinton_hr/resources/views/employee/questionnaire.blade.php:31`. The `questionnaire_submitted_at` field is not
  treated as a date by the Employee model.
- **Fix:** treat it as a date.
- **Size:** tiny.

---

## D. Emails

### D1 🟡 Applicant confirmation email is the old "account created" email
*Email 1, HR #3 · screenshot "need to change the format and wordings of application received email"*

- **Cause:** `/register` still sends `WelcomeEmail` (`washinton_agent/app/Http/Controllers/HelloApplicationController.php:142`,
  template `resources/views/emails/welcome.blade.php`). It says "Your account has been successfully created" and
  "Account Pending Admin Approval", and has a **GO TO LOGIN** button. None of that is true any more, because applying
  no longer creates an account.
- **Fix:** a new "Application received" email in the new theme, using the same wording as the website's
  "Application Submitted" screen, explaining what happens next, and with **no login button**.
- **Size:** small.

---

## E. CRM (hellotransport.com)

### E1 🔴 Blog Posts admin page gives 500
*Email 1, CRM #1 · screenshot "blog post"*

- **Cause (live log):** `Call to undefined function check_panel()` in `partials/mainsite_pages/sidebar.blade.php`.
  The Blog Posts pages use the admin layout but don't load the helper file that every admin page must include
  (`partials.mainsite_pages.return_function`). Same known issue as the Access Guide page (fixed earlier in 92df75f).
- **Fix:** add the include to `resources/views/main/blog_admin/index.blade.php` and `form.blade.php`.
- **Size:** tiny.

### E2 🟡 Remove "Campaign Users" and the agents' documents dropdown from admin and manager
*Email 1, CRM #3*

- **Where:**
  - The **Campaign Users** icon (permission 166): `resources/views/partials/mainsite_pages/nav.blade.php:313-322`
    and `mainsite_p/nav.blade.php:308`.
  - The **Subcontractor Documents** dropdown and its live polling and notifications:
    `mainsite_pages/nav.blade.php:295-311` and the JS at about `:1403-1440` (same in `mainsite_p`).
- **Fix:** remove both on Hello (the HR portal now manages leads and documents). The CrazyRays branch keeps them.
  Return View Employees to a plain list.
- **Size:** small.
- **⚪ To confirm:** "show normal view employee" means View Employees **without** the HR-status, document and pipeline
  extras. Please confirm.

### E3 🟡 Verification (login code) page still uses the old dark theme
*Email 1, CRM #4*

- **Where:** `resources/views/main/verify/index.blade.php` still uses the old `frontend-master` layout with black and gold.
- **Fix:** rebuild it in the new website theme, matching the new sign-in page.
- **Size:** small.

### E4 ⚪ Remove all previous CRM and HR data
*Email 1, CRM #2: "order ids, admin payment, carrier update, payroll, subcontractors, in-house employees etc."*

- **What it means:** permanent deletion on the live Hello database (`hellotransport_databases`). This cannot be undone.
- **Decisions needed before anything is deleted:**
  1. The exact list of what goes (orders and quotes, admin and agent payments, carrier updates, payroll and payslips,
     subcontractors, in-house employees, attendance, breaks, tickets, campaign leads and applications, chats?).
  2. What stays: **admin and manager accounts** (as agreed during the split), settings, roles, panels, document
     settings, questionnaire questions, blog posts.
  3. Whether the current **test agents** (Sana Faisal, Alishah, Jack) should also go.
  4. Whether order and lead numbering should restart.
- **How it would be done:** a full backup to your computer first (as with the split), then deletion of the agreed
  tables only, then a check.
- **Size:** medium, once the scope is agreed.

---

## F. CrazyRays (florida)

### F1 🟠 Live chat keeps blinking and shows unread after replying
*WhatsApp, screenshot "Crazyrays chat issue"*

- **Cause (to confirm on florida):** the unread count is the visitor's messages with `read_it = 0`
  (`app/Http/Controllers/WashingtonChatController.php:186-187`). Replying doesn't mark those messages as read. They are
  only marked when the thread is opened while the chat box is visible, and the marking uses `type = 1` only when the
  window was opened as "admin" (`resources/views/iframe/dashboard.blade.php:687-689`). Opened any other way, it marks
  the wrong column (`read_it_c`), so the count never clears and the tab keeps blinking.
- **Fix:** mark the thread as read whenever staff open it or reply, always on the staff side.
- **Size:** small (crazyrays branch).

---

## G. Other errors found in the logs (not reported by the client)

| Where | Error | Effect | Size |
|---|---|---|---|
| HR `ScreenshotController.php:43` | `Attempt to read property "id" on null`, 13 times on 16 Oct | The agent screenshot upload fails after their session expires; harmless but noisy | tiny |

---

## H. Open question

- **"What's the update on washington version update?"** (WhatsApp). The scope isn't defined in any request I have.
  Please share what this refers to.

---

## Suggested order and estimate

| Order | Items | Estimate |
|---|---|---|
| 1, today | A1, A2, A3, A4, E1, C1, C4, B1 | about 1 working day, including a full test from apply to CRM sign-in |
| 2 | C2, C3, D1, E2, E3, B2 | about 1–1.5 days |
| 3 | F1 (CrazyRays chat) | about half a day |
| 4 | E4 data cleanup | half a day after the scope is confirmed, with a backup first |

**Needed from the client:** the full NDA closing sentence (C3), the scope of the data cleanup (E4), confirmation of what
"normal view employee" means (E2), and what the "washington version update" is (H).
