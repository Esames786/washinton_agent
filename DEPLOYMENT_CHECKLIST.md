# Employee Sync - Deployment Checklist

## Pre-Deployment Verification

### Code Changes
- [x] Migration file created: `2026_06_07_add_hr_employee_id_to_users.php`
- [x] Controller created: `Bridge/EmployeeSyncController.php`
- [x] Observer created: (washinton_hr) `EmployeeObserver.php`
- [x] Route added: `POST /bridge/employee/sync`
- [x] User model updated: `$fillable` array
- [x] AppServiceProvider updated: Observer registered

### Configuration
- [x] washinton_agent .env: `HELLOTRANSPORT_BRIDGE_SHARED_KEY` added
- [x] washinton_hr .env: `HELLOTRANSPORT_BRIDGE_URL` added
- [x] washinton_hr .env: `HELLOTRANSPORT_BRIDGE_KEY` added
- [x] Both bridge keys are identical

### Documentation
- [x] IMPLEMENTATION_SUMMARY.md created
- [x] EMPLOYEE_SYNC_SETUP.md created (washinton_agent)
- [x] EMPLOYEE_SYNC_SETUP.md created (washinton_hr)
- [x] EMPLOYEE_SYNC_TEST.php created
- [x] QUICK_START.md created
- [x] DEPLOYMENT_CHECKLIST.md created (this file)

## Pre-Production Testing

### Step 1: Verify Files Exist
```bash
# washinton_agent
test -f "app/Http/Controllers/Bridge/EmployeeSyncController.php" && echo "✓ Controller"
test -f "database/migrations/2026_06_07_add_hr_employee_id_to_users.php" && echo "✓ Migration"

# washinton_hr
test -f "app/Observers/EmployeeObserver.php" && echo "✓ Observer"
```

### Step 2: Verify Environment Variables
```bash
# Both should print the same key
grep "HELLOTRANSPORT_BRIDGE" d:\laragon2\www\washinton_agent\.env
grep "HELLOTRANSPORT_BRIDGE" d:\laragon2\www\washinton_hr\.env
```

### Step 3: Run Migration
```bash
cd d:\laragon2\www\washinton_agent
php artisan migrate --force
```
Expected: "Migrated: 2026_06_07_add_hr_employee_id_to_users"

### Step 4: Clear Caches
```bash
php artisan config:cache
php artisan route:cache
```

### Step 5: Verify Routes
```bash
php artisan route:list | grep "bridge/employee"
```
Should show: `POST /bridge/employee/sync Bridge/EmployeeSyncController@syncEmployee`

### Step 6: Run Diagnostic Test
```bash
php artisan tinker
>>> include 'EMPLOYEE_SYNC_TEST.php';
```
All tests should pass (✓).

### Step 7: Test API Endpoint
```bash
curl -X POST https://hellotransport.com/bridge/employee/sync \
  -H "X-Bridge-Key: c689d5166a3fe6a0772ce020b14c2e0d980559f4de3e80617d617383ac592cf5" \
  -H "Content-Type: application/json" \
  -d '{
    "employee_id": 999,
    "first_name": "Test",
    "last_name": "Sync",
    "email": "test.sync@example.com",
    "phone": "0300-1234567",
    "role_id": 1,
    "role_name": "Test Role"
  }'
```
Expected: `{"success": true, "message": "Employee synced successfully.", ...}`

### Step 8: Create Test Employee in HR
1. Log in to HR portal
2. Create employee with valid details
3. Check Agent portal for new user
4. Verify hr_employee_id is set

### Step 9: Check Logs
```bash
tail -f storage/logs/laravel.log | grep -i employee
```
Should see sync messages.

### Step 10: Test User Update
1. Update HR employee (e.g., phone)
2. Check Agent user is updated
3. Check logs for "updated" message

## Deployment Steps

### 1. Backup Database
```sql
mysqldump -u user -p washinton_agent > backup_2026_06_07.sql
mysqldump -u user -p shiap16_main2 > backup_hr_2026_06_07.sql
```

### 2. Deploy Code
- Copy `EmployeeSyncController.php` to washinton_agent
- Copy migration file to washinton_agent
- Copy `EmployeeObserver.php` to washinton_hr
- Update `AppServiceProvider.php` in washinton_hr
- Update route file in washinton_agent

### 3. Update Configuration
- Add/update bridge keys in .env files
- Verify keys match exactly

### 4. Run Migration
```bash
php artisan migrate --force
```

### 5. Clear Caches
```bash
php artisan config:cache
php artisan route:cache
```

### 6. Restart Services
```bash
# Restart PHP-FPM or Apache
sudo systemctl restart php-fpm
# or
sudo systemctl restart apache2
```

### 7. Verify Deployment
```bash
php artisan route:list | grep bridge/employee
php artisan migrate --status
```

