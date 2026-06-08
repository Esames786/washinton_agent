# Employee Sync Implementation Guide

## Overview
This document describes the automated employee sync feature from `washinton_hr` to `washinton_agent`.

## Architecture

### Data Flow
```
washinton_hr (Employee Created/Updated)
    ↓
EmployeeObserver (on Employee model)
    ↓
HTTP POST to washinton_agent /bridge/employee/sync
    ↓
EmployeeSyncController (validates bridge key)
    ↓
getRoleIdOrCreate() → Find or create role in washinton_agent
    ↓
createOrUpdateUser() → Create or update user in washinton_agent
    ↓
JSON Response {success: true, user_id: X, role_id: Y}
```

## Components Implemented

### 1. washinton_agent
#### Migration: `2026_06_07_add_hr_employee_id_to_users.php`
- Adds `hr_employee_id` column to `user` table
- Stores the employee ID from washinton_hr for linking
- Unique constraint to prevent duplicates
- Foreign key references to hr_employees table (if available)

#### Controller: `app/Http/Controllers/Bridge/EmployeeSyncController.php`
- **Endpoint**: `POST /bridge/employee/sync`
- **Auth**: X-Bridge-Key header (matches `HELLOTRANSPORT_BRIDGE_SHARED_KEY` in .env)
- **Input**: `employee_id`, `first_name`, `last_name`, `email`, `phone`, `role_id`, `role_name`
- **Output**: `{success: true, user_id: X, role_id: Y}` or error JSON
- **Functions**:
  - `syncEmployee()` - Main entry point, validates input
  - `getRoleIdOrCreate()` - Finds or creates role by name
  - `createOrUpdateUser()` - Creates new or updates existing user

#### Route: `routes/web.php`
```php
Route::prefix('bridge')->group(function () {
    Route::post('/employee/sync', 'Bridge\EmployeeSyncController@syncEmployee');
});
```

#### Updated Files:
- `.env` - Added `HELLOTRANSPORT_BRIDGE_SHARED_KEY`
- `app/User.php` - Updated `$fillable` to include new fields

### 2. washinton_hr
#### Observer: `app/Observers/EmployeeObserver.php`
- Listens to Employee model `created` and `updated` events
- On creation: Sends full sync request
- On update: Sends sync request only if key fields changed (name, email, phone, role)
- Non-blocking: Logs errors but doesn't interrupt HR operations

#### Service Provider: `app/Providers/AppServiceProvider.php`
- Registers `EmployeeObserver` to observe `Employee` model

#### Updated Files:
- `.env` - Added `HELLOTRANSPORT_BRIDGE_URL` and `HELLOTRANSPORT_BRIDGE_KEY`

## Setup Steps

### 1. Run Migration (washinton_agent)
```bash
cd d:\laragon2\www\washinton_agent
php artisan migrate --force
```

Expected output:
```
Migrating: 2026_06_07_add_hr_employee_id_to_users
Migrated:  2026_06_07_add_hr_employee_id_to_users (XYZ ms)
```

### 2. Verify .env Configuration

**washinton_hr/.env** (should have):
```
HELLOTRANSPORT_BRIDGE_URL=https://hellotransport.com
HELLOTRANSPORT_BRIDGE_KEY=c689d5166a3fe6a0772ce020b14c2e0d980559f4de3e80617d617383ac592cf5
```

**washinton_agent/.env** (should have):
```
HELLOTRANSPORT_BRIDGE_SHARED_KEY=c689d5166a3fe6a0772ce020b14c2e0d980559f4de3e80617d617383ac592cf5
```

Both keys must be identical.

## Testing

### 1. Manual API Test

Using curl or Postman:

```bash
POST https://hellotransport.com/bridge/employee/sync
Headers:
  X-Bridge-Key: c689d5166a3fe6a0772ce020b14c2e0d980559f4de3e80617d617383ac592cf5
  Content-Type: application/json

Body:
{
  "employee_id": 1,
  "first_name": "John",
  "last_name": "Doe",
  "email": "john.doe@example.com",
  "phone": "1234567890",
  "role_id": 5,
  "role_name": "Agent"
}
```

Expected Response (201):
```json
{
  "success": true,
  "message": "Employee synced successfully.",
  "user_id": 42,
  "role_id": 5
}
```

### 2. Create Employee in washinton_hr

1. Log in to `https://hr.hellotransport.com`
2. Navigate to Employees section
3. Create a new employee with:
   - First Name: Test
   - Last Name: Employee
   - Email: test@example.com
   - Phone: 0300-1234567
   - Role: Select any existing role
