# Implementation Tasks

## Task Overview

Tasks are ordered so each builds on the previous. Phase 1 sets up both codebases. Phase 2 handles the shared database. Phase 3 adds features to `washinton_agent`. Phase 4 wires up the bridge between the two new portals.

---

## Phase 1: Codebase Initialisation

- [ ] 1. Initialise `washinton_agent` from `washinton_latest`
  - [ ] 1.1 Copy all files from `washinton_latest` into `washinton_agent` (excluding `.git`, `vendor`, `node_modules`, `storage/logs`)
  - [ ] 1.2 Create a fresh `.env` in `washinton_agent` based on `.env.example`, pointing `DB_DATABASE=shiap16_main2`
  - [ ] 1.3 Run `composer install` in `washinton_agent`
  - [ ] 1.4 Run `php artisan key:generate` in `washinton_agent`
  - [ ] 1.5 Verify all existing Washington routes return 200 (smoke test: `/dashboard`, `/loginn`, `/view_query`)

- [ ] 2. Initialise `washinton_hr` from `daydispatchhr`
  - [ ] 2.1 Copy all files from `daydispatchhr` into `washinton_hr` (excluding `.git`, `vendor`, `node_modules`, `storage/logs`)
  - [ ] 2.2 Create a fresh `.env` in `washinton_hr` pointing `DB_DATABASE=shiap16_main2` with the same cPanel DB credentials
  - [ ] 2.3 Run `composer install` in `washinton_hr`
  - [ ] 2.4 Run `php artisan key:generate` in `washinton_hr`

---

## Phase 2: Database — HR Table Prefix

- [ ] 3. Update all `washinton_hr` Eloquent models to use `hr_` prefixed table names
  - [ ] 3.1 Update `Admin` model: `$table = 'hr_admins'`
  - [ ] 3.2 Update `Employee` model: `$table = 'hr_employees'`
  - [ ] 3.3 Update `Department` model: `$table = 'hr_departments'`
  - [ ] 3.4 Update `Designation` model: `$table = 'hr_designations'`
  - [ ] 3.5 Update `Role` model: `$table = 'hr_roles'`
  - [ ] 3.6 Update `Permission` model: `$table = 'hr_permissions'`
  - [ ] 3.7 Update `Payroll` model: `$table = 'hr_payrolls'`
  - [ ] 3.8 Update `PayrollDetail` model: `$table = 'hr_payroll_details'`
  - [ ] 3.9 Update `PayrollStatus` model: `$table = 'hr_payroll_statuses'`
  - [ ] 3.10 Update `EmployeeAttendance` model: `$table = 'hr_employee_attendances'`
  - [ ] 3.11 Update `EmployeeBreak` model: `$table = 'hr_employee_breaks'`
  - [ ] 3.12 Update `EmployeeLeave` model: `$table = 'hr_employee_leaves'`
  - [ ] 3.13 Update `EmployeeTicket` model: `$table = 'hr_employee_tickets'`
  - [ ] 3.14 Update `TicketMessage` model: `$table = 'hr_ticket_messages'`
  - [ ] 3.15 Update `TicketAttachment` model: `$table = 'hr_ticket_attachments'`
  - [ ] 3.16 Update `PettyCashMaster` model: `$table = 'hr_petty_cash_masters'`
  - [ ] 3.17 Update `PettyCashTransaction` model: `$table = 'hr_petty_cash_transactions'`
  - [ ] 3.18 Update `PettyCashHead` model: `$table = 'hr_petty_cash_heads'`
  - [ ] 3.19 Update `ShiftType` model: `$table = 'hr_shift_types'`
  - [ ] 3.20 Update `Holiday` model: `$table = 'hr_holidays'`
  - [ ] 3.21 Update `LeaveType` model: `$table = 'hr_leave_types'`
  - [ ] 3.22 Update `GratuitySetting` model: `$table = 'hr_gratuity_settings'`
  - [ ] 3.23 Update `GratuityBalance` model: `$table = 'hr_gratuity_balances'`
  - [ ] 3.24 Update `GratuityPayout` model: `$table = 'hr_gratuity_payouts'`
  - [ ] 3.25 Update `CommissionSetting` model: `$table = 'hr_commission_settings'`
  - [ ] 3.26 Update `TaxSlabSetting` model: `$table = 'hr_tax_slab_settings'`
  - [ ] 3.27 Update `CurrencyRate` model: `$table = 'hr_currency_rates'`
  - [ ] 3.28 Update `UserScreenshot` model: `$table = 'hr_user_screenshots'`
  - [ ] 3.29 Update all remaining HR models not listed above to use `hr_` prefix
  - [ ] 3.30 Update Spatie permission config in `washinton_hr/config/permission.php` to reference `hr_` prefixed table names

