# Batch 6 — Plan & Findings (2026-07-09)

> Investigation-first doc (no code changed yet). Each item: **Finding / root cause → Plan → Risk → Open Q**.
> Projects: `washinton_agent` (hello), `washinton_hr` (HR), `crazyrays`.

---

## 0. HR subcontractor creation is blocked (console: "invalid form control … not focusable")
**Finding:** `add_employee.blade.php` submit is a native `<button type="submit">` (line ~688) on a form with **no `novalidate`**. The **Leaves** inputs (`leaves[N][assigned_quota]`, `valid_from`, `valid_to`) carry native HTML `required`. When the wizard advances, non-active steps are `display:none`; on Publish the browser tries to validate those `required` fields, but they're hidden → **"not focusable"** → submit silently blocked. (This is independent of my WFH work; it affects any create.)
**Plan:** Add `novalidate` to the wizard `<form>` (the wizard already does its own per-step JS validation), **or** convert the leaves native `required` into wizard-managed validation. `novalidate` is the minimal, safe fix.
**Risk:** Low. Server validation still enforces required rules.
**Open Q:** none.

---

## A. Soft-hide (hello)

### A1. Port data — soft hide entirely
**Finding:** Port Details modal + its two trigger icons render in `layouts/innerpages.blade.php` + `layouts/mainsite.blade.php` (the `$port1/$port2` block). Batch-5 #7 trimmed it to name+state; now they want it **fully hidden** (icons + modal).
**Plan:** Hide the two bottom-right port icons (the trigger buttons) + the modal via `display:none` (kept in code). Identify the trigger elements (near `data-target="#portModal"` / the icon group at bottom-right).
**Risk:** Low. **Open Q:** confirm — hide only the icons (modal unreachable) is enough? (yes, assumed).

