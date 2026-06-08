# Employee Sync - Quick Start Guide

## What Was Implemented?

Automatic synchronization of employees from HR portal (washinton_hr) to Agent CRM (washinton_agent) when:
- A new employee is created in HR
- An employee's key details are updated in HR

## Quick Setup (5 minutes)

### Step 1: Apply Database Migration
```bash
cd d:\laragon2\www\washinton_agent
php artisan migrate --force
```

### Step 2: Verify Environment Keys Match

**washinton_hr/.env** (line 55-56):
```
HELLOTRANSPORT_BRIDGE_URL=https://hellotransport.com
HELLOTRANSPORT_BRIDGE_KEY=c689d5166a3fe6a0772ce020b14c2e0d980559f4de3e80617d617383ac592cf5
```

**washinton_agent/.env** (line 86):
```
HELLOTRANSPORT_BRIDGE_SHARED_KEY=c689d5166a3fe6a0772ce020b14c2e0d980559f4de3e80617d617383ac592cf5
```

The keys must match exactly.

### Step 3: Clear Cache
```bash
cd d:\laragon2\www\washinton_agent
php artisan config:cache
php artisan route:cache
```

### Step 4: Test
Create an employee in washinton_hr, verify it appears in washinton_agent.

## What Gets Synced?

| Field | Source | Destination |
|-------|--------|-------------|
| Employee ID | hr_employees.id | users.hr_employee_id |
| Name | first_name + last_name | users.name |
| Email | email | users.email |
| Phone | phone | users.phone |
| Role | role_id + role_name | roles + users.role |

## Files Modified

| File | Change |
|------|--------|
| washinton_agent/.env | Added bridge key |
| washinton_agent/routes/web.php | Added /bridge/employee/sync route |
| washinton_agent/app/User.php | Updated $fillable array |
| washinton_hr/.env | Added bridge URL and key |
| washinton_hr/app/Providers/AppServiceProvider.php | Registered observer |

## Files Created

### washinton_agent
- `database/migrations/2026_06_07_add_hr_employee_id_to_users.php`
- `app/Http/Controllers/Bridge/EmployeeSyncController.php`
- `EMPLOYEE_SYNC_SETUP.md` (detailed guide)
- `EMPLOYEE_SYNC_TEST.php` (test script)
- `IMPLEMENTATION_SUMMARY.md` (full documentation)
- `QUICK_START.md` (this file)

### washinton_hr
- `app/Observers/EmployeeObserver.php`
- `EMPLOYEE_SYNC_SETUP.md` (HR-specific guide)

## Test It

### Option 1: Create Employee in UI
1. Go to https://hr.hellotransport.com
2. Create new employee
3. Check https://hellotransport.com for new user
4. Verify phone number matches

### Option 2: Direct API Test
```bash
curl -X POST https://hellotransport.com/bridge/employee/sync \
  -H "X-Bridge-Key: c689d5166a3fe6a0772ce020b14c2e0d980559f4de3e80617d617383ac592cf5" \
  -H "Content-Type: application/json" \
  -d '{
    "employee_id": 1,
    "first_name": "Test",
    "last_name": "Employee",
    "email": "test@example.com",
    "phone": "0300-1234567",
    "role_id": 1,
    "role_name": "Agent"
  }'
```

Expected response:
```json
{
  "success": true,
  "message": "Employee synced successfully.",
  "user_id": 42,
  "role_id": 1
}
```

### Option 3: Database Check
```bash
php artisan tinker

# In washinton_agent
>>> \App\User::where('email', 'test@example.com')->first();
```

Should show user with hr_employee_id set.

## Troubleshooting

### Employee created but not synced
1. Check HR logs: `storage/logs/laravel.log`
2. Search for "EmployeeObserver"
3. If error: check bridge key in HR .env

### Bridge key error (403)
```bash
# Verify keys match exactly
grep HELLOTRANSPORT_BRIDGE_KEY d:\laragon2\www\washinton_hr\.env
grep HELLOTRANSPORT_BRIDGE_SHARED_KEY d:\laragon2\www\washinton_agent\.env
```

### User not created (but sync succeeded)
1. Check if email already exists in washinton_agent
2. If exists: user was updated instead of created
3. Check hr_employee_id is set to correct employee ID

## Key Features

✓ Automatic sync on employee creation
✓ Automatic sync on employee update (name, email, phone, role)
✓ Role auto-creation if doesn't exist
✓ Email uniqueness handling
✓ Non-blocking (HR succeeds even if sync fails)
✓ Comprehensive logging
✓ Transaction rollback on errors

## Important Notes

1. **Default Status**: New users are inactive (admin must activate)
2. **Default Role**: Matched from HR or created if new
3. **Default Permissions**: Copied from Agent reference user (id=130)
4. **Bridge Key**: Stored in .env, passed in X-Bridge-Key header

## Next Steps

- Review `IMPLEMENTATION_SUMMARY.md` for full documentation
- Review `EMPLOYEE_SYNC_SETUP.md` for detailed setup
- Run `EMPLOYEE_SYNC_TEST.php` for diagnostic checks
- Monitor `storage/logs/laravel.log` for sync messages

## Support Resources

| Document | Purpose |
|----------|---------|
| IMPLEMENTATION_SUMMARY.md | Complete technical documentation |
| EMPLOYEE_SYNC_SETUP.md | Detailed setup and testing |
| EMPLOYEE_SYNC_TEST.php | Diagnostic test script |
| QUICK_START.md | This file |

## Database Changes

One migration adds one column to existing `user` table:
- `hr_employee_id` (bigint, nullable, unique)

No tables deleted or renamed.

## Rollback

If needed, rollback the migration:
```bash
php artisan migrate:rollback
```

This removes the hr_employee_id column and reverts to original state.

---

**Status**: Ready for production
**Last Updated**: 2026-06-07
**Implementation**: Complete
