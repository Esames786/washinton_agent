# Hello Transport — Reopen Signup/Login + US-Agent Onboarding + Per-User Timezone
**Plan / work inventory — gathered from git history + current code. Date: 2026-08-02**

---

## 0. Background (what git shows today)

The 4 portals share ONE database (same VPS, different cPanel accounts):
`florida.crazyrayssolutions.com.pk` (agent), `hr.crazyrayssolutions.com.pk` (HR),
`hellotransport.com` (marketing + agent), `hr.hellotransport.com` (HR).

During the CrazyRays migration we **cut Hello's public signup and login off**:

| What was cut | Where | How |
|---|---|---|
| Hello signup form | `PublicSignupController@showForm` | redirects away to `crazyrayssolutions.com.pk` ("Public self-service signup is handled on CrazyRays now") |
| Hello login | `WelcomeController@loginn` (line ~76) | when `!config('app.is_agent_portal')` → redirects away to `agent_portal_url` (florida) |
| Marketing → portal links | branding commits (`1fd00e5`, `73b362c`) | signup/login entry points pointed at CrazyRays |

The **backend** `PublicSignupController@store` (POST /register) still exists and works,
but is **outdated** vs. the CR-application→subcontractor conversion flow
(`CrApplicationController@approve`), which has since gained more backend steps.

Existing building blocks we will reuse:
- `user.is_crazyrays` flag (1 = CrazyRays origin; Hello users = 0) — already read by
  HR `Employee::isCrazyrays()` and `employee-sidebar` for branding.
