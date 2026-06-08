# Employee Sync Implementation - Complete Summary

## Implementation Status: COMPLETE

All components for automatic employee sync from washinton_hr to washinton_agent have been implemented.

## Files Created/Modified

### washinton_agent

#### New Files
1. **`database/migrations/2026_06_07_add_hr_employee_id_to_users.php`**
   - Adds `hr_employee_id` column to `user` table
   - Unique constraint + foreign key reference

2. **`app/Http/Controllers/Bridge/EmployeeSyncController.php`**
   - POST /bridge/employee/sync endpoint
   - Validates bridge key from X-Bridge-Key header
   - Gets or creates role, creates or updates user
   - Returns JSON response with user_id and role_id

3. **`EMPLOYEE_SYNC_SETUP.md`**
   - Comprehensive setup and testing guide
   - Architecture documentation
   - Troubleshooting section

4. **`EMPLOYEE_SYNC_TEST.php`**
   - Diagnostic test script
   - Validates configuration, migration, routes, models
   - Can be run via `php artisan tinker`

5. **`IMPLEMENTATION_SUMMARY.md`**
   - This file

#### Modified Files
1. **`routes/web.php`**
   - Added route group for `/bridge/employee/sync`
   - No auth required (uses X-Bridge-Key header)

2. **`.env`**
   - Added `HELLOTRANSPORT_BRIDGE_SHARED_KEY` for inbound bridge requests

3. **`app/User.php`**
   - Updated `$fillable` array to include new fields:
     - `phone`
     - `role`
     - `hr_employee_id`
     - `verify`
     - `status`

### washinton_hr

#### New Files
1. **`app/Observers/EmployeeObserver.php`**
   - Listens to Employee model `created()` event
   - Listens to Employee model `updated()` event (if key fields change)
   - Makes HTTP POST request to washinton_agent `/bridge/employee/sync`
   - Non-blocking with try-catch error handling

2. **`EMPLOYEE_SYNC_SETUP.md`**
   - HR-specific setup and testing guide
   - Observer behavior documentation
   - Error cases and monitoring

#### Modified Files
1. **`app/Providers/AppServiceProvider.php`**
   - Register EmployeeObserver in `boot()` method
   - Observes Employee model

2. **`.env`**
   - Added `HELLOTRANSPORT_BRIDGE_URL` = `https://hellotransport.com`
   - Added `HELLOTRANSPORT_BRIDGE_KEY` (shared key)

## Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                      washinton_hr                                │
│  Employee Created/Updated                                        │
│         ↓                                                         │
│  EmployeeObserver::created() or updated()                        │
│         ↓                                                         │
│  HTTP POST /bridge/employee/sync                                 │
│  Headers: X-Bridge-Key: HELLOTRANSPORT_BRIDGE_KEY               │
│  Payload: employee_id, first_name, last_name, email, etc.       │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
         ║
         ║ (Network/HTTP)
         ║
┌─────────────────────────────────────────────────────────────────┐
│                    washinton_agent                               │
│         ↓                                                         │
│  EmployeeSyncController::syncEmployee()                          │
│  Assert Bridge Key matches HELLOTRANSPORT_BRIDGE_SHARED_KEY     │
│         ↓                                                         │
│  getRoleIdOrCreate()                                             │
│  - Find role by name in washinton_agent.roles                   │
│  - Create if not found (Str::slug(), level=5)                   │
│         ↓                                                         │
│  createOrUpdateUser()                                            │
│  - Find user by email                                            │
│  - Update if exists, create if new                              │
│  - Set hr_employee_id, phone, role, password, etc.              │
│  - Copy permissions from reference user (id=130)                │
│  - Create user_settings (penal_type=1, call_type=134)           │
│         ↓                                                         │
│  Response: {success: true, user_id: X, role_id: Y}             │
│         ↓                                                         │
│  Logs: Info level for success, Error level for failures         │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
```

## Endpoint Specification

### POST /bridge/employee/sync

**URL**: `https://hellotransport.com/bridge/employee/sync`

**Authentication**:
- Header: `X-Bridge-Key: c689d5166a3fe6a0772ce020b14c2e0d980559f4de3e80617d617383ac592cf5`

**Request Body**:
```json
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

**Success Response (200 OK)**:
```json
{
  "success": true,
  "message": "Employee synced successfully.",
  "user_id": 42,
  "role_id": 5
}
```

**Error Responses**:
- **422 Unprocessable Entity**: Validation failed
- **403 Forbidden**: Invalid/missing bridge key
- **500 Internal Server Error**: Server-side error (transaction rolled back)

## Default User Settings When Synced

When an employee is synced from HR to Agent:

| Field | Value | Notes |
|-------|-------|-------|
| `name` | first_name + ' ' + last_name | Full name |
| `email` | From HR employee record | Unique, may update existing |
| `phone` | From HR employee record | Optional |
| `password` | Random 12 chars (hashed) | Admin can reset |
| `role` | Matched/created role ID | Links to roles.id |
| `hr_employee_id` | From HR employee.id | Unique link back to HR |
| `current_status` | 0 (inactive) | Admin activates manually |
| `verify` | 1 | Shows in employee list |
| `status` | 0 | Inactive |
| User Settings: |  |  |
| `penal_type` | 1 (default panel) | From settings |
| `call_type` | 134 (default call app) | From settings |
| Permissions | Copied from user id=130 (Agent) | Includes order_taker_quote |

## Key Environment Variables

### washinton_hr/.env
```env
HELLOTRANSPORT_BRIDGE_URL=https://hellotransport.com
HELLOTRANSPORT_BRIDGE_KEY=c689d5166a3fe6a0772ce020b14c2e0d980559f4de3e80617d617383ac592cf5
```

### washinton_agent/.env
```env
HELLOTRANSPORT_BRIDGE_SHARED_KEY=c689d5166a3fe6a0772ce020b14c2e0d980559f4de3e80617d617383ac592cf5
```

**IMPORTANT**: Both keys must be identical.

## Setup Instructions

### 1. Apply Migration (washinton_agent)
```bash
cd d:\laragon2\www\washinton_agent
php artisan migrate --force
```

### 2. Verify Environment Variables
```bash
# washinton_hr
grep HELLOTRANSPORT_BRIDGE .env

