# Batch 6 — Production Deploy Guide (2026-07-11)

> **Rule:** Deploy **washinton_hr** and **washinton_agent** together (bridge SSO + document
> settings depend on both). Take a **DB backup** before running any migration/seeder.

---

## PART 1 — SAFE deploy (do this now)

Everything below is tested + lint-clean and does **not** touch the risky dynamic-panel
go-live. Panel *foundation* tables are additive (nothing reads them yet), so they are safe
to migrate too.

### 1.1 Files to upload

**washinton_hr**
- `app/Http/Controllers/AdminEmployeeController.php`
- `resources/views/admin/user_management/employees/add_employee.blade.php`
- `resources/views/admin/user_management/employees/index.blade.php`
- `resources/views/partials/navbar-header.blade.php`
- `database/migrations/2026_07_11_000001_b6_set_active_document_settings.php`

**washinton_agent**
- `app/Http/Controllers/Auth/LoginController.php`
- `app/Http/Controllers/Bridge/BridgeAuthController.php`
- `app/Http/Controllers/FrontendController.php`
- `app/Http/Kernel.php`
- `app/Providers/EventServiceProvider.php`
- `app/Http/Middleware/EnforceUserSecurity.php`  *(new)*
- `app/Listeners/RecordLoginActivity.php`         *(new)*
- `resources/views/layouts/innerpages.blade.php`
- `resources/views/layouts/mainsite.blade.php`
- `resources/views/main/register/edit_employee.blade.php`
- `resources/views/main/register/index.blade.php`
- `resources/views/main/register/view_register.blade.php`
- `database/migrations/2026_07_10_000001_add_current_session_id_to_user.php`  *(new column)*

> Panel foundation files (`app/PanelType.php`, the 3 `2026_07_11_*` panel migrations,
> `database/seeds/B6*.php`) are **inert** — safe to upload now but see PART 2 before using them.

### 1.2 Run migrations

```bash
# washinton_hr
php artisan migrate --force        # runs b6_set_active_document_settings

# washinton_agent
php artisan migrate --force        # runs add_current_session_id_to_user
                                   # (+ panel_types / user_panel_access / signup_defaults if uploaded)
```

### 1.3 Clear caches (BOTH projects)

```bash
php artisan optimize:clear         # route + config + view cache
```

### 1.4 Verify (2 min smoke test)
- HR: create a subcontractor end-to-end → Publish works; Review step shows; gratuity/leaves toggle works.
- HR: employees list newest-id first; **Agent ID** column visible; documents screen shows only the 7 active + Father/Mother CNIC.
- HR: an agent who came from hello sees **Back to Hello Dashboard** in the dropdown.
- Hello: log in → check IP is recorded; log in on a 2nd browser → 1st session is kicked.
- Hello: a website quote lands on **Website (4)**.
- Hello: register screen shows only Admin / Order Taker / Dispatcher / Manager / QA.

**If anything looks wrong:** re-run `php artisan optimize:clear`. The login/IP middleware is
**fail-open** (never locks anyone out on error).

---

## PART 2 — Dynamic panels (city names) — NOW INCLUDED & safe

Dynamic panels are implemented and safe to deploy WITH Part 1, **as long as the panel
seeder runs**. What's live:
- City names everywhere (active-panel label, switcher, order badges, filters) via
  `PanelType::nameFor()` / `listActive()` — both **fail-safe**: if the table is missing
  they fall back to "Panel N" / empty, so the nav never 500s during the deploy window.
- Panel switcher, order badges, subcontractor panel-access checkboxes, customer filter →
  all driven by the `panel_types` table (city names + new panels 7-10 appear automatically).
- **Admin screen**: `/panel-types` (admin-only, also in the profile dropdown) to
  create / rename / enable-disable panels. Testing + Website are system-locked.
- **Signup**: the 4 signup controllers now read `signup_defaults` (fallback = reference
  user if not seeded) and assign the panel by city (entered city → IP geolocation →
  matched functional panel 1-6, else the safe default). No runtime dependency on users 130/53.

### ⚠️ Required with the code deploy
```bash
# washinton_agent — run WITH the Part 1 migrate/clear
php artisan migrate --force                              # panel_types, user_panel_access, signup_defaults
composer dump-autoload                                   # so database/seeds/B6* classes resolve
php artisan db:seed --class=B6PanelTypeSeeder --force    # REQUIRED — city names + panels 1-10
php artisan db:seed --class=B6SignupDefaultsSeeder --force   # recommended — else signup uses reference-user fallback
php artisan optimize:clear
```
`B6PanelTypeSeeder` is **required** — without it the switcher / assignment lists render empty.
Both seeders are idempotent (safe to re-run).

### Verify (panels)
- `/panel-types` lists Lahore…Karachi; renaming a non-system panel updates labels everywhere.
- Panel switcher + order badges show city names.
- A new signup from a known city lands on that city's panel (check `user_setting.penal_type`).

### ⛔ Still DEFERRED (one piece, high-risk — NOT in this deploy)
**Granular per-panel PERMISSION editing for the NEW panels 7-10** (Bahawalpur, Jhang,
Peshawar, Karachi). New panels are named / assignable / routable / switchable, but the
`check_panel()` permission source for 7-10 still needs to read `user_panel_access`
(6 hot controller switches + edit_employee save wiring + `B6UserPanelAccessSeeder`).
Because that touches every-request permission loading across ~600 sites, it must be a
separate, tested rollout. City-based signup deliberately assigns **only functional panels
1-6**, so nothing lands on an unwired panel. `B6UserPanelAccessSeeder` is for THAT future
step — not needed for this deploy.

---

## Rollback
- HR document migration `down()` is a no-op (data curation) — to revert, flip statuses manually.
- `add_current_session_id_to_user` / panel tables: `php artisan migrate:rollback --step=1` per migration.
- Panels degrade gracefully: if you must disable, empty `panel_types` → labels revert to "Panel N".
- Code: redeploy the previous release; `php artisan optimize:clear`.

## Resolved
- Nav "info icon" = the **Port (crane) icon** → hidden via CSS (`#portmodal` trigger + modal).
