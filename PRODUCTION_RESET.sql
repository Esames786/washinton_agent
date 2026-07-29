-- =====================================================================
-- PRODUCTION DATA RESET  —  hellotransport_databases  (shared Agent + HR DB)
-- Built from the LIVE schema (255 tables) on 2026-07-28.
--
-- KEEPS:  • user rows with role IN (1 = Admin, 9 = Manager)  [ids 1, 373, 416]
--         • user ids 53 (Reference Dispatcher) + 130 (Reference Order Taker) — signup
--           templates the code copies permissions from (KEEP or new signups break)
--         • ALL config / lookup tables (roles, pstatus, panels, field labels,
--           HR settings/types/permissions, commission/gratuity/tax settings, etc.)
--         • hr_admins (HR admin/manager accounts)
--         • Reference / scraped datasets (zipcodes, dealers, shipper_details_*,
--           wp_vehiclelisting*, carriers_archive, ips)  — see REVIEW section
--         • Admin/Manager functional rows (their user_settings / panel_access / RC)
--
-- DELETES: all orders + everything order-linked, all HR employees + everything
--          employee-linked, all CR applications, all non-admin/manager users and
--          their operational/activity data.
--
-- ⚠️  BEFORE RUNNING:
--   1. TAKE A FULL BACKUP:  mysqldump -h 199.250.220.27 -u hellotransport_databases -p
--        hellotransport_databases > backup_before_reset.sql
--   2. Run on a COPY first if possible.
--   3. This is wrapped in a transaction: review the counts at the end, then COMMIT
--      (or ROLLBACK if anything looks wrong). Nothing is permanent until COMMIT.
-- =====================================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_SAFE_UPDATES = 0;
START TRANSACTION;

-- ------------------------------------------------------------------
-- 1) ORDERS + everything order-linked
-- ------------------------------------------------------------------
DELETE FROM `order`;
DELETE FROM `order_backup`;
DELETE FROM `order_backup2`;
DELETE FROM `order_freight`;
DELETE FROM `order_history`;
DELETE FROM `order_history_backup`;
DELETE FROM `order_feedbacks`;
DELETE FROM `order_price_requests`;
DELETE FROM `order_quote_status`;
DELETE FROM `order_website_emails`;
DELETE FROM `order_payment_journeys`;
DELETE FROM `order_payment_logs`;
DELETE FROM `order_payments`;
DELETE FROM `orderpayments`;
DELETE FROM `order_taker_client_accesses`;
DELETE FROM `order_taker_qoute_accesses`;
DELETE FROM `auto_order_histories`;
DELETE FROM `creditcards`;
DELETE FROM `invoices`;
DELETE FROM `invoice_roros`;
DELETE FROM `sell_invoices`;
DELETE FROM `nature_of_customer`;
DELETE FROM `not_respond_orders`;
DELETE FROM `owes_histories`;
DELETE FROM `payment_logs`;
DELETE FROM `payment_system`;
DELETE FROM `port_track_history`;
DELETE FROM `price_checker_prices`;
DELETE FROM `profit`;
DELETE FROM `public_orders`;
DELETE FROM `public_order_chats`;
DELETE FROM `qa_verify_histories`;
DELETE FROM `q_n_a_orders`;
DELETE FROM `questions`;
DELETE FROM `question_anwsers`;
DELETE FROM `ratings`;
DELETE FROM `request_checker`;
DELETE FROM `request_shipments`;
DELETE FROM `transfer_quotes`;
DELETE FROM `demand_vehicles`;
DELETE FROM `report`;
DELETE FROM `reports`;
DELETE FROM `report_new`;
DELETE FROM `singlereports`;
DELETE FROM `agents_report`;
DELETE FROM `today_agent_report`;
DELETE FROM `flags`;
DELETE FROM `sheet_details`;
DELETE FROM `storages`;
DELETE FROM `special_instructions`;
DELETE FROM `profile_cards`;

-- carriers (per-order carrier assignments + related history)  [carriers_archive is KEPT below]
DELETE FROM `carriers`;
DELETE FROM `carriers_company`;
DELETE FROM `carrier_approachings`;
DELETE FROM `carrier_click`;
DELETE FROM `carrier_old_order_histories`;
DELETE FROM `count_carrier_history`;
DELETE FROM `count_clicks`;
DELETE FROM `count_days`;
DELETE FROM `historyblockcompany`;
DELETE FROM `historyusedandnew`;
DELETE FROM `historyusedandnew_whatsapp`;
DELETE FROM `callcountusedandnew`;
DELETE FROM `whatsapp_autoApproach_count`;