# washinton_agent
grep HELLOTRANSPORT_BRIDGE .env
```

Keys must match.

### 3. Clear Cache
```bash
php artisan config:cache
php artisan route:cache
```

### 4. Test (Optional but Recommended)
```bash
php artisan tinker
>>> include 'EMPLOYEE_SYNC_TEST.php';
```

## Testing Procedures

### Manual API Test
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

### Test via HR Portal UI
1. Log in to `https://hr.hellotransport.com`
2. Create new employee
3. Check washinton_agent for newly created user
4. Verify `hr_employee_id` is set to employee ID

### Verify Logs
```bash
# washinton_agent
tail -f storage/logs/laravel.log | grep -i "employee"

# washinton_hr
tail -f storage/logs/laravel.log | grep -i "observer"
```

## Error Handling

| Scenario | washinton_hr | washinton_agent | Action |
|----------|-------------|------------------|--------|
| Valid sync | ✓ Employee created | ✓ User created | Success |
| Bridge key mismatch | ✓ Employee created | ✗ 403 error | Log warning, retry manually |
| Email exists | ✓ Employee created | ✓ User updated | Update instead of create |
| Network error | ✓ Employee created | ✗ Timeout | Log error, manual sync needed |
| DB error | ✓ Employee created | ✗ 500 error | Log error, transaction rolled back |

**Key Point**: HR operation never fails due to sync errors (non-blocking).

## Logging

### Success Log (washinton_agent)
```
[INFO] EmployeeSyncController: Employee synced successfully
  hr_employee_id: 1
  user_id: 42
  email: john@example.com
  role_id: 5
```

### Error Log (washinton_hr)
```
[ERROR] EmployeeObserver: Failed to sync employee to washinton_agent
  employee_id: 1
  email: john@example.com
  error: Connection timeout
```

## Role Creation Logic

When syncing an employee:

1. **Check if role exists by name**
   ```sql
   SELECT id FROM roles WHERE name = 'Agent'
   ```

2. **If found**: Use existing role ID

3. **If not found**: Create new role
   ```php
   role::create([
       'name'        => 'Agent',
       'slug'        => 'agent',  // Str::slug()
       'description' => 'Synced from HR',
       'level'       => 5,
   ])
   ```

## Important Notes

1. **Bridge Key Security**:
   - Keys stored in .env (not code)
   - Passed in X-Bridge-Key header (not URL)
   - Case-sensitive, must match exactly

2. **Non-Blocking Design**:
   - HR operation succeeds even if sync fails
   - All errors logged for manual review
   - Admin can manually trigger sync if needed

3. **User Creation**:
   - Default status: inactive (admin must activate)
   - Default role: matched from HR or created new
   - Default permissions: copied from reference user (id=130)
   - Password: random, admin can reset

4. **Update Behavior**:
   - If user exists by email: updates instead of creates
   - HR `updated()` observer only syncs if key fields change
   - Prevents unnecessary API calls

5. **Database Constraints**:
   - `hr_employee_id` is unique (prevents duplicates)
   - Foreign key to hr_employees table (if available)
   - Nullable (can have users not from HR)

## Monitoring Checklist

- [ ] Migration applied successfully
- [ ] Bridge keys configured in both .env files
- [ ] Routes visible in `php artisan route:list`
- [ ] Observer registered in AppServiceProvider
- [ ] Test employee creation in HR
- [ ] Verify user created in Agent
- [ ] Check logs for any errors
- [ ] Test role creation (sync employee with new role)
- [ ] Test user update (modify and save HR employee)
- [ ] Verify permissions copied correctly

## Future Enhancements

1. Sync employee deletion (soft delete in Agent)
2. Sync employee status changes
3. Sync documents from HR to Agent
4. Bulk sync endpoint for all employees
5. HMAC signature verification for bridge requests
6. Webhook retry logic with exponential backoff
7. Status endpoint to check sync health

## Support

For issues:
1. Check logs in `storage/logs/laravel.log`
2. Verify bridge key configuration
3. Test endpoint directly with curl
4. Check network connectivity between servers
5. Review EMPLOYEE_SYNC_SETUP.md for detailed troubleshooting

## Files Reference

| Path | Purpose |
|------|---------|
| `database/migrations/2026_06_07_add_hr_employee_id_to_users.php` | Schema change |
| `app/Http/Controllers/Bridge/EmployeeSyncController.php` | Bridge endpoint |
| `routes/web.php` | Route definition |
| `app/User.php` | Model configuration |
| `.env` | Bridge key |
| (HR) `app/Observers/EmployeeObserver.php` | Event listener |
| (HR) `app/Providers/AppServiceProvider.php` | Observer registration |
| (HR) `.env` | Bridge URL & key |
