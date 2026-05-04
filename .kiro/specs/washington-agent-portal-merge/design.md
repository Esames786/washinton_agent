# Design Document - Washington Agent Portal Merge

## Overview

The Washington Agent Portal Merge produces **two new independent portals** that are completely isolated from the existing live portals (`daydispatchagent`, `daydispatchhr`, `washinton_latest`). The old portals are never touched.

### Two New Portals

| Portal | Folder | Domain | Purpose |
|---|---|---|---|
| **Washington Agent** | `washinton_agent` | `washintonagent.shawntransport.com` | Washington CRM + DayDispatch public frontend merged |
| **Washington HR** | `washinton_hr` | `hrwashinton.shawntransport.com` | Copy of HR portal, linked to Washington Agent |

### Single Shared Database

Both new portals share **one database: `shiap16_main2`** (the existing Washington database, hosted on the same cPanel server). The HR portal tables are added to this database with an `hr_` prefix to avoid collisions with Washington's existing tables.

```
shiap16_main2 (single database on cPanel)
├── Washington tables (existing): order, user, profit, roles, commission_ranges, ...
└── HR tables (new, prefixed): hr_employees, hr_payrolls, hr_departments,
    hr_attendances, hr_payroll_details, hr_petty_cash_masters, ...
```

**Why single database:**
- Both portals are on the same cPanel hosting — no cross-server connections needed
- One DB backup covers everything
- Simpler deployment and maintenance
- HR tables use `hr_` prefix to prevent any name collision (e.g. HR has `users`, `roles`, `notifications` which clash with Washington's tables)

### Old Portals — Zero Changes

```
agent.daydispatch.com     → daydispatchagent folder  → daydispatchagent DB  (UNTOUCHED)
hragent.daydispatch.com   → daydispatchhr folder     → daydispatchagent DB  (UNTOUCHED)
washington.shawntransport.com → washinton_latest folder → shiap16_main2 DB  (UNTOUCHED)
```

### New Portals

```
washintonagent.shawntransport.com → washinton_agent folder → shiap16_main2 DB
hrwashinton.shawntransport.com    → washinton_hr folder    → shiap16_main2 DB (hr_ tables)
```

### What Each New Portal Contains

**`washinton_agent`** (copy of `washinton_latest` + DayDispatch frontend merged in):
- All existing Washington CRM functionality unchanged
- DayDispatch public frontend pages (converted from Livewire to Blade)
- Public self-service signup (`/register`)
- Commission & Profit screen
- Bridge service that calls `washinton_hr` for SSO

**`washinton_hr`** (copy of `daydispatchhr` with `hr_` prefixed tables):
- All HR portal functionality unchanged (payroll, attendance, gratuity, petty cash, tickets)
- All Eloquent models updated to use `hr_` prefixed table names
- Bridge endpoints updated to use new Washington-specific shared key
- DB connection points to `shiap16_main2` instead of `daydispatchagent`

### Key Design Decisions

- **No framework upgrade**: `washinton_agent` stays on Laravel 7/8 + PHP 8.1. The Agent Portal's Livewire components are converted to plain Blade views.
- **Single database, `hr_` prefix**: All HR portal tables are added to `shiap16_main2` with `hr_` prefix. Models in `washinton_hr` have `protected $table = 'hr_employees'` etc.
- **Washington as identity source**: The `user` table in `shiap16_main2` is the single source of truth for authentication. An `agent_id` column links Washington users to HR employees.
- **Bridge between the two new portals only**: `washinton_agent` calls `washinton_hr` bridge endpoints. The old portals' bridge keys are completely separate.
- **Permission template copy**: New signups inherit permissions from reference users (id=130 for Agent, id=53 for Carrier).
- **Read-only commission calculation**: The commission screen and bridge endpoint are pure reads — they never modify order, profit, or payment records.

## Architecture

### System Context Diagram

```
cPanel Server — shawntransport.com
┌──────────────────────────────────────────────────────────────────────┐
│                                                                      │
│  washintonagent.shawntransport.com        hrwashinton.shawntransport.com  │
│  ┌─────────────────────────────┐         ┌──────────────────────────┐│
│  │  washinton_agent            │         │  washinton_hr             ││
│  │  Laravel 7/8 · PHP 8.1      │◄───────►│  Laravel 10 · PHP 8.2    ││
│  │                             │ bridge  │                          ││
│  │  ┌──────────┐ ┌──────────┐  │ HTTP    │  ┌──────────────────────┐││
│  │  │ Public   │ │ CRM/Auth │  │ POST    │  │ Payroll, Attendance  │││
│  │  │ Frontend │ │(unchanged│  │         │  │ Gratuity, Petty Cash │││
│  │  │ /register│ │)         │  │         │  │ Tickets, Payslips    │││
│  │  │ /Quote-Req│ │/commission│ │         │  └──────────────────────┘││
│  │  └──────────┘ └──────────┘  │         └──────────────────────────┘│
│  └─────────────────────────────┘                    │                │
│              │                                      │                │
│              └──────────────────┬───────────────────┘                │
│                                 ▼                                    │
│                    ┌────────────────────────┐                        │
│                    │   shiap16_main2 (MySQL) │                        │
│                    │                        │                        │
│                    │  Washington tables:     │                        │
│                    │  order, user, profit,   │                        │
│                    │  roles, commission_     │                        │
│                    │  ranges, ...            │                        │
│                    │                        │                        │
│                    │  HR tables (hr_ prefix):│                        │
│                    │  hr_employees,          │                        │
│                    │  hr_payrolls,           │                        │
│                    │  hr_departments,        │                        │
│                    │  hr_attendances, ...    │                        │
│                    └────────────────────────┘                        │
│                                                                      │
│  (Separate, untouched)                                               │
│  agent.daydispatch.com → daydispatchagent → daydispatchagent DB      │
│  hragent.daydispatch.com → daydispatchhr → daydispatchagent DB       │
└──────────────────────────────────────────────────────────────────────┘
```

### HR Table Prefix Mapping

All HR portal models in `washinton_hr` are updated to use `hr_` prefixed table names:

| Original Table | Prefixed Table in shiap16_main2 |
|---|---|
| `users` (HR admins) | `hr_users` |
| `employees` | `hr_employees` |
| `roles` (HR roles) | `hr_roles` |
| `departments` | `hr_departments` |
| `designations` | `hr_designations` |
| `payrolls` | `hr_payrolls` |
| `payroll_details` | `hr_payroll_details` |
| `payroll_statuses` | `hr_payroll_statuses` |
| `employee_attendances` | `hr_employee_attendances` |
| `employee_breaks` | `hr_employee_breaks` |
| `employee_leaves` | `hr_employee_leaves` |
| `employee_tickets` | `hr_employee_tickets` |
| `ticket_messages` | `hr_ticket_messages` |
| `petty_cash_masters` | `hr_petty_cash_masters` |
| `petty_cash_transactions` | `hr_petty_cash_transactions` |
| `petty_cash_heads` | `hr_petty_cash_heads` |
| `shift_types` | `hr_shift_types` |
| `holidays` | `hr_holidays` |
| `leave_types` | `hr_leave_types` |
| `gratuity_settings` | `hr_gratuity_settings` |
| `gratuity_balances` | `hr_gratuity_balances` |
| `gratuity_payouts` | `hr_gratuity_payouts` |
| `commission_settings` | `hr_commission_settings` |
| `tax_slab_settings` | `hr_tax_slab_settings` |
| `currency_rates` | `hr_currency_rates` |
| `notifications` | `hr_notifications` |
| `user_screenshots` | `hr_user_screenshots` |
| `personal_access_tokens` | `hr_personal_access_tokens` |
| `permissions` (Spatie) | `hr_permissions` |
| `model_has_permissions` | `hr_model_has_permissions` |
| `model_has_roles` | `hr_model_has_roles` |
| `role_has_permissions` | `hr_role_has_permissions` |
| All other HR tables | `hr_` + original name |

### `washinton_hr` Setup Steps

1. Copy `daydispatchhr` folder to `washinton_hr`
2. Update `.env`: `DB_DATABASE=shiap16_main2`, `DB_USERNAME`, `DB_PASSWORD` to match cPanel
3. Update all Eloquent model `$table` properties to add `hr_` prefix
4. Update `config/bridge.php` shared key to the new Washington-specific key
5. Run `php artisan migrate` — creates all `hr_` prefixed tables in `shiap16_main2`
6. Update Spatie permission config to use `hr_` prefixed table names

### Request Flow Summary

### Request Flow Summary

**Public Signup Flow**
```
Browser → GET /register → PublicSignupController@showForm → register.blade.php
Browser → POST /register → PublicSignupController@store
  → validate fields
  → load reference user (130 or 53)
  → create User (status=0, copy permissions)
  → create user_settings
  → redirect /loginn with success flash
```

**HR Portal SSO Flow**
```
Admin → GET /hr-portal/{userId} → HrPortalRedirectController@redirect
  → load User, assert agent_id not null
  → HrPortalBridgeService::login(agent_id)
    → POST hrwashinton.shawntransport.com + /bridge/agent/login {agent_id, X-Bridge-Key}
  → washinton_hr returns {redirect_url: signed_url}
  → redirect()->away(redirect_url)
```

**Inbound Bridge — Commission**
```
HR Portal → POST /bridge/washington/agent/commission {agent_id, month, X-Bridge-Key}
  → WashingtonBridgeController@commission
  → validate key, validate agent_id
  → find User by agent_id
  → query order + profit + commission_ranges
  → return JSON commission summary
```

**Inbound Bridge — Status**
```
HR Portal → POST /bridge/washington/agent/status {agent_id, X-Bridge-Key}
  → WashingtonBridgeController@status
  → validate key, validate agent_id
  → find User by agent_id
  → return JSON {linked, active, user_id, user_name, message}
```

**Quote Request Flow**
```
Browser → GET /Quote-Request → FrontendController@quoteRequest → get-quote.blade.php
Browser → POST /Quote-Request → FrontendController@submitQuoteRequest
  → validate fields
  → create ShipaQuery record
  → redirect /Quote-Request/confirmation
```

## Components and Interfaces

### New Files to Create in `washington_agent`

#### 1. `config/bridge.php`

```php
return [
    'hrportal' => [
        'base_url'               => env('HRPORTAL_BASE_URL', ''),
        'shared_key'             => env('HRPORTAL_SHARED_KEY', ''),
        'agent_login_endpoint'   => env('HRPORTAL_AGENT_LOGIN_ENDPOINT', '/bridge/agent/login'),
        'agent_status_endpoint'  => env('HRPORTAL_AGENT_STATUS_ENDPOINT', '/bridge/agent/status'),
    ],
    'washington' => [
        'shared_key' => env('WASHINGTON_BRIDGE_SHARED_KEY', ''),
    ],
];
```

#### 2. `app/Services/HrPortalBridgeService.php`

Responsible for all outbound HTTP calls from Washington to the HR portal. Modelled after `daydispatchagent`'s `HrPortalAgentClient` but adapted for Laravel 7/8 (uses `Illuminate\Support\Facades\Http` which is available in Laravel 7+).

```php
namespace App\Services;

class HrPortalBridgeService
{
    public function login(int $agentId): array
    // Calls POST /bridge/agent/login with {agent_id}
    // Returns ['redirect_url' => '...'] on success
    // Throws RuntimeException on config error, connection error, or HTTP error

    public function status(int $agentId): array
    // Calls POST /bridge/agent/status with {agent_id}
    // Returns ['linked' => bool, 'active' => bool, ...] on success

    protected function post(string $endpoint, array $payload, string $fallbackMessage): array
    // Shared HTTP POST logic:
    //   - Reads base_url and shared_key from config('bridge.hrportal.*')
    //   - Throws RuntimeException if base_url or shared_key is blank
    //   - Sets X-Bridge-Key header
    //   - Sets 20-second timeout
    //   - Throws RuntimeException on ConnectionException or HTTP error response
}
```

#### 3. `app/Http/Controllers/PublicSignupController.php`

Handles the public self-service signup form. No auth middleware.

```php
namespace App\Http\Controllers;

class PublicSignupController extends Controller
{
    public function showForm()
    // Returns view('auth.register')

    public function store(Request $request)
    // Validates: name, email (unique:user), password (min:8, confirmed),
    //            phone, address, signup_type (in:agent,carrier)
    // Loads reference user (130 for agent, 53 for carrier)
    // Creates User with status=0, copies all permission columns from reference
    // Creates user_settings with penal_type from reference (or 1 for agent)
    // Redirects to /loginn with success message
}
```

**Permission columns to copy from reference user:**
`emp_access_phone`, `emp_access_web`, `emp_access_test`, `panel_type_4`, `panel_type_5`, `panel_type_6`, `emp_panel_access`, `emp_show_data`, `emp_access_ship`, `emp_access_profile`, `emp_access_action`, `emp_access_report`, `emp_access_guide`, `order_taker_quote`, `assign_daily_qoute`, `sheet_access`

#### 4. `app/Http/Controllers/Bridge/WashingtonBridgeController.php`

Handles inbound bridge requests from the HR portal. No web session middleware — uses API-style key validation.

```php
namespace App\Http\Controllers\Bridge;

class WashingtonBridgeController extends Controller
{
    public function commission(Request $request): JsonResponse
    // Validates X-Bridge-Key header (abort 401 if invalid)
    // Validates: agent_id (required, integer, min:1), month (nullable, date_format:Y-m)
    // Finds User by agent_id (404 if not found)
    // Queries commission data (see Commission Calculation section)
    // Returns JSON commission summary

    public function status(Request $request): JsonResponse
    // Validates X-Bridge-Key header (abort 401 if invalid)
    // Validates: agent_id (required, integer, min:1)
    // Finds User by agent_id
    // Returns JSON {linked, active, user_id, user_name, message}

    private function validateBridgeKey(Request $request): void
    // Calls abort(401) if X-Bridge-Key header does not match config('bridge.washington.shared_key')
}
```

#### 5. `app/Http/Controllers/HrPortalRedirectController.php`

Handles the "Open HR Portal" button click from the Edit Employee screen.

```php
namespace App\Http\Controllers;

class HrPortalRedirectController extends Controller
{
    public function __construct(protected HrPortalBridgeService $bridge) {}

    public function redirect(int $userId): RedirectResponse
    // Auth middleware applied via route
    // Loads User by $userId, asserts agent_id is not null
    // Calls $this->bridge->login($user->agent_id)
    // On success: redirect()->away($response['redirect_url'])
    // On RuntimeException: back()->with('hr_portal_error', $e->getMessage())
}
```

#### 6. `app/Http/Controllers/CommissionController.php`

Handles the internal Commission Screen.

```php
namespace App\Http\Controllers;

class CommissionController extends Controller
{
    public function index(Request $request): View
    // Auth middleware applied via route
    // Accepts: from_date, to_date (or month in Y-m format)
    // Queries commission summary per user
    // Returns view('main.commission.index', compact('rows', 'filters'))

    public function show(Request $request, int $userId): View
    // Shows per-order breakdown for a single user
    // Returns view('main.commission.show', compact('user', 'orders', 'filters'))
}
```

#### 7. `app/Http/Controllers/FrontendController.php`

Handles all public frontend static and dynamic pages.

```php
namespace App\Http\Controllers;

class FrontendController extends Controller
{
    public function home(): View
    public function aboutUs(): View
    public function faq(): View
    public function terms(): View
    public function carriers(): View
    public function brokers(): View
    public function blog(): View
    public function quoteRequest(): View
    public function submitQuoteRequest(Request $request): RedirectResponse
    // Validates: name, email, phone, origin, destination
    // Creates ShipaQuery record
    // Redirects to /Quote-Request/confirmation
    public function quoteConfirmation(): View
}
```

### Modified Files

#### `app/User.php`
Add `agent_id` to the `$fillable` array. No other changes.

#### `app/Http/Controllers/DashboardController.php`
Add `update_agent_id(Request $request)` method to handle saving `agent_id` from the Edit Employee form. Validates positive integer or null, enforces uniqueness.

#### `resources/views/main/register/edit_employee.blade.php`
Add Agent ID input field and "Open HR Portal" button (visible only when `$data2->agent_id` is not null and the authenticated user has the appropriate permission).

#### `routes/web.php`
Add new routes (see Routes section below).

### New View Files

| View Path | Description |
|---|---|
| `resources/views/auth/register.blade.php` | Public signup form |
| `resources/views/main/frontend/home.blade.php` | Public home page |
| `resources/views/main/frontend/about-us.blade.php` | About Us page |
| `resources/views/main/frontend/faq.blade.php` | FAQ page |
| `resources/views/main/frontend/terms.blade.php` | Terms of Service page |
| `resources/views/main/frontend/carriers.blade.php` | Carriers page |
| `resources/views/main/frontend/brokers.blade.php` | Brokers page |
| `resources/views/main/frontend/get-quote.blade.php` | Quote Request form |
| `resources/views/main/frontend/quote-confirmation.blade.php` | Quote submission confirmation |
| `resources/views/main/commission/index.blade.php` | Commission summary table |
| `resources/views/main/commission/show.blade.php` | Per-user order breakdown |

### Routes

New routes to add to `routes/web.php`:

```php
// Public frontend (no auth)
Route::get('/', 'FrontendController@home');
Route::get('/about_us', 'FrontendController@aboutUs');
Route::get('/faq', 'FrontendController@faq');
Route::get('/terms', 'FrontendController@terms');
Route::get('/carriers', 'FrontendController@carriers');
Route::get('/brokers', 'FrontendController@brokers');
Route::get('/blog', 'FrontendController@blog');
Route::get('/Quote-Request', 'FrontendController@quoteRequest');
Route::post('/Quote-Request', 'FrontendController@submitQuoteRequest');
Route::get('/Quote-Request/confirmation', 'FrontendController@quoteConfirmation');

// Public signup (no auth)
Route::get('/register', 'PublicSignupController@showForm');
Route::post('/register', 'PublicSignupController@store');

// HR Portal SSO redirect (auth required)
Route::get('/hr-portal/{userId}', 'HrPortalRedirectController@redirect')->middleware('auth');

// Commission screen (auth required)
Route::get('/commission', 'CommissionController@index')->middleware('auth');
Route::get('/commission/{userId}', 'CommissionController@show')->middleware('auth');

// Bridge endpoints (no session middleware — key-authenticated)
Route::post('/bridge/washington/agent/commission', 'Bridge\WashingtonBridgeController@commission');
Route::post('/bridge/washington/agent/status', 'Bridge\WashingtonBridgeController@status');
```

Note: The existing `Route::get('/', 'WelcomeController@loginn')` must be removed or replaced by the new public home route. The login page moves to `/loginn` only (already exists).

## Data Models

### Database: `shiap16_main2`

#### `user` table (existing, with additions)

| Column | Type | Notes |
|---|---|---|
| id | int (PK) | Existing |
| name | varchar(50) | Existing |
| last_name | varchar(50) | Existing |
| slug | varchar | Existing |
| username | varchar | Existing |
| email | varchar(50) UNIQUE | Existing |
| password | varchar | Existing |
| role | int (FK → roles.id) | Existing |
| phone | varchar | Existing |
| address | varchar | Existing |
| status | tinyint | Existing. 0=inactive, 1=active |
| emp_access_phone | varchar | Existing. Comma-separated permission integers |
| emp_access_web | varchar | Existing |
| emp_access_test | varchar | Existing |
| panel_type_4 | tinyint | Existing |
| panel_type_5 | tinyint | Existing |
| panel_type_6 | tinyint | Existing |
| emp_panel_access | varchar | Existing |
| emp_show_data | tinyint | Existing |
| emp_access_ship | tinyint | Existing |
| emp_access_profile | tinyint | Existing |
| emp_access_action | tinyint | Existing |
| emp_access_report | tinyint | Existing |
| emp_access_guide | tinyint | Existing |
| order_taker_quote | tinyint | Existing |
| assign_daily_qoute | tinyint | Existing |
| sheet_access | tinyint | Existing |
| **agent_id** | **int NULLABLE UNIQUE** | **NEW — links to HR portal employee** |
| deleted | tinyint | Existing. Soft delete flag |
| created_at | timestamp | Existing |
| updated_at | timestamp | Existing |

**Migration**: `database/migrations/YYYY_MM_DD_add_agent_id_to_user_table.php`
```php
Schema::table('user', function (Blueprint $table) {
    $table->integer('agent_id')->nullable()->unique()->after('sheet_access');
});
```

#### `user_settings` table (existing)

| Column | Type | Notes |
|---|---|---|
| id | int (PK) | Existing |
| user_id | int (FK → user.id) | Existing (note: migration shows no user_id — check live schema) |
| penal_type | int | Existing. 1=Auction, 2=ProMax, 3=Testing, 4=Shipa1, 5=Panel5, 6=Panel6 |
| created_at | timestamp | Existing |
| updated_at | timestamp | Existing |

Note: The migration file for `user_settings` does not include a `user_id` column, but the application code references `user_setting::where('user_id', $id)`. The live database has this column. No migration change needed.

#### `commission_ranges` table (existing)

| Column | Type | Notes |
|---|---|---|
| id | int (PK) | Existing |
| from_order | decimal(20,2) | Min profit value for this tier |
| to_order | decimal(20,2) | Max profit value for this tier |
| from_avg | decimal(20,2) | Min average (secondary range) |
| to_avg | decimal(20,2) | Max average (secondary range) |
| commission | decimal(20,2) | Commission amount/rate for this tier |
| created_at | timestamp | Existing |
| updated_at | timestamp | Existing |

Commission lookup: `WHERE profit BETWEEN from_order AND to_order`. If no row matches, commission = 0 and a warning is logged.

#### `order` table (existing, relevant columns)

| Column | Type | Notes |
|---|---|---|
| id | int (PK) | |
| pstatus | int | 13 = Completed |
| payment | decimal | Total payment amount |
| order_taker_id | int (FK → user.id) | The agent who took the order |
| created_at | timestamp | Used for date range filtering |

#### `profit` table (existing, relevant columns)

| Column | Type | Notes |
|---|---|---|
| id | int (PK) | |
| order_id | int (FK → order.id) | |
| profit | decimal | Profit amount for this order |
| user_id | int (FK → user.id) | |

#### `roles` table (existing, relevant rows)

| id | name |
|---|---|
| (lookup) | Order Taker |
| (lookup) | Dispatcher |

The actual role IDs are looked up at runtime via `role::where('name', 'Order Taker')->first()->id`. This avoids hardcoding IDs that may differ between environments.

### Commission Calculation Query

```sql
SELECT
    u.id            AS user_id,
    u.name          AS user_name,
    COUNT(o.id)     AS completed_orders_count,
    SUM(o.payment)  AS total_payment,
    SUM(p.profit)   AS total_profit,
    SUM(
        COALESCE(
            (SELECT cr.commission
             FROM commission_ranges cr
             WHERE p.profit BETWEEN cr.from_order AND cr.to_order
             LIMIT 1),
            0
        )
    )               AS total_commission
FROM user u
LEFT JOIN `order` o
    ON o.order_taker_id = u.id
    AND o.pstatus = 13
    AND o.deleted_at IS NULL
    AND o.created_at BETWEEN :from_date AND :to_date
LEFT JOIN profit p
    ON p.order_id = o.id
WHERE u.deleted = 0
GROUP BY u.id, u.name
ORDER BY u.name ASC
```

The LEFT JOIN ensures users with zero completed orders still appear in the result with zero values (Requirement 7.6).

### Bridge API Contracts

#### Outbound: `POST {hrportal.base_url}/bridge/agent/login`

**Request headers:**
```
X-Bridge-Key: {hrportal.shared_key}
Content-Type: application/json
Accept: application/json
```

**Request body:**
```json
{ "agent_id": 42 }
```

**Success response (200):**
```json
{
  "message": "Redirect ready.",
  "redirect_url": "https://hragent.daydispatch.com/bridge/hr/consume?..."
}
```

**Error responses:**
- `404` — Employee not linked to this agent_id
- `403` — Employee is inactive
- `422` — Validation error (agent_id missing or not integer)
- `401` — Invalid Bridge_Key

#### Inbound: `POST /bridge/washington/agent/commission`

**Request headers:**
```
X-Bridge-Key: {washington.shared_key}
Content-Type: application/json
```

**Request body:**
```json
{
  "agent_id": 42,
  "month": "2025-01"
}
```

**Success response (200):**
```json
{
  "agent_id": 42,
  "user_id": 130,
  "user_name": "John Smith",
  "period": "2025-01",
  "completed_orders_count": 15,
  "total_payment": "12500.00",
  "total_profit": "3200.00",
  "total_commission": "320.00"
}
```

**Error responses:**
- `401` — Missing or invalid X-Bridge-Key
- `404` — No user found with this agent_id
- `422` — agent_id is not a positive integer

#### Inbound: `POST /bridge/washington/agent/status`

**Request body:**
```json
{ "agent_id": 42 }
```

**Success response (200) — linked user:**
```json
{
  "linked": true,
  "active": true,
  "user_id": 130,
  "user_name": "John Smith",
  "message": "User linked and active."
}
```

**Success response (200) — not linked:**
```json
{
  "linked": false,
  "active": false,
  "user_id": null,
  "user_name": null,
  "message": "No user linked to this agent_id."
}
```

## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system — essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

This feature has several components that are well-suited to property-based testing: the signup flow (pure data transformation with permission copying), the commission calculation (pure arithmetic over database records), and the bridge endpoint authentication (universal key validation). The following properties are derived from the acceptance criteria prework analysis.

### Property Reflection

Before listing properties, redundancies were identified and consolidated:

- Requirements 3.7 and 3.8 (permission copy for Agent vs Carrier) are both instances of the same property: "for any signup type, the new user's permissions match the reference user for that type." These are combined into **Property 3**.
- Requirements 7.3 and 7.4 (date range filter and month filter) are both instances of date-range filtering. Combined into **Property 6**.
- Requirements 9.2 and 9.3 (idempotent commission calculation on screen vs bridge endpoint) are the same property applied to two surfaces. Combined into **Property 9**.
- Requirements 8.7 and 10.1 (bridge key validation) are the same property. Combined into **Property 10**.
- Requirements 4.4 and 4.5 (agent_id validation) are the same property. Combined into **Property 4**.

---

### Property 1: Quote Request Submission Round Trip

*For any* valid quote request submission (any combination of name, email, phone, origin, destination), submitting the form should result in a record appearing in the `ShipaQuery` table that contains the submitted values.

**Validates: Requirements 2.3**

---

### Property 2: Public Pages Require No Authentication

*For any* public route in the set `{/, /about_us, /faq, /terms, /carriers, /brokers, /Quote-Request, /register}`, an unauthenticated GET request should return HTTP 200 and not redirect to the login page.

**Validates: Requirements 2.6, 3.1**

---

### Property 3: Signup Creates User with Correct Permissions

*For any* valid signup submission (any name, email, phone, address, and signup_type of "agent" or "carrier"), the created `user` record should have:
- All permission columns (`emp_access_phone`, `emp_access_web`, `emp_access_test`, `panel_type_4`, `panel_type_5`, `panel_type_6`, `emp_panel_access`, `emp_show_data`, `emp_access_ship`, `emp_access_profile`, `emp_access_action`, `emp_access_report`, `emp_access_guide`, `order_taker_quote`, `assign_daily_qoute`, `sheet_access`) equal to the corresponding values from the reference user (id=130 for agent, id=53 for carrier).
- `status = 0`
- `name`, `email`, `phone`, `address` matching the submitted values exactly.

**Validates: Requirements 3.6, 3.7, 3.8**

---

### Property 4: Agent ID Validation Rejects Non-Positive Integers

*For any* value submitted as `agent_id` that is not a positive integer (including zero, negative integers, non-numeric strings, floats, and null-equivalent strings), the Edit Employee save operation should return a validation error and leave `user.agent_id` unchanged.

**Validates: Requirements 4.4, 4.5**

---

### Property 5: Agent ID Uniqueness

*For any* two distinct Washington users, they cannot both have the same non-null `agent_id`. Attempting to assign an `agent_id` that is already assigned to another user should fail with a validation error.

**Validates: Requirements 4.7**

---

### Property 6: Commission Calculation Respects Date Range

*For any* date range `[from_date, to_date]`, the commission calculation should include only orders where `order.created_at` falls within that range (inclusive). Orders outside the range should contribute zero to all totals.

**Validates: Requirements 7.3, 7.4**

---

### Property 7: Commission Calculation Correctness

*For any* set of completed orders with associated profit values, the commission for each order should equal the `commission` value from the `commission_ranges` row where `profit BETWEEN from_order AND to_order`. If no row matches, the commission for that order is zero.

**Validates: Requirements 7.5, 9.1**

---

### Property 8: Commission Screen Displays All Required Order Fields

*For any* order appearing in the per-user commission breakdown, the rendered output should contain all five required fields: order ID, order completion date, payment amount, profit amount, and commission amount — all non-null and formatted with two decimal places for monetary values.

**Validates: Requirements 7.8, 7.9**

---

### Property 9: Commission Calculation is Deterministic (Idempotent Read)

*For any* set of completed orders and profit values, calling the commission calculation (whether via the Commission Screen or the bridge endpoint) multiple times with the same inputs should always return the same result. The `order`, `profit`, and `payment` tables should be identical before and after any commission calculation.

**Validates: Requirements 9.2, 9.3, 9.4**

---

### Property 10: Bridge Endpoints Reject Invalid Keys

*For any* request to `/bridge/washington/agent/commission` or `/bridge/washington/agent/status` that is missing the `X-Bridge-Key` header or contains a key that does not match `config('bridge.washington.shared_key')`, the endpoint should return HTTP 401 and not execute any business logic (no database queries, no user data returned).

**Validates: Requirements 8.7, 10.1**

---

### Property 11: Bridge Endpoint Rejects Non-Positive Agent IDs

*For any* request to either bridge endpoint containing an `agent_id` that is not a positive integer (zero, negative, non-numeric), the endpoint should return HTTP 422 with a descriptive validation error.

**Validates: Requirements 8.8**

---

### Property 12: Bridge Commission Response Contains All Required Fields

*For any* valid request to `POST /bridge/washington/agent/commission` with a valid `agent_id` and optional `month`, the JSON response should contain all eight required fields: `agent_id`, `user_id`, `user_name`, `period`, `completed_orders_count`, `total_payment`, `total_profit`, `total_commission`.

**Validates: Requirements 8.2**

---

### Property 13: Bridge Status Response Contains All Required Fields

*For any* valid request to `POST /bridge/washington/agent/status` with any `agent_id` (whether linked or not), the JSON response should contain all five required fields: `linked`, `active`, `user_id`, `user_name`, `message`.

**Validates: Requirements 8.5, 8.6**

---

### Property 14: Bridge Service Throws on Missing Configuration

*For any* blank or missing value for `hrportal.base_url` or `hrportal.shared_key` in the bridge configuration, calling any method on `HrPortalBridgeService` should throw a `RuntimeException` before making any HTTP request.

**Validates: Requirements 11.4, 5.7**

---

### Property 15: Duplicate Email Signup is Rejected

*For any* email address that already exists in the `user` table, a signup attempt using that email should return a validation error and the total count of rows in the `user` table should remain unchanged.

**Validates: Requirements 3.12**

---

### Property 16: Missing Required Signup Fields are Rejected

*For any* subset of the required signup fields (`name`, `email`, `password`, `phone`, `address`, `signup_type`) that is omitted from the submission, the signup should fail with field-level validation errors for each missing field, and no new user record should be created.

**Validates: Requirements 3.13**

## Error Handling

### Bridge Service Errors (Outbound — Washington → HR Portal)

| Scenario | Handling |
|---|---|
| `hrportal.base_url` or `hrportal.shared_key` is blank | `HrPortalBridgeService` throws `RuntimeException('HR portal configuration is incomplete.')` before any HTTP call. Controller catches and shows error flash to admin. |
| HR portal is unreachable (connection timeout/refused) | `ConnectionException` is caught, re-thrown as `RuntimeException('HR portal is not reachable right now.')`. Controller shows error flash. |
| HR portal returns 404 (employee not linked) | `RuntimeException` with message from response `message` field. Controller shows error flash. No redirect. |
| HR portal returns 403 (employee inactive) | Same as 404 handling. |
| HR portal returns 422 (validation error) | Same as 404 handling. |
| HR portal returns unexpected 5xx | `RuntimeException` with HTTP status code and sanitised message. Error is logged (without Bridge_Key). |

All errors are caught in `HrPortalRedirectController::redirect()` and surfaced as `back()->with('hr_portal_error', $message)`. The raw HTTP response body and Bridge_Key are never exposed to the browser.

### Bridge Endpoint Errors (Inbound — HR Portal → Washington)

| Scenario | HTTP Status | Response |
|---|---|---|
| Missing or invalid `X-Bridge-Key` | 401 | `{"message": "Unauthorized."}` |
| `agent_id` missing or not a positive integer | 422 | `{"message": "Validation failed.", "errors": {...}}` |
| No user found for `agent_id` | 404 | `{"message": "No user linked to agent_id {n}."}` |
| Unexpected server error | 500 | `{"message": "Internal server error."}` (logged with full details server-side) |

### Signup Errors

| Scenario | Handling |
|---|---|
| Email already exists | Redirect back with `$errors->email` validation message |
| Required field missing | Redirect back with field-level `$errors` |
| Reference user (130 or 53) not found | Log error, return 500 with generic message (should never happen in production) |
| Database write failure | DB transaction rollback, redirect back with generic error message |

### Commission Calculation Errors

| Scenario | Handling |
|---|---|
| No `commission_ranges` row matches a profit value | Commission for that order = 0. `Log::warning("Unmatched profit value {$profit} for order {$orderId}")` |
| Date range filter produces no results | Return empty result set with zero totals (not an error) |
| User has no completed orders | Return row with all zero values (not an error) |

### Configuration Errors

| Scenario | Handling |
|---|---|
| `HRPORTAL_BASE_URL` not set | `RuntimeException` thrown by `HrPortalBridgeService` on first call |
| `HRPORTAL_SHARED_KEY` not set | Same as above |
| `WASHINGTON_BRIDGE_SHARED_KEY` not set | Bridge endpoints return 401 for all requests (empty key never matches) |

## Testing Strategy

### Dual Testing Approach

This feature uses both unit/example-based tests and property-based tests. Unit tests cover specific scenarios, integration points, and error conditions. Property-based tests verify universal correctness across a wide input space.

### Property-Based Testing Library

**Library**: [Eris](https://github.com/giorgiosironi/eris) for PHP (PHPUnit integration).

Eris is the standard property-based testing library for PHP/PHPUnit. Each property test runs a minimum of **100 iterations** with randomly generated inputs.

**Tag format for each property test:**
```php
// Feature: washington-agent-portal-merge, Property {N}: {property_text}
```

### Property Tests

Each of the 16 correctness properties maps to a single property-based test:

| Property | Test Class | Generator Strategy |
|---|---|---|
| P1: Quote Request Round Trip | `QuoteRequestPropertyTest` | Generate random name/email/phone/origin/destination strings |
| P2: Public Pages Require No Auth | `PublicRoutesPropertyTest` | Sample from the set of public route strings |
| P3: Signup Creates User with Correct Permissions | `SignupPermissionPropertyTest` | Generate random name/email/phone/address, alternate signup_type |
| P4: Agent ID Validation Rejects Non-Positive | `AgentIdValidationPropertyTest` | Generate zero, negatives, floats, strings |
| P5: Agent ID Uniqueness | `AgentIdUniquenessPropertyTest` | Generate two users, attempt to assign same agent_id to both |
| P6: Commission Respects Date Range | `CommissionDateRangePropertyTest` | Generate random date ranges and order sets |
| P7: Commission Calculation Correctness | `CommissionCalculationPropertyTest` | Generate random profit values and commission_ranges tiers |
| P8: Commission Order Fields Present | `CommissionOrderFieldsPropertyTest` | Generate random order sets, verify all fields present |
| P9: Commission is Deterministic | `CommissionIdempotencePropertyTest` | Generate random order sets, call calculation twice, compare |
| P10: Bridge Rejects Invalid Keys | `BridgeKeyValidationPropertyTest` | Generate random strings as X-Bridge-Key values |
| P11: Bridge Rejects Non-Positive Agent IDs | `BridgeAgentIdValidationPropertyTest` | Generate zero, negatives, strings as agent_id |
| P12: Commission Response Fields | `CommissionResponsePropertyTest` | Generate random valid agent_id + month combinations |
| P13: Status Response Fields | `StatusResponsePropertyTest` | Generate random agent_id values (linked and unlinked) |
| P14: Bridge Service Throws on Missing Config | `BridgeServiceConfigPropertyTest` | Generate blank/null values for base_url and shared_key |
| P15: Duplicate Email Rejected | `DuplicateEmailPropertyTest` | Generate random emails, insert one, attempt second signup |
| P16: Missing Fields Rejected | `MissingFieldsPropertyTest` | Generate all non-empty subsets of required fields to omit |

### Unit / Example Tests

**`PublicSignupControllerTest`**
- GET /register returns 200 without auth
- POST /register with valid Agent data creates user with role=Order Taker, status=0, penal_type=1
- POST /register with valid Carrier data creates user with role=Dispatcher, status=0, penal_type=ref_53
- POST /register redirects to /loginn with success message
- POST /register does not send any email (mock mailer)

**`HrPortalRedirectControllerTest`**
- Redirect with valid agent_id calls bridge service and redirects to returned URL
- Redirect with null agent_id returns 404 or error
- Redirect when bridge service throws RuntimeException shows error flash and does not redirect
- "Open HR Portal" button is visible on edit_employee when agent_id is set
- "Open HR Portal" button is absent when agent_id is null

**`WashingtonBridgeControllerTest`**
- POST /bridge/washington/agent/commission with valid key and known agent_id returns 200 with all fields
- POST /bridge/washington/agent/commission with unknown agent_id returns 404
- POST /bridge/washington/agent/status with valid key and known agent_id returns 200 with linked=true
- POST /bridge/washington/agent/status with unknown agent_id returns 200 with linked=false

**`CommissionControllerTest`**
- Commission screen accessible to authorised user (200)
- Commission screen redirects unauthorised user to login
- Commission screen shows zero row for user with no completed orders
- Per-user breakdown shows correct order-level data

**`HrPortalBridgeServiceTest`**
- Throws RuntimeException when base_url is blank
- Throws RuntimeException when shared_key is blank
- Sets X-Bridge-Key header on all requests
- Sets 20-second timeout on all requests
- Throws RuntimeException on connection failure
- Throws RuntimeException on HTTP 4xx/5xx response

### Integration Tests

The following scenarios require integration tests (1-3 examples each, not property-based):

- HR portal `POST /bridge/agent/login` returns redirect_url for active employee
- HR portal `POST /bridge/agent/login` returns 404 for unknown agent_id
- HR portal `POST /bridge/agent/login` returns 403 for inactive employee
- SSO redirect URL is valid within 2 minutes and invalid after

### Smoke Tests

- `user.agent_id` column exists with correct type and nullable+unique constraints
- `config/bridge.php` exists and all 5 required keys are present
- `.env.example` contains all bridge environment variable placeholders
- All existing Washington routes return 200 after merge (regression check)
- Bridge_Key is not present in any rendered view or HTTP response

### Test Configuration

```php
// phpunit.xml additions
<testsuite name="Feature-washington-agent-portal-merge">
    <directory>tests/Feature/WashingtonAgentPortalMerge</directory>
</testsuite>
```

Property tests use Eris with 100 iterations minimum:
```php
use Eris\TestTrait;

class CommissionCalculationPropertyTest extends TestCase
{
    use TestTrait;

    // Feature: washington-agent-portal-merge, Property 7: Commission Calculation Correctness
    public function test_commission_matches_range_tier(): void
    {
        $this->forAll(
            Generator\float(0.01, 99999.99),  // profit value
            Generator\elements($this->commissionRanges())  // commission tier
        )->then(function (float $profit, array $tier) {
            // ...
        });
    }
}
```