-- order chats / calls / messages
DELETE FROM `call_histories`;
DELETE FROM `message_calls`;
DELETE FROM `message_chats`;
DELETE FROM `chat_show_hides`;
DELETE FROM `custom_chats`;
DELETE FROM `run_time_chats`;
DELETE FROM `chats`;
DELETE FROM `chat_mains`;
DELETE FROM `group_chats`;
DELETE FROM `groups`;
DELETE FROM `group_users`;
DELETE FROM `public_order_chats`;
DELETE FROM `issue_chats`;
DELETE FROM `issues`;
DELETE FROM `thread_tables`;

-- shipa website queries / leads (order-taker linked)
DELETE FROM `shipa_query`;
DELETE FROM `shipaquery_assign`;
DELETE FROM `shipaquery_histories`;
DELETE FROM `shipa_phone`;
DELETE FROM `instant_quotes`;
DELETE FROM `approaching_assign`;
DELETE FROM `assignUsedAndNewOrderTaker`;

-- authorizations / bonuses (order/customer linked)
DELETE FROM `authorization_form_images`;
DELETE FROM `authorization_form`;
DELETE FROM `auction_detail`;
DELETE FROM `cancel_bonus`;
DELETE FROM `first_bonus`;
DELETE FROM `second_bonus`;

-- ------------------------------------------------------------------
-- 2) HR EMPLOYEES + everything employee-linked   (HR config/lookup is KEPT)
-- ------------------------------------------------------------------
DELETE FROM `hr_employee_assign_leaves`;
DELETE FROM `hr_employee_attendances`;
DELETE FROM `hr_employee_attendance_requests`;
DELETE FROM `hr_employee_bank_details`;
DELETE FROM `hr_employee_breaks`;
DELETE FROM `hr_employee_daily_activities`;
DELETE FROM `hr_employee_documents`;
DELETE FROM `hr_employee_holiday_exceptions`;
DELETE FROM `hr_employee_leaves`;
DELETE FROM `hr_employee_payslips`;
DELETE FROM `hr_employee_status_histories`;
DELETE FROM `hr_employee_taxes`;
DELETE FROM `hr_employee_ticket_attachments`;
DELETE FROM `hr_employee_tickets`;
DELETE FROM `hr_employee_working_days`;
DELETE FROM `hr_employee_work_equipment`;
DELETE FROM `employee_equipment`;
DELETE FROM `hr_gratuity_balances`;
DELETE FROM `hr_gratuity_payouts`;
DELETE FROM `hr_payroll_details`;
DELETE FROM `hr_payrolls`;
DELETE FROM `hr_payslip_adjustments`;
DELETE FROM `hr_payslip_items`;
DELETE FROM `hr_petty_cash_master_histories`;
DELETE FROM `hr_petty_cash_transactions`;
DELETE FROM `hr_petty_cash_masters`;
DELETE FROM `hr_ticket_messages`;
DELETE FROM `hr_sso_tokens`;
DELETE FROM `hr_admin_sso_tokens`;
DELETE FROM `hr_user_screenshots`;
DELETE FROM `hr_employees`;

-- ------------------------------------------------------------------
-- 3) CR APPLICATIONS
-- ------------------------------------------------------------------
DELETE FROM `cr_applications`;

-- ------------------------------------------------------------------
-- 4) NON-ADMIN/MANAGER USERS + their operational / activity data
--    (Admin=1, Manager=9 are KEPT, along with their settings/panel/RC below)
-- ------------------------------------------------------------------
-- 4a. Per-user activity / logs — full wipe (all of it is for removed users or is a log)
DELETE FROM `activities`;
DELETE FROM `agent_active_times`;
DELETE FROM `attendances`;
DELETE FROM `break_times`;
DELETE FROM `last_activities`;
DELETE FROM `user_login_activities`;
DELETE FROM `user_screen_shots`;
DELETE FROM `user_targets`;
DELETE FROM `user_commission`;
DELETE FROM `daily_qoutes`;
DELETE FROM `notes`;
DELETE FROM `notifications`;
DELETE FROM `freeze_users`;
DELETE FROM `freeze_users_archive`;
DELETE FROM `logout_questions_answers`;
DELETE FROM `logout_question_comments`;
DELETE FROM `logout_questions`;
DELETE FROM `webphone_instances`;
DELETE FROM `ringcentral_call_logs`;
DELETE FROM `ringcentral_messages`;
DELETE FROM `ringcentral_voicemails`;
DELETE FROM `ringcentral_dialer_blocked_numbers`;
DELETE FROM `sheets`;
DELETE FROM `sheet_data`;
DELETE FROM `excel_sheets`;
DELETE FROM `rules`;
DELETE FROM `email_folders`;
DELETE FROM `email_messages`;
DELETE FROM `email_message_attachments`;
DELETE FROM `email_histories`;
DELETE FROM `email_inline_uploads`;
DELETE FROM `email_accounts`;
DELETE FROM `send_template_emails`;
DELETE FROM `last_sent_email_timestamps`;
DELETE FROM `daily_email_limits`;