- [ ] 4. Update `washinton_hr` migrations to use `hr_` prefixed table names
  - [ ] 4.1 Update every `Schema::create('table_name', ...)` call in all migration files to use `hr_` prefix
  - [ ] 4.2 Update every `Schema::table('table_name', ...)` call in all migration files to use `hr_` prefix
  - [ ] 4.3 Run `php artisan migrate` in `washinton_hr` to create all `hr_` prefixed tables in `shiap16_main2`
  - [ ] 4.4 Verify all `hr_` tables exist in `shiap16_main2` and HR portal admin login works

---

## Phase 3: `washinton_agent` — Link Washington User to HR Employee

- [ ] 5. Update Edit Employee screen to link Washington user to HR employee
  - [ ] 5.1 Add a "Link to HR Employee" section on `resources/views/main/register/edit_employee.blade.php`
  - [ ] 5.2 Display the Washington `user.id` prominently so the admin knows what value to enter in the HR portal's `hr_employees.agent_id` field
  - [ ] 5.3 Add an "Open HR Portal" button — visible to admins with employee management permission — that calls `GET /hr-portal/{userId}` passing `user.id` as the agent_id in the bridge request
  - [ ] 5.4 No new column is added to the `user` table — `user.id` is the identifier used as `hr_employees.agent_id` in `washinton_hr`
  - [ ] 5.5 In `washinton_hr`, the existing `hr_employees.agent_id` column stores the Washington `user.id` value — confirm this column exists after migration (from `add_column_agent_id_employees` migration)

---

## Phase 4: `washinton_agent` — Public Frontend Pages

- [ ] 6. Create `FrontendController` and public layout
  - [ ] 6.1 Create `app/Http/Controllers/FrontendController.php` with methods: `home`, `aboutUs`, `faq`, `terms`, `carriers`, `brokers`, `blog`, `quoteRequest`, `submitQuoteRequest`, `quoteConfirmation`
  - [ ] 6.2 Create `resources/views/layouts/frontend.blade.php` — public layout (header, nav, footer) adapted from Agent Portal's frontend layout, using Washington's existing CSS/assets
  - [ ] 6.3 Add public frontend routes to `routes/web.php`: `GET /`, `GET /about_us`, `GET /faq`, `GET /terms`, `GET /carriers`, `GET /brokers`, `GET /blog`, `GET /Quote-Request`, `POST /Quote-Request`, `GET /Quote-Request/confirmation`

- [ ] 7. Create public frontend Blade views (converted from Agent Portal Livewire)
  - [ ] 7.1 Create `resources/views/main/frontend/home.blade.php` — home page content from Agent Portal's `Dashboard` Livewire component
  - [ ] 7.2 Create `resources/views/main/frontend/about-us.blade.php`
  - [ ] 7.3 Create `resources/views/main/frontend/faq.blade.php`
  - [ ] 7.4 Create `resources/views/main/frontend/terms.blade.php`
  - [ ] 7.5 Create `resources/views/main/frontend/carriers.blade.php`
  - [ ] 7.6 Create `resources/views/main/frontend/brokers.blade.php`
  - [ ] 7.7 Create `resources/views/main/frontend/blog.blade.php`
  - [ ] 7.8 Create `resources/views/main/frontend/get-quote.blade.php` — Quote Request form (5 fields: name, email, phone, origin, destination)
  - [ ] 7.9 Create `resources/views/main/frontend/quote-confirmation.blade.php` — success page after form submission
  - [ ] 7.10 Copy required public assets (images, CSS, JS) from Agent Portal `public/frontend/` into `washinton_agent/public/frontend/`

