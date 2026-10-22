-- =====================================================================================
-- Hello Transport: Onsite panel (client email, 22 Oct 2026)
--   1) a panel named Onsite
--   2) Onsite agents see every quote and order in the panel (All quotes)
--   3) the Autos Approach folder shows in the Onsite panel (needs the code deploy too)
--   4) ShipA1's Auto Approach data on Hello
--
-- Section A runs on the HELLO database (hellotransport_databases).
-- Section B runs on the SHIPA1 database (washington.shawntransport.com, shipa16_main): READ ONLY.
-- Every statement looks the panel up by name and can be run again safely.
-- Take a backup of `user`, `user_panel_access`, `user_settings`, `panel_types`,
-- `used_new_car_dealers` before running.
-- =====================================================================================


-- ======================== A. HELLO DATABASE ==========================================

-- A1. The Onsite panel (same as Panel Types > Add in the admin menu).
INSERT INTO panel_types (name, is_system, is_default, sort, status, created_at, updated_at)
SELECT 'Onsite', 0, 0, s.next_sort, 1, NOW(), NOW()
FROM (SELECT COALESCE(MAX(sort), 0) + 1 AS next_sort FROM panel_types) s
WHERE NOT EXISTS (SELECT 1 FROM panel_types WHERE name = 'Onsite');

SELECT id AS onsite_panel_id FROM panel_types WHERE name = 'Onsite';   -- expect 12


-- A2. Autos Approach only in Onsite: take permission 94 (Access Auto Approach) off every
--     other panel. Today only the admin and the two managers have it.
UPDATE user SET
    emp_access_phone = TRIM(BOTH ',' FROM REPLACE(CONCAT(',', emp_access_phone, ','), ',94,', ',')),
    emp_access_web   = TRIM(BOTH ',' FROM REPLACE(CONCAT(',', emp_access_web,   ','), ',94,', ',')),
    emp_access_test  = TRIM(BOTH ',' FROM REPLACE(CONCAT(',', emp_access_test,  ','), ',94,', ',')),
    panel_type_4     = TRIM(BOTH ',' FROM REPLACE(CONCAT(',', panel_type_4,     ','), ',94,', ',')),
    panel_type_5     = TRIM(BOTH ',' FROM REPLACE(CONCAT(',', panel_type_5,     ','), ',94,', ',')),
    panel_type_6     = TRIM(BOTH ',' FROM REPLACE(CONCAT(',', panel_type_6,     ','), ',94,', ','))
WHERE FIND_IN_SET('94', emp_access_phone) OR FIND_IN_SET('94', emp_access_web) OR FIND_IN_SET('94', emp_access_test)
   OR FIND_IN_SET('94', panel_type_4) OR FIND_IN_SET('94', panel_type_5) OR FIND_IN_SET('94', panel_type_6);

UPDATE user_panel_access a
JOIN panel_types p ON p.name = 'Onsite'
SET a.access_ids = TRIM(BOTH ',' FROM REPLACE(CONCAT(',', a.access_ids, ','), ',94,', ',')), a.updated_at = NOW()
WHERE a.panel_type_id <> p.id AND FIND_IN_SET('94', a.access_ids);


-- A3. Admin and managers: Onsite in their panel list, with their Lahore permissions plus
--     94 (Auto Approach), 145 (Auto Approach Assign) and 146 (Auto Approach Filter).
UPDATE user u
JOIN panel_types p ON p.name = 'Onsite'
SET u.emp_panel_access = CONCAT_WS(',', NULLIF(u.emp_panel_access, ''), p.id)
WHERE u.role IN (1, 9) AND u.deleted = 0
  AND NOT FIND_IN_SET(p.id, COALESCE(u.emp_panel_access, ''));

INSERT INTO user_panel_access (user_id, panel_type_id, access_ids, created_at, updated_at)
SELECT u.id, p.id, COALESCE(u.emp_access_phone, ''), NOW(), NOW()
FROM user u JOIN panel_types p ON p.name = 'Onsite'
WHERE u.role IN (1, 9) AND u.deleted = 0
ON DUPLICATE KEY UPDATE updated_at = NOW();

UPDATE user_panel_access a
JOIN panel_types p ON p.id = a.panel_type_id AND p.name = 'Onsite'
JOIN user u ON u.id = a.user_id AND u.role IN (1, 9) AND u.deleted = 0
SET a.access_ids = CONCAT_WS(',', NULLIF(a.access_ids, ''), '94')
WHERE NOT FIND_IN_SET('94', COALESCE(a.access_ids, ''));

UPDATE user_panel_access a
JOIN panel_types p ON p.id = a.panel_type_id AND p.name = 'Onsite'
JOIN user u ON u.id = a.user_id AND u.role IN (1, 9) AND u.deleted = 0
SET a.access_ids = CONCAT_WS(',', NULLIF(a.access_ids, ''), '145')
WHERE NOT FIND_IN_SET('145', COALESCE(a.access_ids, ''));