### A2. View-employee role tabs + Job Type: keep only Admin, Manager, QA, Order Taker
**Finding:** `view_register.blade.php` builds role tabs from `@foreach($roles …)` (lines 109 & 126) + separate **No Roles** / **Deleted** buttons. The **Job Type** dropdown in add/edit employee lists all roles. The nav **info icon** is also marked to hide.
**Plan:** Whitelist to `['Admin','Manager','QA','Order Taker']` — wrap the tab button + tab content loops with an `@if in_array($val->name, $whitelist)`, and hide the **No Roles** + **Deleted** buttons. Filter the Job Type `<select>` options the same way (add + edit). Hide the nav info icon (soft-hide CSS).
**Risk:** Medium — must not break the DataTables init (each tab inits a table); hiding a tab's content is fine, but ensure no JS refers to a removed tab id.
**✅ Roles resolved (DB `roles`) — UPDATED 2026-07-09:** KEEP = **Admin(1), Order Taker(2), Dispatcher(3), Manager(9), QA(10)**. *(Dispatcher kept — it's linked to crazy/hello everywhere, do NOT hide.)* HIDE all others: Owes Money(4), Price Checker(5), Code Giver(6), Chat Approver(7), Delivery Boy(8), Trust And Safety(11), Feedback And Review(12), CSR(13), Seller Agent(14), Accountant(15), test(16), H.O.D(17), Data entry(18), Price Giver(19), QA DISPATCH(20), D.BOOKING CHECKER(21), O.T TEAM LEAD(22), Check Price(23), 123(24), + No Roles + Deleted. (Keep is "QA"=10, NOT "QA DISPATCH"=20.)

---

## B. HR portal

### B1. Employees list: DESC by id + show hello (agent) id
**Finding:** The `hr_employees` list (User Management) is a DataTables served by `AdminEmployeeController` (selects `hr_employees.id … agent_id`). Current order looks alphabetical.
**Plan:** `AdminEmployeeController` datatable query is ordered `->orderBy('name')` (line ~273, alphabetical). Change to **`->orderByDesc('hr_employees.id')`** (+ DataTables default `order: [[0,'desc']]`), and add/show the **`agent_id`** (hello/Washington id) column (already selected at line ~84).
**Risk:** Low. **Open Q:** which list exactly — `/admin/subcontractors` and/or `User Management → hr_employees` DataTable (likely same controller).

### B2. Subcontractor portal: "Back to Hello Dashboard" option
**Finding:** The subcontractor portal user dropdown (`partials/navbar-header.blade.php`) has only **My Profile** + **Log Out**. An agent who SSO'd from hello → HR has no way back.
**Plan:** Add a **"Back to Hello Dashboard"** item that SSOs the agent back to hello (reverse of the hello→HR bridge). Needs: a bridge endpoint/route on hello that logs the agent in (by agent_id) and lands on `/dashboard`, and a link in the HR dropdown that opens it. Verify the agent has a hello session or use a signed token.
**Risk:** Medium (cross-portal SSO). **Open Q:** should it open hello in the same tab (switch) or new tab?

### B3. Document Settings: only specific docs Active
**Finding:** `hr.hellotransport.com/admin/document_settings` lists doc types with Active/Inactive status (a `hr_document_settings` table, admin-toggleable).
**Plan:** Set **Active** only for: Resume, CNIC (National ID), Experience Letter, Last/Highest Education Certificate, Utility Bill (Address Confirmation), Father/Mother CNIC — everything else **Inactive**. Do via a data migration/seeder (so prod is consistent) — these are just status flips, admin can still change later.
**✅ Resolved (DB `hr_document_settings`, 12 rows):**
- **Activate (status→1):** 1 CNIC (National ID) *[currently INACTIVE]*, 2 Educational Certificate *(= "last education certificate")*, 3 Experience Letter, 9 Bill *(= "Utility Bill for address")*, 10 CNIC Front, 11 CNIC Back *[currently INACTIVE]*, 12 Resume.
- **Inactivate (status→0):** 4 Passport, 5 Bank Account Details, 6 Medical Certificate, 7 Police Clearance, 8 smart card.
- **CREATE (missing):** **"Father/Mother CNIC"** — no such row exists; must insert a new doc type (file, not required, active).
**Risk:** Low (status flips + 1 insert). **Open Q:** confirm "Bill" is the intended Utility Bill, and confirm creating a single "Father/Mother CNIC" row (vs two separate).

### B4. Gratuity/Leaves optional per agent + review step before Publish
**Finding:** Batch-5 made gratuity/leaves optional **only for Work-From-Home**. Now the client wants an **explicit toggle** ("give gratuity / leaves — yes/no") per agent regardless of shift, plus a **review/summary** shown at the final step (near Publish) listing everything entered, with **"edit this section"** jump-back buttons.
**Plan:**
- Add two toggles (e.g. "Assign Gratuity?", "Assign Leaves?") in the Employment/Leaves steps.
- Client: when a toggle = No → hide + un-require that section (reuse the Batch-5 capture-original toggle).
- Server (`AdminEmployeeController` store+update): make gratuity/leaves optional when the toggle is off (extend the existing `shift===6` condition).
- Add a **Review** panel on the final wizard step summarizing all fields, each group with an "Edit" button that jumps to its step.
**Risk:** Medium-high (wizard + validation + summary wiring). **Open Q:** where should the toggles live (a checkbox per section)? Confirm labels.(also check Crons and payslip and functions where gratuity and leaves are handle so this module does not effect this)

---

## C. Panels → dynamic panel types (BIG)
**Finding:** Panels are **hardcoded integers 1–6** everywhere: `paneltype` on orders, `emp_panel_access` (comma IDs), `penal_type`, and label maps in `edit_employee.blade.php`, `register/index.blade.php`, `return_function*.blade.php`, `customerlist`, nav "Panel N" dropdown, `query/table` badges, etc. No `panel_types` table.
**Desired:** Dynamic, admin-managed panel types with **city names**:
Lahore=1, Islamabad=2, Testing=3 (unchanged), Website Quote=4 (unchanged), Rawalpindi=5, Multan=6, Bahawalpur=7, Jhang=8, Peshawar=9. Admin screen (with permission) to **create/name new panels**. Website + Test panels stay the same. A **seeder** pushes the data; nothing breaks on the website.
**Also:** Hello Transport + AutoHauling quotes should land on **Website Quote (4)**, not ProMax/Panel 2. → **`FrontendController` lines ~214, ~260 set `paneltype = 2`; change to `4`.** (AutohaulQuoteController + InstantQuoteApiController already use 4.)
**Plan (phased):**
But Hello own website quote website was landed on paneltype 2 change it to 4.
1. **Create `panel_types` table** (id, name, is_system, sort, status) + `PanelType` model + **seeder** (ids 1–9 with the city names; mark 3=Testing,4=Website as system/locked).
2. **Admin CRUD screen** (permission-gated) to add/rename panels — under Management or a settings page.
3. **Replace hardcoded labels** with dynamic lookups: the `$options`/label maps + nav "Panel N" dropdown + `return_function*` + badges read from `panel_types`. Keep the numeric `paneltype`/`emp_panel_access` IDs (don't renumber — data safety); only the *display name* becomes dynamic.
4. **Fix FrontendController paneltype 2→4** (hello/autohaul → Website Quote).
5. Rename current labels now (Panel 1→Lahore, 2→Islamabad, 5→Rawalpindi, 6→Multan, +7/8/9) in the views listed above.
**Risk:** HIGH — panels touch orders, permissions, routing, many views. Must not renumber existing `paneltype`/`emp_panel_access` values (would corrupt access + order routing). Seeder must be idempotent.

**Scope confirmed — "Panel N" label strings live in 30+ view files**, e.g.:
`register/edit_employee`, `register/index`, `mainsite_pages/sidebar` + `mainsite_p/sidebar`,
`mainsite_pages/nav` + `mainsite_p/nav`, `layouts/innerpages` + `layouts/mainsite`,
`return_function` + `return_function2`, `role/create` + `role/edit`, `new_quote/index`,
`new/new_edit`, and many `phone_quote/*` badge tables (`query/table`, `ShipperDetails*`,
`usedAndNewCarDealers/*`, `whatsappCallCount`, `question/*`, `query_report/index`).

**Refined phasing:**
- **Phase 1 (now / "for now"):** rename the visible labels to the city names
  (1→Lahore, 2→Islamabad, 3 Testing keep, 4 Website keep, 5→Rawalpindi, 6→Multan) across the
  30+ files — same mechanical approach as the earlier Auction→Panel 1 rename. Add **FrontendController 2→4**.
- **Phase 2 (dynamic):** `panel_types` table + `PanelType` model + idempotent **seeder**
  (ids 1–9, 3/4 = system-locked) + a `panelName($id)` helper; swap the hardcoded label
  strings/ternaries to read from `panel_types`; build the **admin CRUD screen** (permission-gated)
  to add/rename panels. Numeric IDs stay put.

**Open Q:** "Total panels: 7" but **9** city names listed — confirm final count (assume **9**: +Bahawalpur 7, Jhang 8, Peshawar 9). Confirm the admin-create screen location + which permission gates it. Confirm 7/8/9 are *new* panel type ids (safe to add).

---

## C2. Signup defaults (drop reference-user) + city-based panel assignment  *(NEW 2026-07-09)*
**Finding:** New signups (hello + crazy) currently COPY permission/panel columns from **reference users 130 (Order Taker) / 53 (Carrier/Dispatcher)** — constants `AGENT_REFERENCE_USER_ID=130`, `CARRIER_REFERENCE_USER_ID=53`, `PERMISSION_COLUMNS[]` in **4 controllers**: `BridgeAuthController`, `PublicSignupController`, `CrApplicationController`, `EmployeeSyncController`. Once panels become dynamic/city-named, copying a reference user's fixed panel columns will break.
**Plan:**
1. **Stop copying the reference user.** Define **default permission/access arrays** in one place (a config file `config/signup_defaults.php` OR a small DB table `signup_defaults`, admin-editable) — one default set per role (Order Taker, Dispatcher). All 4 controllers use these defaults instead of `User::find(130/53)`.
2. **City-based panel assignment on signup:** determine the user's city from **IP geolocation** (or the **entered signup city**), match it (regex/normalize) to a panel whose name is that city (Lahore/Islamabad/Rawalpindi/Multan/Bahawalpur/Jhang/Peshawar) → assign that panel. **Fallback = Karachi/default panel** when no city matches.
**Risk:** Medium.
**✅ RESOLVED:**
- **Karachi → add as a panel** (own panel type; also the default fallback).
- **Defaults → admin-editable DB table** (`signup_defaults`, per role).
- **IP→city source →** hello already ships **`stevebauman/location` ^6.6 + geoip2** — use `Location::get($request->ip())->cityName` for server-side IP→city at signup (fallback to the entered signup city, then Karachi).
**Open Q (minor):** city→panel matching — normalize + case-insensitive contains match (e.g. "Lahore Cantt" → Lahore); anything unmatched → Karachi panel. OK?

## C3. Panel access columns → dynamic table (architectural, LARGE)  *(NEW 2026-07-09)*
**Finding:** Per-user panel access is stored in **6 fixed `user` columns** — `emp_access_phone`(panel1), `emp_access_web`(panel2), `emp_access_test`(panel3), `panel_type_4`, `panel_type_5`, `panel_type_6` — each a comma list of permission IDs. Plus `emp_panel_access` (assigned panel ids) and `penal_type` (active panel). **These columns are read/written in ~600 places each** (`app/` + views: `check_panel()`, nav `$phoneaccess`, sidebar gating, every quote screen). New panels 7/8/9 have **no columns**.
**Plan (recommended — avoid rewriting 600+ sites):**
1. New tables: **`panel_types`** (C) + **`user_panel_access`** (user_id, panel_type_id, access_ids TEXT).
2. **Seeder/migration** copies existing data → `user_panel_access`: panel1←emp_access_phone, 2←emp_access_web, 3←emp_access_test, 4←panel_type_4, 5←panel_type_5, 6←panel_type_6, for every user. Idempotent.
3. **Compatibility accessors on the `User` model:** make `emp_access_phone`/`emp_access_web`/`…`/`panel_type_6` **Eloquent accessors+mutators** that transparently read/write `user_panel_access` for panels 1–6. → the ~600 existing `$user->emp_access_phone` read sites keep working unchanged, but the source is now the dynamic table. The old physical columns become **unused** (kept as backup, dropped later).
4. New panels (7/8/9/…): accessed via a dynamic helper `$user->panelAccess($panelId)` + a dynamic modal loop in edit_employee (already loops `$modals` — extend to iterate `panel_types` rows).
**Risk:** HIGH — accessor/mutator must exactly mirror current comma-string behavior; the seeder must run before the accessors go live; test `check_panel()`, nav, sidebar, save flows.
**✅ DECIDED (2026-07-09):** use the **accessor-compatibility** approach (keep old column names working via `user_panel_access`); keep physical columns as backup, drop later after verification.

## ✅ DECISIONS LOCKED (2026-07-09)
1. **Panel columns →** accessor-compat (old column names become accessors/mutators over `user_panel_access`; ~600 sites untouched; drop physical cols later).
2. **Karachi →** add **Karachi as a panel** (its own panel type; also the default fallback when no city matches).
3. **Signup defaults →** admin-editable **DB table** (`signup_defaults`, per role Order Taker/Dispatcher).
4. **Concurrent login →** kick the **older** session on new login (single active session).

---

## D. Login activity + IP  *(UPDATED 2026-07-09 — tables confirmed to EXIST in prod)*
**Client confirms:** the tables exist in prod, and **login from CRAZY saves/checks IP (works), but login from HELLO does NOT.**

### D1. Hello login doesn't capture IP (crazy does)
**Verified in code:** hello login form posts to `route('login')` → `Auth::routes()` (web.php:41) → `LoginController@login` (AuthenticatesUsers trait) → `LoginController@authenticated()`, which IS present & correct and calls `UserLoginActivity::record(..., 'hello', ...)`. The `Bridge\BridgeAuthController@login` at web.php:1359 is under the **`bridge` prefix** (`/bridge/login`) so it does **NOT** shadow `/login`.
So the hello path *should* work. Since it doesn't on prod while crazy (explicit `BridgeAuthController` record) does, the cause is one of:
  1. **The `LoginController@authenticated` change isn't actually on prod** (partial deploy — views + BridgeAuthController deployed, LoginController not), and/or **route/config cache** on prod serving the old controller.
  2. The `authenticated()` hook is genuinely not invoked for the deployed login flow.
**Robust fix (path-independent — recommended):** stop relying on the per-controller hook. Add a **`Login` event listener** (`Illuminate\Auth\Events\Login`) registered in `EventServiceProvider`. It fires on **every** successful login (hello trait-login AND crazy `Auth::login`), so it captures uniformly regardless of controller or deploy state. Source = infer from the request route/path (bridge route → `crazyrays`, else `hello`). Remove the duplicate explicit records to avoid double-capture.
**Risk:** Low. **Action also:** redeploy hello + `php artisan optimize:clear` (route/config/view cache).

### D2. IP restriction not enforced on hello + concurrent login on 2 PCs
**Finding:** (a) IP enforcement currently only runs inside `LoginController@authenticated` (same hook that isn't firing/deployed on hello) — so hello logins bypass the IP check while crazy (enforced in `BridgeAuthController`) works. (b) **No single-session enforcement** — Laravel allows concurrent sessions; a 2nd PC login doesn't kick the 1st.
**Robust fix:**
  - **IP enforce → middleware.** Add an `EnforceIpRestriction` middleware on the authenticated `web` group: for a logged-in user with `ip_check_enabled`, compare `$request->ip()` to `allowed_ips`; on mismatch → `Auth::logout()` + redirect to login with the "contact admin" error. This covers **all** login paths AND ongoing sessions (not just the login moment).
  - **Single session.** On login (in the same `Login` event listener), enforce one active session — either `Auth::logoutOtherDevices()` (needs the `AuthenticateSession` middleware) **or** store `user.current_session_id` and invalidate others via the middleware. Recommend: store `current_session_id` on login; middleware logs out any session whose id ≠ the stored one → the **older PC is kicked** on the new login.
**Risk:** Medium — middleware runs on every authenticated request; must be efficient and not lock admins out (exempt the login/logout routes; fail-open if columns missing).
**Open Q:** on 2nd login — **kick the older session** (recommended) or **block the new login**?

---

## Deploy note (not the root cause for D, but still pending)
Tables/columns EXIST on prod (confirmed). But redeploy hello + `php artisan optimize:clear` is still needed so the latest `LoginController`/controllers are live and route/config cache is fresh. Other Batch-4/5 migrations (contract/guides/booking-form) should also be run if not yet.

---

## Suggested execution order
1. **Item 0** (creation blocker — `novalidate`) — quick, high impact.
2. **D1/D2** login capture + IP enforce via **event listener + middleware** (path-independent), + single-session; then redeploy + `optimize:clear`.
3. **A2 roles**, **A1 port hide**, **B3 documents**, **B1 list order**, **FrontendController 2→4** — small/clear.
4. **B2 back-to-hello** — medium.
5. **B4 gratuity/leaves toggle + review step** — medium-high.
6. **C dynamic panels + seeder + admin screen** — the big one, last, carefully.

## Items still needing a DB/runtime read (do before coding)
- Roles list (exact names) — `SELECT id,name FROM roles`.  *(DB tool was intermittently unavailable; grab when back.)*
- `hr_document_settings` rows (to know which titles exist / must be created).
- ~~Confirm web-login controller path~~ ✅ confirmed: hello uses `LoginController` (Auth::routes); bridge is `/bridge/login`.
- Enumerate the two "Panel N" nav dropdowns + all `return_function*` label spots for the dynamic swap (Phase 2).

---

## ✅ B6 IMPLEMENTATION LOG (2026-07-11)

### DONE (in code)
- **#0 Creation blocker** — `add_employee.blade.php` form given `novalidate`.
- **FrontendController paneltype 2→4** — hello website quotes now land on Website (4). *(lines ~214, ~260)*
- **D1/D2 Login IP + single session** — `RecordLoginActivity` listener on `Illuminate\Auth\Events\Login` (captures IP on EVERY login path), `EnforceUserSecurity` middleware (single-session: kicks OLDER session via `user.current_session_id`; + IP enforce; fail-open). Migration `2026_07_10_000001_add_current_session_id_to_user`. Old per-controller hooks removed.
- **A2 roles whitelist** — `['Admin','Order Taker','Dispatcher','Manager','QA']` on view_register tabs+content, edit_employee + register/index Job Type dropdowns.
- **A1 port hide** — `a[data-target="#portmodal"], #portmodal { display:none }` in innerpages + mainsite layouts.
- **B3 documents** — migration `washinton_hr/.../2026_07_11_000001_b6_set_active_document_settings.php`: activate 1,2,3,9,10,11,12; inactivate 4,5,6,7,8; INSERT "Father/Mother CNIC" (file, NOT required, active). Idempotent.
- **B1 HR list** — `AdminEmployeeController@index` datatable already selects agent_id (col "Agent ID"); set DataTable default `order:[[0,'desc']]` (newest id first).
- **B2 Back-to-Hello** — `navbar-header.blade.php` employee dropdown: "Back to Hello Dashboard" link (→ `config('bridge.agent_portal.dashboard_url')`) shown when `session('agent_sso_origin')`.
- **B4 Gratuity/Leaves toggle + Review step** — add_employee wizard: switches "Apply Gratuity" (Step2) + "Assign leaves" (Step3) sending hidden `gratuity_enabled`/`assign_leaves`; unified `applyOnboardingToggles()` composes with WFH + commission-only; leave inputs DISABLED when off (don't submit). New **Review** step (index 6) before Completed with per-section Edit jump buttons (`gotoWizardStep`). Backend `store()`+`update()` honor `$gratuityOff/$leavesOff` (validation + null gratuity when off).

### FLAGGED (needs client screenshot)
- **Nav "info icon"** — could not identify with confidence; only info-like icon in nav is the `fa-question-circle` **Guides** dropdown item (a real feature). NOT hidden — needs the circled screenshot to confirm the exact element.

### Dynamic panels — FOUNDATION built (additive, breaks nothing)
New in washinton_agent (nothing reads them yet):
- Migrations: `2026_07_11_000001_create_panel_types_table`, `_000002_create_user_panel_access_table`, `_000003_create_signup_defaults_table`.
- Model: `app/PanelType.php` (`nameFor($id)` cached lookup + `defaultPanel()`).
- Seeders (`database/seeds/`, need `composer dump-autoload`):
  - `B6PanelTypeSeeder` — ids 1..10 city names (1 Lahore,2 Islamabad,3 Testing*,4 Website*,5 Rawalpindi,6 Multan,7 Bahawalpur,8 Jhang,9 Peshawar,10 Karachi=default). *=system.
  - `B6UserPanelAccessSeeder` — copies 6 legacy cols → user_panel_access for every user (idempotent).
  - `B6SignupDefaultsSeeder` — freezes ref-users 130/53 permission columns into signup_defaults (order_taker/dispatcher).

### Dynamic panels — REMAINING wiring (Phase 2, HIGH risk, NOT yet done)
1. **User model accessor-compat** — make `emp_access_phone/web/test`,`panel_type_4/5/6` accessors+mutators over `user_panel_access`. *(MUST NOT go live until B6UserPanelAccessSeeder has run on prod.)*
2. **Admin CRUD screen** — create/rename panels (permission-gated) under Management.
3. **Signup rewiring** — 4 controllers (BridgeAuthController, PublicSignupController, CrApplicationController, EmployeeSyncController): use `signup_defaults` instead of ref users 130/53; assign panel by IP/entered city (stevebauman/location) → matching panel name, fallback Karachi(10).
4. **Label renames** — swap hardcoded "Panel N" strings → `PanelType::nameFor()` across 30+ views (nav/sidebar ×2, return_function ×2, edit_employee, register/index, role create/edit, phone_quote badges, layouts).

### ⚠️ DEPLOY ORDER (critical)
1. Run all migrations (agent: current_session_id, panel_types, user_panel_access, signup_defaults; hr: document settings).
2. `composer dump-autoload` (agent) so the B6*Seeder classes resolve.
3. `db:seed --class=B6PanelTypeSeeder`, `--class=B6UserPanelAccessSeeder`, `--class=B6SignupDefaultsSeeder`.
4. ONLY THEN deploy the code that turns on the User accessor-compat + signup rewiring.
5. `php artisan optimize:clear` (route/config/view cache) on agent + hr.
6. Deploy hr & agent TOGETHER (bridge SSO + document settings).