- `App\Support\Brand` (hellotransport / crazyrays) + `Brand::applyTokens()` for T&C/NDA.
- NDA/Contract rich-text editing from admin (florida Employee Review + HR profile) — **already built**; brand-token default means Hello agents automatically get Hello-worded NDA/contract. (Requirement #6 = no new work, verify only.)
- `SignupProvisioner::applyNoAccess()` — zero-access default for new signups.
- `hr_document_settings.condition` column (used for own/rent docs) — pattern for brand-conditional docs.

---

## 1. Reopen Hello signup + login (requirement #1)

**Goal:** hellotransport.com gets its own working signup + login again. Hello users land
directly in the `user` table (no CR application step).

Work:
1. `WelcomeController@loginn` — remove/condition the "redirect away to florida" so Hello serves its own login page again. (Keep florida behavior unchanged.)
2. `PublicSignupController@showForm` — serve `auth/register.blade.php` again on Hello (keep the redirect-to-CR **only** on the florida deployment, keyed off `PORTAL_BRAND`/`is_agent_portal`, not globally).
3. Marketing site header/hero: restore Sign Up / Login links (Hello pages only).
4. Logout on Hello: must return to Hello login (verify `WelcomeController@logout` doesn't send Hello users to crazyrays — only `is_crazyrays` users go there).
5. `.env` note (hello): `IS_AGENT_PORTAL` stays unset/false; nothing else needed.

## 2. Bring Hello signup **backend** to parity with CR→subcontractor conversion (requirement: "everything should be updated")

`CrApplicationController@approve` currently does MORE than `PublicSignupController@store`.
Parity checklist (add to Hello signup store, adapted):

| Step | CR approve has | Hello signup store today | Action |
|---|---|---|---|
| Zero-access provisioning | ✅ `applyNoAccess` | ✅ | none |
| `is_crazyrays` flag | ✅ sets 1 | ❌ (defaults 0) | explicitly set 0 (Hello) |
| HR mirror (`hrBridge->createEmployee`) | ✅ with `account_type` map (commission_only=2 …) + `contract_accepted_at` | ✅ basic fields only | pass account/pay type, T&C acceptance timestamp, **timezone**, brand |
| Document transfer to HR (`attachDocuments`, doc-setting IDs) | ✅ | ❌ (no docs at signup) | add doc uploads at signup + transfer (see §4) |
| T&C acceptance recorded | ✅ (`contract_accepted_at`) | ❌ | add checkbox + timestamp (see §6) |
| Approval/rejection emails (branded) | ✅ | welcome email only | keep welcome mail from Hello mailer; on admin activation the existing brand-aware `AgentActivatedEmail` already handles Hello |
| Commission defaults | ✅ | ❌ | Pay type = **Commission** fixed (see §3) |

**Rule going forward:** any future backend added to CR-approve must be added here too — note this in both controllers as a cross-reference comment.

## 3. New Hello signup form fields (requirement #2)

Rework `auth/register.blade.php` + `store()` validation + HR mirror. Target field set:

| Field | Notes / change from current form |
|---|---|
| Full name | keep (currently first + last name — combine or keep split, submit as full name) |
| Mother/Father name | **optional** (father_name exists; add mother_name — column exists on hr_employees) |
| **State ID** | replaces CNIC for Hello (store in `cnic` column or new `state_id` — decision below) |
| Contact number **with US code** | phone prefixed +1, digits validation (like CR form's dial-code UI) |
| Full address | street address → city → state → **zipcode** entry pattern |
| City / State / **Zipcode** | zipcode = new input (new col on hr_employees if missing) |
| Shift Type | **"Morning (10am – 5pm)"** — new/updated `hr_shift_types` row with 10:00–17:00; Hello signup shows only this |
| Pay type | fixed **Commission** (account_type_id = commission_only) |
| **Timezone** | asked when country selected — see §8 |
| Password + confirm | keep |
| Remove for Hello | gender/marital/CNIC-format/campaign (CR-specific) — confirm with client |

## 4. Hello document requirements (requirement #3)

At signup (and visible in HR docs system):
1. **Resume — Required** (setting id 12 exists)
2. **Experience (letter) — Optional** (setting id 3 exists)
3. **State ID — Required** (new `hr_document_settings` row)

Work:
- Add brand-conditional documents: extend the `condition` pattern (own/rent) with a brand dimension, or add new settings rows (`condition = 'hello'` / `'crazyrays'`) so each brand's gate/profile counts only its own required docs.
- Upload UI on the Hello signup form (or immediately after first login via the existing verification gate) → files into `hr_employee_documents` (like CR doc transfer).
- Verification gate (`account_verification_gate.blade.php`) + HR profile counting must respect brand-conditional requireds (they currently filter by ownership `condition` — extend the same filter).

## 5. W9 form step (requirement #4)

New feature: after the signup fields/documents, applicant fills a **W9 form online**; we receive it as a submitted form.
- Build a fillable W9 page (fields of IRS W-9: name, business name, tax classification, address, SSN/EIN, certification signature) as the last signup step (or first-login step inside the verification gate — recommended, so signup stays short).
- Store submissions: new table `w9_forms` (user_id, all fields, signature data-URL, IP, timestamps) + generate PDF (dompdf, same pattern as NDA).
- Admin/HR visibility: card on florida Employee Review modal + HR subcontractor show ("W9: Submitted ✓ / Pending", view + download PDF) — Hello agents only.

## 6. Terms & Conditions of Hello Transport (requirement #5)

- Signup T&C block loads the default contract with **Hello** branding — `Brand::applyTokens($tpl, Brand::byKey('hellotransport'))` (mirror of `publicDefaultContract()` which forces crazyrays).
- Record `contract_accepted_at` at signup → passed in HR mirror (parity with CR flow, prevents the blocking contract modal).

## 7. NDA & Contract for Hello agents (requirement #6)

The **editors are already built** (rich-text NDA + Contract on florida Employee Review and HR profile;
admin writes/edits and assigns them).

⚠️ **But branding is NOT correct for Hello agents yet** — see §12.1 bugs **B1–B4**. Today a Hello agent
would be shown a *Crazy Rays*-worded NDA/contract (HR hardcodes the name; florida's `force` brand overrides
the per-user brand). So this item is **not** verify-only:
1. Fix B1–B4 (§12.2).
2. Then verify end-to-end for a Hello agent: editor default → sign modal → PDF → HR profile → print summary
   all say **Hello Transport**.

## 8. Hello vs Crazy differentiation everywhere (requirement #7)

Source of truth: `user.is_crazyrays` (0 = Hello, 1 = Crazy). Add a visible **brand badge/filter**:

| Screen | Work |
|---|---|
| HR subcontractors DataTable (`admin/subcontractors`) | badge column (🟡 CR / 🔵 Hello) + filter dropdown |
| HR subcontractor show/profile + print summary | brand chip in header |
| florida `view_subcontractor` (view_register list + Employee Review modal) | badge in table row + modal header |
| florida CRM/CR-applications area | already CR-only; no change |
| Anywhere agents are listed in HR (attendance, breaks, payroll lists) | optional badge — confirm scope |

## 9. Per-user timezone (requirement 3rd)

**Rule:** CR agents (Pakistan) stay `Asia/Karachi` everywhere (unchanged). Hello agents pick a timezone at signup (with country); that timezone drives **their** clock, check-in/checkout, breaks, attendance marking, and displayed times in BOTH portals.

Work:
1. Schema: `timezone` VARCHAR on `user` + `hr_employees` (default `Asia/Karachi`); signup saves it; editable in HR profile + florida Edit HR Profile.
2. Helper: `Employee::tz()` / `User::tz()` → returns stored tz or `Asia/Karachi`; use `now($emp->tz())` etc.
3. Replace hardcoded `Asia/Karachi` with the helper — inventory:
   - **washinton_hr: 22 occurrences** — `MarkDailyAttendance` (cron: per-employee day boundaries!), `EmployeeAttendanceController` (check-in/out), `EmployeeBreakController` (start/end/duration/date), dashboards/lists.
   - **washinton_agent: 6 occurrences** — `Console/Kernel.php`, `Inactivity.php` (+ any display).
   - `config/app.php` `'timezone' => 'Asia/Karachi'` stays (server default); per-user tz applied at usage points.
4. Attendance cron: `MarkDailyAttendance` must evaluate "today / shift window / late" **per employee timezone** — the biggest single piece of this section.
5. Display: HR + agent portal header clocks ("12:39 pm PKT") and date columns render in the logged-in employee's tz (label from tz abbreviation, not hardcoded "PKT").
6. Shift times (e.g., Morning 10–5) interpreted in the employee's own tz.

## 10. Deploy / ops notes

- All 4 sites share the DB — schema changes (timezone cols, State ID doc row, w9_forms, shift row) are **one-time on the shared DB**, but code must deploy to **hello + florida + both HRs together** (hello & hr.hellotransport are currently running older builds — they must be pulled up to current before this feature, since they share tables the new code writes).
- Hello `.env`: mail block already Hello; no CUSTOMER_BASE_URL needed; set `APP_DEBUG=false`
  (currently `true` on hellotransport.com).
- ⚠️ **`MAIL_ENCRYPTION=tls` with `MAIL_PORT=465` in ALL FOUR `.env` files** — 465 is implicit **SSL**
  (587 is TLS). This is already producing live failures: florida logs
  `550 "No Such User Here"` on RCPT TO, i.e. the SMTP session isn't authenticating so the server refuses
  to relay to outside addresses. Fix: `MAIL_ENCRYPTION=ssl` (keep port 465) on hellotransport.com,
  hr.hellotransport.com, florida and hr.crazyrays — same for `CR_MAIL_ENCRYPTION`. Also quote the password
  (`MAIL_PASSWORD="[cCj3THz8iua"` — it starts with `[`). Then `php artisan config:clear`.
- Verify `storage:link`/upload dirs on the Hello accounts for signup docs + W9 PDFs.

---

## 11. Email origin must follow the DOMAIN (new requirement)

**Rule:** the sending domain decides the sender. Confirmed matrix:

| Deployment | Customer email (orders, quotes, tracking, invoices, auth form) | Agent / recruitment / OTP / HR email |
|---|---|---|
| **hellotransport.com** + **hr.hellotransport.com** | **Hello** ✅ | **Hello** ✅ (everything is Hello on Hello domains — no CrazyRays sender at all) |
| **florida.crazyrayssolutions.com.pk** + **hr.crazyrayssolutions.com.pk** | **Hello** ✅ *(already built — keep as-is)* | **CrazyRays** ✅ *(already built — keep as-is)* |

So florida's behaviour is **already correct and unchanged**: customers get Hello, agents get CrazyRays
(`emails/layouts/app.blade.php` Hello override + per-email overrides + `customer_url()` for links, and
`Brand::mailer()` routing recruitment mail to the `crazyrays` mailer).
The **new** work is only for the Hello domains: nothing there may send as CrazyRays.

Work:
1. Hello `.env` (both hello sites): default mailer = Hello (already set). The `CR_MAIL_*` block on
   hellotransport.com should be **removed or left unused** — nothing on the Hello domain should send as CrazyRays.
2. `Brand::mailer()` / `mailFrom()` currently key off the **user's** brand. Add a guard so that when the
   deployment is a Hello domain (`PORTAL_BRAND` unset / `brands.force` empty), the CrazyRays mailer is
   never selected — recruitment mail on Hello goes out as Hello.
3. `hr.hellotransport.com` `.env`: mail is already Hello ✅. **Fix `MAIL_ENCRYPTION`** (see §10 — all four
   `.env` files use `tls` on port 465, which is wrong and is already causing live `550` send failures).
4. Audit senders: `SendCodeMail` (OTP), `AgentActivatedEmail`, `AgentActionRequiredMail`,
   `WelcomeEmail`, `hr_activated_agent` — all must resolve to Hello on Hello domains.

## 12. Brand must follow the DOMAIN — remove every hardcoded "Crazy Rays" (new requirement)

Verified against the brand commits (`1fd00e5` "branding work florida", `9cbe766`, `7f46336`,
`a1d5818`, `fb19ee9` in agent; `eef4ffb`, `dc1b6fc`, `2b2920e`, `50ed657` "brand CrazyRays for
is_crazyrays subcontractors" in HR).

### 12.1 Four concrete bugs found (must fix for Hello reopen)

| # | Bug | Where | Effect on Hello users |
|---|---|---|---|
| **B1** | `brands.force` (PORTAL_BRAND=crazyrays) **overrides per-user brand** — `Brand::for($helloUser)` returns CrazyRays on florida | `app/Support/Brand.php` `for()` | A Hello agent managed from florida gets a **CrazyRays** contract/NDA default, CR emails, CR logos |
| **B2** | `applyTokens()` is **one-way**: replaces `Hello Transport → brand`, but never `Crazy Rays → brand` | `Brand.php:118-119` | Content already saved with CR literals never converts back for a Hello user |
| **B3** | HR hardcodes the company name when rendering the NDA default | `washinton_hr/.../EmployeeNdaController.php:46`, `AdminEmployeeController.php:1395` — `str_ireplace([...], 'Crazy Rays Solutions', $tpl)` | **Every** HR NDA says "Crazy Rays Solutions", even for Hello subcontractors |
| **B4** | **HR has no Brand helper at all** (`no app/Support/Brand.php`, `no config/brands.php`) — it only has ad-hoc `is_crazyrays` checks | washinton_hr | No single source of truth; branding leaks in both directions |

### 12.2 Fixes — ✅ IMPLEMENTED 2026-08-02 (steps 1 & 2 of the build order)

**Done:**
- **B1** — `Brand::for()` now returns the **person's** brand (PORTAL_BRAND no longer overrides it);
  `Brand::current()` keeps returning the **domain** brand for portal chrome. All 14 `for()` call sites are
  person-scoped (NDA/contract defaults + PDFs, OTP, activation, action-required mail) — chrome uses `current()`.
- **B2** — `applyTokens()` rewrites literals **both ways** in a single case-insensitive pass
  (verified: CR→Hello and Hello→CR, no "Solutions Solutions" double-replace).
- **B4** — `App\Support\Brand` + `config/brands.php` ported into **washinton_hr** (same contract, resolves
  from `Employee::isCrazyrays()`; `Auth::guard('employee')` for chrome).
- **B3** — every hardcoded "Crazy Rays" removed from NDA paths in both apps:
  HR `EmployeeNdaController` + `AdminEmployeeController::defaultNda()` (now takes `?employee=` and brands
  per person), HR + agent `nda/modal.blade.php` (logo alt + footer block), HR + agent `nda/pdf.blade.php`
  (fallbacks now the configured default brand, not CR).
- **Self-healing display** — contract/NDA are re-branded **at render time** for the person everywhere they
  appear: agent contract-block modal, agent Employee Review payload, HR employee contract overlay,
  HR employee profile, HR admin profile (incl. the editor), HR print summary, and at signing time in both apps.
  So copies stored under the wrong brand correct themselves on next view.
- **Email origin (§11)** — `Brand::mailer()`/`mailFrom()` now return the Hello mailer/sender whenever
  `brands.force` is empty (i.e. on the Hello domains), so nothing on hellotransport.com /
  hr.hellotransport.com can send as CrazyRays. florida behaviour unchanged.

**Original fix list (for reference):**

1. **Port `Brand` + `config/brands.php` into washinton_hr** (same shape as agent) so both apps resolve brand identically.
2. **Brand resolution order** (both apps): **per-user `is_crazyrays`** first → then domain default; `force`
   becomes a *fallback for guests only* (login page, public pages), **not** an override for an identified user.
   This is what makes "whichever domain the user is visiting from" work while a Hello agent stays Hello on florida.
3. **`applyTokens()` two-way**: also map `Crazy Rays Solutions` / `CRAZY RAYS SOLUTIONS` → `{brand name}` so
   stored content re-brands in either direction.
4. **Replace hardcoded CR strings** with brand lookups. Inventory (excluding legit CR-only files — CR application
   mails, `CrazyRaysCors`, bridge/config):
   - agent: `resources/views/nda/modal.blade.php` (3), `resources/views/nda/pdf.blade.php` (1)
   - HR: `resources/views/nda/modal.blade.php`, `resources/views/nda/pdf.blade.php`,
     `EmployeeNdaController.php`, `AdminEmployeeController.php`
5. **Templates are already correct** — verified in the live DB:
   `contract_templates` id 1 uses "Hello Transport LLC" literals; `nda_templates` id 1 uses `{{COMPANY_NAME}}`.
   Both re-brand correctly **once B1–B3 are fixed**. No template edits needed.
6. Logos/names/footers on HR (`layout/master`, `navbar-header`, both login pages) already switch per `is_crazyrays`
   (commit `50ed657`) — re-verify after the resolution-order change so Hello users see Hello.

## 13. HR print summary — heading font size (new requirement) — ✅ DONE 2026-08-02

`washinton_hr/resources/views/admin/user_management/employees/print_summary.blade.php`:
`h2 { font-size:14px }` makes the **Contract** and **NDA** section headings look tiny next to the
embedded agreement text (which renders its own large `<h1>`).

Work: raise the section-heading size (≈18–20px, bolder) — either globally for `h2` or specifically for the
Contract/NDA sections — so the headings read as section titles above their content. Check the same on
`profile.blade.php` card headers for consistency.

## 14. Open questions for the client

1. Hello signup: drop gender/marital status entirely, or keep as optional?
2. State ID: store in existing `cnic` column (fast) or new dedicated column (cleaner)?
3. W9 at end of **signup**, or after first login inside the verification gate (recommended)?
4. Should Hello agents skip campaign/WFH concepts entirely (assumed yes)?
5. Timezone list: full IANA list, or US-timezones-only when country = US?
6. Brand badge also on attendance/payroll listings, or only subcontractor screens?

## 15. Suggested build order

| # | Work | Why this order |
|---|---|---|
| 1 | ✅ **DONE** — **§12 Brand-per-domain fixes (B1–B4)** + **§11 email origin** | Foundation — everything Hello-facing is wrong until brand resolves per user/domain. Also fixes existing CR-branding leaks. |
| 2 | ✅ **DONE** (code) — **§13 print-summary font**; ⚠️ `.env` `MAIL_ENCRYPTION` fix still to apply on the servers | Tiny, immediate wins; the mail fix unblocks live sending today. |
| 3 | ⬅️ **NEXT** — **§1 Reopen Hello signup + login** | Small; unblocks all testing of the rest. |
| 4 | **§3 + §6 form fields + Hello T&C**, then **§2 backend parity** | The core signup rework. |
| 5 | **§4 Documents** (brand-conditional settings + gate counting) | Depends on signup + brand. |
| 6 | **§8 Hello vs Crazy badges/filters** | Small, needs `is_crazyrays` already reliable (step 1). |
| 7 | **§9 Per-user timezone** | Largest single piece (22 HR + 6 agent hardcodes + attendance cron). |
| 8 | **§5 W9 form** | Independent; can land last. |
| 9 | **§7 verify** NDA/contract branding end-to-end for a Hello agent | Final QA gate. |