UPDATE user_panel_access a
JOIN panel_types p ON p.id = a.panel_type_id AND p.name = 'Onsite'
JOIN user u ON u.id = a.user_id AND u.role IN (1, 9) AND u.deleted = 0
SET a.access_ids = CONCAT_WS(',', NULLIF(a.access_ids, ''), '146')
WHERE NOT FIND_IN_SET('146', COALESCE(a.access_ids, ''));


-- A4. Onsite AGENTS. Find their ids first:
SELECT id, name, last_name, email, role FROM user WHERE deleted = 0 AND role IN (2, 25) ORDER BY id DESC;
--     Then replace the 0 in "IN (0)" in the FOUR statements below with those ids, e.g. IN (512, 513).
--     Left as 0 they change nothing.

-- A4a. Onsite in their panel list + see ALL quotes/orders of the panel (not only their own).
UPDATE user u
JOIN panel_types p ON p.name = 'Onsite'
SET u.emp_panel_access = IF(FIND_IN_SET(p.id, COALESCE(u.emp_panel_access, '')), u.emp_panel_access,
                            CONCAT_WS(',', NULLIF(u.emp_panel_access, ''), p.id)),
    u.order_taker_quote = 0
WHERE u.id IN (0);

-- A4b. Their Onsite permissions = their Lahore permissions + 94 (the Autos Approach folder).
INSERT INTO user_panel_access (user_id, panel_type_id, access_ids, created_at, updated_at)
SELECT u.id, p.id,
       IF(FIND_IN_SET('94', COALESCE(u.emp_access_phone, '')), u.emp_access_phone,
          CONCAT_WS(',', NULLIF(u.emp_access_phone, ''), '94')),
       NOW(), NOW()
FROM user u JOIN panel_types p ON p.name = 'Onsite'
WHERE u.id IN (0)
ON DUPLICATE KEY UPDATE
    access_ids = IF(FIND_IN_SET('94', COALESCE(access_ids, '')), access_ids, CONCAT_WS(',', NULLIF(access_ids, ''), '94')),
    updated_at = NOW();

-- A4c/d. Put them in the Onsite panel now (they can also switch from the panel dropdown).
UPDATE user_settings s
JOIN panel_types p ON p.name = 'Onsite'
SET s.penal_type = p.id, s.updated_at = NOW()
WHERE s.user_id IN (0);

INSERT INTO user_settings (user_id, penal_type, created_at, updated_at)
SELECT u.id, p.id, NOW(), NOW()
FROM user u JOIN panel_types p ON p.name = 'Onsite'
WHERE u.id IN (0) AND NOT EXISTS (SELECT 1 FROM user_settings s WHERE s.user_id = u.id);


-- A5. Auto Approach data. Hello already holds ShipA1's list up to id 641416 (copied in May;
--     rows 1..641416 are identical on both). Import ShipA1's newer rows (section B), then run:

-- A5a. 12,284 rows still carry ShipA1 user ids, so they can't be assigned on Hello (and could
--      show to a Hello agent with the same id). Make them assignable again. Does nothing if
--      anything has already been assigned on Hello.
UPDATE used_new_car_dealers
SET user_id = 0
WHERE (user_id IS NULL OR user_id <> 0)
  AND NOT EXISTS (SELECT 1 FROM assignUsedAndNewOrderTaker);

-- A5b. Check.
SELECT COUNT(*) AS total, MAX(id) AS max_id, MAX(created_at) AS newest,
       SUM(user_id <> 0) AS assigned
FROM used_new_car_dealers;


-- A6. Check the result.
SELECT u.id, u.name, u.role, u.emp_panel_access, u.order_taker_quote, a.access_ids AS onsite_access
FROM user u
JOIN panel_types p ON p.name = 'Onsite'
LEFT JOIN user_panel_access a ON a.user_id = u.id AND a.panel_type_id = p.id
WHERE u.deleted = 0 AND FIND_IN_SET(p.id, u.emp_panel_access);


-- ======================== B. SHIPA1 DATABASE (read only) ==============================

-- B1. How many Auto Approach rows ShipA1 has that Hello doesn't.
SELECT COUNT(*) AS new_rows, MIN(id), MAX(id), MAX(created_at)
FROM used_new_car_dealers
WHERE id > 641416;

-- B2. Export those rows, then import the file on the HELLO database (phpMyAdmin > Import),
--     then run A5a there. From SSH on the washington server:
--
--   mysqldump -u shipa16_main -p --no-create-info --insert-ignore --skip-triggers \
--     --where="id > 641416" shipa16_main used_new_car_dealers > auto_approach_new.sql
--
--   (or in SQLyog: run the SELECT below on ShipA1, Export result as "SQL statements",
--    table name used_new_car_dealers, and run the file on Hello)
--
-- SELECT * FROM used_new_car_dealers WHERE id > 641416;