-- 4b. Functional per-user tables — KEEP Admin/Manager + the 2 signup reference users
--     (130 = Reference Order Taker, 53 = Reference Dispatcher — the signup flow does
--      User::find(130/53) to copy permissions from, so they MUST stay).
DELETE FROM `user_settings`       WHERE user_id  NOT IN (SELECT id FROM (SELECT id FROM `user` WHERE role IN (1,9) OR id IN (53,130)) k);
DELETE FROM `user_panel_access`   WHERE user_id  NOT IN (SELECT id FROM (SELECT id FROM `user` WHERE role IN (1,9) OR id IN (53,130)) k);
DELETE FROM `ringcentral_users`   WHERE user_id  NOT IN (SELECT id FROM (SELECT id FROM `user` WHERE role IN (1,9) OR id IN (53,130)) k);

-- 4c. Finally the users themselves — keep ONLY Admin + Manager + the 2 signup reference users
DELETE FROM `user` WHERE role NOT IN (1, 9) AND id NOT IN (53, 130);

-- ------------------------------------------------------------------
-- 5) VERIFY, then COMMIT (or ROLLBACK)
-- ------------------------------------------------------------------
SELECT (SELECT COUNT(*) FROM `user`)             AS users_left,
       (SELECT COUNT(*) FROM `order`)            AS orders_left,
       (SELECT COUNT(*) FROM `hr_employees`)     AS hr_employees_left,
       (SELECT COUNT(*) FROM `cr_applications`)  AS cr_apps_left;
-- Expect: users_left = 5 (admin 1 + managers 373,416 + reference 53,130),
--         orders_left = 0, hr_employees_left = 0, cr_apps_left = 0

COMMIT;                 -- <-- run this ONLY if the counts look right; otherwise: ROLLBACK;
SET FOREIGN_KEY_CHECKS = 1;
SET SQL_SAFE_UPDATES = 1;

-- =====================================================================
-- KEPT (config / lookup / reference) — intentionally NOT deleted:
--   migrations, roles, role_accesses, panel_types, pstatus, field_labels,
--   old_field_labels, site_settings, general_settings, signup_defaults,
--   get_cachee, phone_digits, commission_ranges, mile_price, price_range,
--   port_details, port_prices, offer_prices, vehicle_extra, productivity_rules,
--   review_website_links, contract_templates, templates, email_template, guide,
--   guide_videos, cr_campaigns, equipment_types, coupons, cpanel_emails,
--   password_resets, logins, block_phones, ips,
--   hr_admins, ALL hr_* CONFIG (hr_roles, hr_permissions, hr_role_has_permissions,
--     hr_model_has_*, hr_departments, hr_designations, hr_shift_types,
--     hr_shift_attendance_rules, hr_document_settings, hr_leave_types, hr_holidays,
--     hr_commission_*, hr_gratuity_settings, hr_gratuity_payout_statuses,
--     hr_tax_slab_settings, hr_employee_statuses, hr_employment_types,
--     hr_employee_account_types, hr_attendance_statuses, hr_ticket_statuses,
--     hr_ticket_types, hr_payroll_statuses, hr_payroll_detail_statuses,
--     hr_payslip_item_types, hr_daily_activity_fields, hr_role_activity_fields,
--     hr_role_commission_settings, hr_role_gratuity_settings, hr_petty_cash_heads,
--     hr_currency_rates)
--
-- REVIEW — big reference/scraped datasets, KEPT by default. If you also want these
-- emptied, tell me and I'll add them:
--   zipcodes (~43k), used_new_car_dealers (~632k), shipper_details_fetch (~682k),
--   shipper_details_fetch_edge (~68k), shipper_details_carriers (~23k),
--   shipper_details_phone*, wp_vehiclelisting (~117k), wp_vehiclelistings,
--   wp_priceperm, wp_general_exception, carriers_archive (~38k)
-- =====================================================================