4. Save the employee

### 3. Verify Sync to washinton_agent

After employee creation in HR:

**Check 1: User Created in washinton_agent**
```bash
cd d:\laragon2\www\washinton_agent
php artisan tinker
>>> \App\User::where('email', 'test@example.com')->first();
```

**Check 2: hr_employee_id Link**
```bash
>>> \App\User::where('hr_employee_id', 1)->first();
```

**Check 3: Role Created if Needed**
If the role didn't exist, a new role should have been created:
```bash
>>> \App\role::where('name', 'Agent')->first();
```

**Check 4: Logs**
```bash
cd d:\laragon2\www\washinton_agent
# Check storage/logs/laravel.log for sync status
tail -f storage/logs/laravel.log
```

## Default Permissions

When a new user is synced from HR, they receive:
- Permission columns copied from reference user (id=130, Agent type)
- `order_taker_quote = 1`
- `current_status = 0` (inactive)
- `verify = 1` (visible in employee list)
- Panel type: 1 (default)
- Call type: 134 (default)

Admin can modify these after sync.

## Error Handling

### Bridge Key Invalid
- Response: 403 Forbidden
- Log: Warning logged with truncated key
- Cause: X-Bridge-Key header missing or incorrect

### Email Already Exists
- Response: 200 OK with updated user (if same email)
- Action: Updates existing user instead of creating new one
- Log: Info logged with "Existing user updated"

### Role Not Found/Created
- Response: 500 Server Error
- Log: Error logged with role name and failure reason
- Action: Transaction rolled back, HR creation unaffected

### Employee Sync Failure (HR side)
- Response: Success in HR (no interruption)
- Log: Error logged in washinton_hr
- Action: Admin should check logs and manually trigger sync if needed

## Manual Sync (if needed)

If sync fails and needs to be retried:

```bash
cd d:\laragon2\www\washinton_hr
php artisan tinker

// Find the employee
$emp = \App\Models\Employee::find(1);

// Trigger observer manually
$emp->touch(); // Updates timestamp and triggers observer
```

## Troubleshooting

### 1. Migration Failed
**Error**: "Specified key was too long"
**Solution**: Already handled with `Schema::defaultStringLength(191)` in AppServiceProvider

### 2. Bridge Key Mismatch
**Symptom**: 403 errors when syncing
**Check**:
```bash
# washinton_hr
echo $HELLOTRANSPORT_BRIDGE_KEY

# washinton_agent
echo $HELLOTRANSPORT_BRIDGE_SHARED_KEY
```

Must be identical.

### 3. HTTP Connection Failed
**Symptom**: Timeout errors in HR logs
**Check**:
```bash
# From washinton_hr server
curl -X POST https://hellotransport.com/bridge/employee/sync \
  -H "X-Bridge-Key: YOUR_KEY" \
  -H "Content-Type: application/json" \
  -d '{"employee_id":1,"first_name":"Test","last_name":"Emp","email":"test@example.com","phone":"","role_id":1,"role_name":"Admin"}'
```

### 4. User Not Created in washinton_agent
**Symptom**: Sync appears successful but user not found
**Check**:
- Bridge endpoint URL is correct in HR .env
- Network connectivity between servers
- Database connection from washinton_agent works
- Check `storage/logs/laravel.log` in washinton_agent

### 5. Duplicate Role Creation
**Symptom**: Multiple roles with same name
**Prevention**: Observer checks by name first, not ID
**Fix**: Delete duplicates, roles are checked by exact name match

## Performance Notes

- Bridge call is non-blocking on HR side (logged but won't fail HR operation)
- Migration adds one nullable index to user table
- Role creation is transactional (rolls back if fails)
- No polling required, event-driven architecture

## Security

- Bridge key in X-Bridge-Key header (not in URL)
- Keys must match exactly (case-sensitive)
- HTTP timeout: 10 seconds per sync request
- All failures logged for audit trail
- Foreign key constraint on hr_employee_id (if cross-DB relations work)

## Future Enhancements

1. **Deletion Sync**: Observer for employee deletion → soft delete in agent
2. **Bulk Sync**: Endpoint for syncing multiple employees at once
3. **Status Sync**: Keep employee status (active/inactive) in sync
4. **Document Sync**: Sync documents from HR to agent
5. **Webhook Verification**: Implement HMAC signature verification for bridge requests