- [ ] 8. Implement Quote Request form submission
  - [ ] 8.1 In `FrontendController@submitQuoteRequest`: validate name, email, phone, origin, destination
  - [ ] 8.2 Create a `ShipaQuery` record (or equivalent lead record) so the submission appears in `/view_query`
  - [ ] 8.3 Redirect to `/Quote-Request/confirmation` on success
  - [ ] 8.4 Verify submission appears in the existing `view_query` screen

---

## Phase 5: `washinton_agent` — Public Signup

- [ ] 9. Create `PublicSignupController`
  - [ ] 9.1 Create `app/Http/Controllers/PublicSignupController.php` with `showForm()` and `store(Request $request)` methods
  - [ ] 9.2 Add routes to `routes/web.php`: `GET /register` and `POST /register` (no auth middleware)

- [ ] 10. Create signup Blade view
  - [ ] 10.1 Create `resources/views/auth/register.blade.php` using the Agent Portal's signup form design
  - [ ] 10.2 Form fields: Full Name, Email, Password, Phone Number, Full Address, Signup Type (radio: Agent / Carrier)
  - [ ] 10.3 Remove any dispatch-type dropdown — only Agent and Carrier options

- [ ] 11. Implement signup store logic
  - [ ] 11.1 Validate: `name` required, `email` required|email|unique:user, `password` required|min:8|confirmed, `phone` required, `address` required, `signup_type` required|in:agent,carrier
  - [ ] 11.2 Look up role ID: `role::where('name', 'Order Taker')->first()->id` for agent, `role::where('name', 'Dispatcher')->first()->id` for carrier
  - [ ] 11.3 Load reference user: `User::find(130)` for agent, `User::find(53)` for carrier
  - [ ] 11.4 Create new `User` with: name, email, bcrypt(password), phone, address, role, status=0, and all permission columns copied from reference user (`emp_access_phone`, `emp_access_web`, `emp_access_test`, `panel_type_4`, `panel_type_5`, `panel_type_6`, `emp_panel_access`, `emp_show_data`, `emp_access_ship`, `emp_access_profile`, `emp_access_action`, `emp_access_report`, `emp_access_guide`, `order_taker_quote`, `assign_daily_qoute`, `sheet_access`)
  - [ ] 11.5 Create `user_settings` record: `penal_type = 1` for agent, reference user 53's `penal_type` for carrier
  - [ ] 11.6 Wrap creation in `DB::transaction()` — rollback on any failure
  - [ ] 11.7 Redirect to `/loginn` with success flash message "Account created. Pending admin activation."
  - [ ] 11.8 Verify no activation email is sent

---

## Phase 6: `washinton_agent` — HR Portal Button on Edit Employee

- [ ] 12. Add "Open HR Portal" button to Edit Employee screen
  - [ ] 12.1 Add "Open HR Portal" button to `resources/views/main/register/edit_employee.blade.php` — visible only to admins with employee management Panel_Permission
  - [ ] 12.2 Button links to `GET /hr-portal/{userId}` where `{userId}` is `$data2->id` (the Washington `user.id`)
  - [ ] 12.3 Display the Washington `user.id` value on the screen labelled "Washington User ID (use as Agent ID in HR portal)" so admins know what value to enter when creating/linking an HR employee
  - [ ] 12.4 Display `hr_portal_error` flash message on the page if the bridge call fails

---

## Phase 7: `washinton_agent` — Commission Screen

- [ ] 14. Create `CommissionController`
  - [ ] 14.1 Create `app/Http/Controllers/CommissionController.php` with `index(Request $request)` and `show(Request $request, int $userId)` methods
  - [ ] 14.2 Add routes (inside auth middleware group): `GET /commission` and `GET /commission/{userId}`
  - [ ] 14.3 Add permission check: only users with the appropriate `emp_access_phone` permission can access

- [ ] 15. Implement commission summary query
  - [ ] 15.1 Accept filters: `from_date`, `to_date` (or `month` in Y-m format — convert to date range)
  - [ ] 15.2 Default to current month if no filter provided
  - [ ] 15.3 Execute LEFT JOIN query: `user` → `order` (pstatus=13, date range) → `profit` → `commission_ranges` (profit BETWEEN from_order AND to_order)
  - [ ] 15.4 Return one row per user with: user_id, user_name, completed_orders_count, total_payment, total_profit, total_commission
  - [ ] 15.5 Log `Log::warning()` for any profit value with no matching `commission_ranges` row, include order_id and profit value

