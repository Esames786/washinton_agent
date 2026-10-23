-- =====================================================================================
-- Florida: both managers can add, edit and update subcontractor and manager accounts
-- (client, 23 Oct 2026). Run on the FLORIDA database (crazyrayssolutio_crazzy).
-- Grants permission 20 "Add/Edit Subcontractor" on every panel. Safe to run again.
-- The code side (dashboard nav shows the subcontractor list to managers) is crazyrays ae0e81f.
-- =====================================================================================

-- 1. Check: both should be role 9 (Manager), status 1, deleted 0.
SELECT id, name, last_name, email, role, status, deleted, emp_panel_access
FROM user
WHERE email IN ('newmanager@gmail.com', 'manager@hellotransport.com');

-- 2. Permission 20 on panels 1-6 (columns) ...
UPDATE user SET
    emp_access_phone = IF(FIND_IN_SET('20', COALESCE(emp_access_phone, '')), emp_access_phone, CONCAT_WS(',', NULLIF(emp_access_phone, ''), '20')),
    emp_access_web   = IF(FIND_IN_SET('20', COALESCE(emp_access_web,   '')), emp_access_web,   CONCAT_WS(',', NULLIF(emp_access_web,   ''), '20')),
    emp_access_test  = IF(FIND_IN_SET('20', COALESCE(emp_access_test,  '')), emp_access_test,  CONCAT_WS(',', NULLIF(emp_access_test,  ''), '20')),
    panel_type_4     = IF(FIND_IN_SET('20', COALESCE(panel_type_4,     '')), panel_type_4,     CONCAT_WS(',', NULLIF(panel_type_4,     ''), '20')),
    panel_type_5     = IF(FIND_IN_SET('20', COALESCE(panel_type_5,     '')), panel_type_5,     CONCAT_WS(',', NULLIF(panel_type_5,     ''), '20')),
    panel_type_6     = IF(FIND_IN_SET('20', COALESCE(panel_type_6,     '')), panel_type_6,     CONCAT_WS(',', NULLIF(panel_type_6,     ''), '20'))
WHERE email IN ('newmanager@gmail.com', 'manager@hellotransport.com') AND role = 9 AND deleted = 0;

-- 3. ... and on panels 7+ (one row per panel).
UPDATE user_panel_access a
JOIN user u ON u.id = a.user_id
SET a.access_ids = IF(FIND_IN_SET('20', COALESCE(a.access_ids, '')), a.access_ids, CONCAT_WS(',', NULLIF(a.access_ids, ''), '20')),
    a.updated_at = NOW()
WHERE u.email IN ('newmanager@gmail.com', 'manager@hellotransport.com') AND u.role = 9 AND u.deleted = 0;

-- 4. Check: has20 should be 1 on every row.
SELECT u.email, 'panels 1-6' AS panel, FIND_IN_SET('20', u.emp_access_phone) > 0 AS has20
FROM user u WHERE u.email IN ('newmanager@gmail.com', 'manager@hellotransport.com')
UNION ALL
SELECT u.email, a.panel_type_id, FIND_IN_SET('20', a.access_ids) > 0
FROM user_panel_access a JOIN user u ON u.id = a.user_id
WHERE u.email IN ('newmanager@gmail.com', 'manager@hellotransport.com');