### 8. Test in Production
```bash
curl -X POST https://hellotransport.com/bridge/employee/sync \
  -H "X-Bridge-Key: YOUR_PRODUCTION_KEY" \
  -H "Content-Type: application/json" \
  -d '{"employee_id": 1, ...}'
```

## Post-Deployment Monitoring

### Hour 1
- [ ] Check application logs for errors
- [ ] Verify endpoint responds correctly
- [ ] Create test employee in HR
- [ ] Verify sync to Agent

### Day 1
- [ ] Monitor HR and Agent logs
- [ ] Verify all employee syncs successful
- [ ] Check user counts match
- [ ] Verify role creation working

### Week 1
- [ ] Review sync logs for any errors
- [ ] Test role creation (sync new role)
- [ ] Test update sync (modify employee)
- [ ] Check database integrity

### Ongoing
- [ ] Monitor logs daily
- [ ] Set up alerts for sync failures
- [ ] Review performance metrics
- [ ] Document any issues

## Rollback Plan

If deployment fails or major issues discovered:

### Quick Rollback (1 minute)
```bash
# Remove route (comment out in web.php)
# OR restart with old code
git checkout routes/web.php
php artisan route:cache
```

### Full Rollback (5 minutes)
```bash
# Reverse migration
php artisan migrate:rollback

# Restore from backup
mysql -u user -p washinton_agent < backup_2026_06_07.sql
```

### Complete Rollback
1. Restore database from backup
2. Revert code changes in both projects
3. Clear caches
4. Restart services

## Success Criteria

After deployment, verify:

- [x] Migration applied successfully
- [x] Route registered and accessible
- [x] Controller instantiable
- [x] Observer registered
- [x] Bridge key configured correctly
- [x] API endpoint responds to requests
- [x] Test employee syncs successfully
- [x] User created with correct hr_employee_id
- [x] Role created if new
- [x] Permissions copied correctly
- [x] Logs show success messages
- [x] No errors in application logs

## Known Issues & Mitigation

### Issue 1: Bridge Key Mismatch
**Symptom**: 403 Forbidden errors
**Mitigation**: Double-check .env keys match exactly
**Prevention**: Script to validate keys before deployment

### Issue 2: Network Connection
**Symptom**: Timeout errors in HR logs
**Mitigation**: Check firewall rules between servers
**Prevention**: Pre-deployment network connectivity test

### Issue 3: Database Constraint Violation
**Symptom**: Migration fails with constraint error
**Mitigation**: Already handled in migration (nullable, unique on hr_employee_id)
**Prevention**: Pre-test on staging database

### Issue 4: Role Creation Fails
**Symptom**: User created but role_id incorrect
**Mitigation**: Observer creates role if not found
**Prevention**: All role names must match between HR and Agent

## Communication Plan

### Before Deployment
- Notify tech team: "Employee sync feature deploying"
- Prepare rollback plan
- Schedule deployment window (low usage)

### During Deployment
- Monitor logs in real-time
- Have rollback ready if issues occur
- Test endpoint immediately after

### After Deployment
- Announce feature live to HR team
- Provide testing instructions
- Monitor for issues

### If Issues
- Implement rollback immediately
- Notify team of issue
- Document problem for post-mortem

## Long-Term Maintenance

### Weekly
- Check logs for errors or warnings
- Verify sync success rates
- Review performance metrics

### Monthly
- Update documentation if needed
- Review and clean up old logs
- Test manual sync procedure

### Quarterly
- Performance optimization review
- Security audit of bridge endpoints
- Consider enhancements (deletion sync, etc.)

## Appendix: File Checklist

### washinton_agent Changes
```
database/migrations/
├── 2026_06_07_add_hr_employee_id_to_users.php ✓

app/Http/Controllers/Bridge/
├── EmployeeSyncController.php ✓

app/
├── User.php (modified $fillable) ✓

routes/
├── web.php (added /bridge/employee/sync) ✓

config/
├── .env (added HELLOTRANSPORT_BRIDGE_SHARED_KEY) ✓

Documentation/
├── QUICK_START.md ✓
├── IMPLEMENTATION_SUMMARY.md ✓
├── EMPLOYEE_SYNC_SETUP.md ✓
├── EMPLOYEE_SYNC_TEST.php ✓
├── DEPLOYMENT_CHECKLIST.md ✓
```

### washinton_hr Changes
```
app/Observers/
├── EmployeeObserver.php ✓

app/Providers/
├── AppServiceProvider.php (modified, added observer) ✓

config/
├── .env (added HELLOTRANSPORT_BRIDGE_URL & KEY) ✓

Documentation/
├── EMPLOYEE_SYNC_SETUP.md ✓
```

---

**Deployment Date**: [To be filled]
**Deployed By**: [Name]
**Approved By**: [Name]
**Rollback Required**: [ ] Yes [ ] No
**Issues Encountered**: [To be filled if any]

---

Status: **Ready for Deployment**
Last Updated: 2026-06-07
Version: 1.0.0