- [ ] 16. Create commission Blade views
  - [ ] 16.1 Create `resources/views/main/commission/index.blade.php` — summary table with date range filter, one row per user, all monetary values formatted to 2 decimal places
  - [ ] 16.2 Create `resources/views/main/commission/show.blade.php` — per-order breakdown for a single user (order ID, completion date, payment, profit, commission per order)

- [ ] 17. Add Commission link to Management dropdown in nav
  - [ ] 17.1 Add permission number 161 (`Commission Screen`) to `$options_phone` array in `edit_employee.blade.php`
  - [ ] 17.2 Add `@if (in_array("161", $phoneaccess))` check in `nav.blade.php` Management dropdown linking to `/commission`

---

## Phase 8: `washinton_agent` — Bridge Config and Service

- [ ] 18. Create `config/bridge.php` in `washinton_agent`
  - [ ] 18.1 Create `config/bridge.php` with keys: `hrportal.base_url`, `hrportal.shared_key`, `hrportal.agent_login_endpoint` (`/bridge/agent/login`), `hrportal.agent_status_endpoint` (`/bridge/agent/status`), `washington.shared_key`
  - [ ] 18.2 Add to `.env`: `HRPORTAL_BASE_URL=https://hrwashinton.shawntransport.com`, `HRPORTAL_SHARED_KEY=`, `HRPORTAL_AGENT_LOGIN_ENDPOINT=/bridge/agent/login`, `HRPORTAL_AGENT_STATUS_ENDPOINT=/bridge/agent/status`, `WASHINGTON_BRIDGE_SHARED_KEY=`
  - [ ] 18.3 Add all bridge env vars to `.env.example` with placeholder values

- [ ] 19. Create `HrPortalBridgeService`
  - [ ] 19.1 Create `app/Services/HrPortalBridgeService.php` with `login(int $agentId): array` and `status(int $agentId): array` public methods
  - [ ] 19.2 Implement `protected post(string $endpoint, array $payload, string $fallbackMessage): array` — reads `config('bridge.hrportal.base_url')` and `config('bridge.hrportal.shared_key')`, throws `RuntimeException` if either is blank
  - [ ] 19.3 Set `X-Bridge-Key` header and 20-second timeout on all requests
  - [ ] 19.4 Catch `\Illuminate\Http\Client\ConnectionException` — re-throw as `RuntimeException('HR portal is not reachable right now.')`
  - [ ] 19.5 On HTTP 4xx/5xx — throw `RuntimeException` with message from response `message` field (never expose raw body to user)

- [ ] 20. Create `HrPortalRedirectController`
  - [ ] 20.1 Create `app/Http/Controllers/HrPortalRedirectController.php`
  - [ ] 20.2 Add route (auth middleware): `GET /hr-portal/{userId}` → `HrPortalRedirectController@redirect`
  - [ ] 20.3 Load `User::findOrFail($userId)` — the Washington `user.id` IS the agent_id passed to HR portal
  - [ ] 20.4 Call `HrPortalBridgeService::login($user->id)` — passes `user.id` as `agent_id` in the bridge request
  - [ ] 20.5 On success: `redirect()->away($response['redirect_url'])`
  - [ ] 20.6 On `RuntimeException`: `back()->with('hr_portal_error', $e->getMessage())`
  - [ ] 20.7 Display `hr_portal_error` flash message in `edit_employee.blade.php`

---

## Phase 9: `washinton_agent` — Inbound Bridge Endpoints

- [ ] 21. Create `WashingtonBridgeController`
  - [ ] 21.1 Create `app/Http/Controllers/Bridge/WashingtonBridgeController.php`
  - [ ] 21.2 Add routes (no session middleware — use `api` or bare `Route::post`): `POST /bridge/washington/agent/commission` and `POST /bridge/washington/agent/status`
  - [ ] 21.3 Implement `private validateBridgeKey(Request $request): void` — `abort(401)` if `X-Bridge-Key` header does not match `config('bridge.washington.shared_key')`

- [ ] 22. Implement `commission` endpoint
  - [ ] 22.1 Call `validateBridgeKey()`
  - [ ] 22.2 Validate: `agent_id` required|integer|min:1, `month` nullable|date_format:Y-m
  - [ ] 22.3 Find `User::find($request->agent_id)` — `agent_id` in the request IS the Washington `user.id`. Return 404 JSON if not found
  - [ ] 22.4 Build date range from `month` param (or current month if null)
  - [ ] 22.5 Run commission query using `user.id` as `order.order_taker_id`
  - [ ] 22.6 Return JSON: `agent_id` (= user.id), `user_id`, `user_name`, `period`, `completed_orders_count`, `total_payment`, `total_profit`, `total_commission`

- [ ] 23. Implement `status` endpoint
  - [ ] 23.1 Call `validateBridgeKey()`
  - [ ] 23.2 Validate: `agent_id` required|integer|min:1
  - [ ] 23.3 Find `User::find($request->agent_id)` — `agent_id` IS the Washington `user.id`
  - [ ] 23.4 Return JSON: `linked` (true if user found), `active` (`status == 1`), `user_id`, `user_name`, `message`
  - [ ] 23.5 If not found: return `linked: false`, `active: false`, `user_id: null`, `user_name: null`

---

## Phase 10: `washinton_hr` — Bridge Key Update

- [ ] 24. Update `washinton_hr` bridge configuration
  - [ ] 24.1 Update `washinton_hr/config/bridge.php` `shared_key` to read from `HR_BRIDGE_SHARED_KEY` env var (separate from old portal's key)
  - [ ] 24.2 Add `HR_BRIDGE_SHARED_KEY=` to `washinton_hr/.env` with a newly generated key (must match `HRPORTAL_SHARED_KEY` in `washinton_agent/.env`)
  - [ ] 24.3 Add `HR_BRIDGE_SHARED_KEY=` placeholder to `washinton_hr/.env.example`
  - [ ] 24.4 Verify `washinton_hr` bridge endpoints (`/bridge/agent/login`, `/bridge/agent/status`) respond correctly with the new key

---

## Phase 11: End-to-End Verification

- [ ] 25. Smoke test `washinton_agent`
  - [ ] 25.1 All existing Washington CRM routes return 200 (no regression)
  - [ ] 25.2 `GET /` returns public home page without auth
  - [ ] 25.3 `GET /Quote-Request` returns form without auth
  - [ ] 25.4 `POST /Quote-Request` creates a lead visible in `/view_query`
  - [ ] 25.5 `GET /register` returns signup form without auth
  - [ ] 25.6 `POST /register` with Agent type creates user with status=0 and permissions from user 130
  - [ ] 25.7 `POST /register` with Carrier type creates user with status=0 and permissions from user 53
  - [ ] 25.8 `GET /commission` returns 200 for authorised user, redirects to login for unauthenticated
  - [ ] 25.9 `POST /bridge/washington/agent/commission` returns 401 with wrong key, 200 with correct key
  - [ ] 25.10 `POST /bridge/washington/agent/status` returns 401 with wrong key, 200 with correct key

- [ ] 26. Smoke test `washinton_hr`
  - [ ] 26.1 Admin login works at `hrwashinton.shawntransport.com/admin/login`
  - [ ] 26.2 Employee login works at `hrwashinton.shawntransport.com/employee/login`
  - [ ] 26.3 All `hr_` prefixed tables exist in `shiap16_main2`
  - [ ] 26.4 `POST /bridge/agent/login` with valid `agent_id` returns a signed redirect URL
  - [ ] 26.5 `POST /bridge/agent/login` with unknown `agent_id` returns 404

- [ ] 27. End-to-end SSO test
  - [ ] 27.1 In `washinton_hr`, create/find an `hr_employees` record and set its `agent_id` = a Washington `user.id`
  - [ ] 27.2 Click "Open HR Portal" on that user's Edit Employee screen in `washinton_agent`
  - [ ] 27.3 Verify the bridge call sends `user.id` as `agent_id` to `washinton_hr`
  - [ ] 27.4 Verify browser redirects to `hrwashinton.shawntransport.com` and the employee is logged in
  - [ ] 27.5 Verify old portals (`agent.daydispatch.com`, `hragent.daydispatch.com`) are completely unaffected
