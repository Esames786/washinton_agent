# Batch 7 — Issues (investigation + plan)

Status legend: 🔴 bug/urgent · 🟡 change · ❓ needs decision. Investigate = file located; fix pending approval.

---

## ‼️ P0 — Payment stuck at "Confirmation Pending" after admin confirms (Hello / washinton_agent)
**CONFIRMED from code** (prod DB not reachable — MariaDB host blocks this machine's IP, so
verified via source, which is conclusive here).

**Two independent payment flows exist:**
- **Order-level dropdown** — `NewQuote@store_payment_status` (NewQuote.php:3381): admin picks a
  value → writes `autoorder.paid_status` **directly** (+ legacy `orderpayments`). If admin uses
  THIS and picks "Received"(2) it already works.
- **New payment system** — `NewPaymentSystemController@confirm` (line 141-145): admin confirms an
  agent-submitted payment → sets `order_payments.payment_status='Payment Confirmed'` **but never
  touches `autoorder.paid_status`.** ← this is the disconnect the client is hitting.

**Root cause:** booking now writes `autoorder.paid_status = 3` (Confirmation Pending) at
`NewQuote.php:2682` + `:5742` (my Batch-6 #8). The order badge reads `paid_status`. Admin confirms
in the **new payment system**, which flips only `order_payments` → order badge stays at 3.

**CHOSEN FIX (option a):** in `NewPaymentSystemController@confirm()`, after saving, also flip the
linked order: `AutoOrder::where('id',$payment->order_id)->update(['paid_status'=>2])` **and** sync
the legacy `orderpayments.payment_status='Paid'` (mirror `store_payment_status`). Symmetrically in
`returnPayment()` set the order back to `paid_status=3`. Order-level dropdown keeps working
independently. Files: `NewPaymentSystemController@confirm` (~145) + `@returnPayment` (~201).

---

## A. CrazyRays  (project: `crazyrays`)
Form: `resources/views/partials/apply-modal.blade.php`; submit proxy: `routes/web.php` `/apply`.

1. 🔴 **Experience is "optional" but form won't submit without it.** Field `campaign_experience`
   (apply-modal ~150) has no `required`, so a JS validation or the upstream `cr-application`
   validation is rejecting empty. Check the submit JS in apply-modal + `CrApplicationApiController`
   validation on the agent side. → make it truly optional end-to-end.
2. 🟡 **Experience textarea: 250–300 word limit.** Add a word counter + maxlength/JS limit on
   `campaign_experience` (apply-modal ~151).
3. 🟡 **CNIC format: digits only, max 13.** Field `national_id` (apply-modal ~81) → add
   `inputmode=numeric`, digit-mask JS, maxlength 13 (+ optional server rule).
4. 🟡 **Phone format: digits only, 11, shown as `0300 1234567`.** Field `phone` (~109) → mask.
5. 🟡 **Add "In-house" campaign option.** Add a campaign card (apply-modal ~18-48) + the value to
   the campaign whitelist in `crazyrays/routes/web.php` `/apply` `$allowed = [...]` **and** the
   agent-side `CrApplicationApiController` / `CrApplicationController` allowed list.
6. 🟡 **Highlight empty/incorrect fields on submit.** Add client-side validation styling (red
   border + message) instead of the native tooltip only.
7. 🟡 **WFH shift → only Commission pay type; rename "Salary" → "Pay".** `shift_type` select (~130)
   + `pay_type` select (~140). When shift = Work From Home, restrict pay_type to Commission Only;
   relabel options "Salary Only"→"Pay Only", "Salary + Commission"→"Pay + Commission".
8. 🟡 **T&C: remove clause 11 (Independent Relationship / third-party disclaimer).** In the
   crazyrays terms page **and** the job-application T&C. Locate the T&C content (page + modal).

## B. Hello Transport  (project: `washinton_agent`)
9. 🟡 **Notification badge on the "campaign users / CR applications" nav icon** showing count of
   NEW job applications (like the message/notification badges). Nav: `mainsite_pages/nav.blade.php`;
   count source: `CrApplicationController` (new/unviewed applications).
10. 🟡 **"Download Resume" → "View Resume"** on the application detail. File:
    `resources/views/main/cr_applications/show.blade.php` (+ open in new tab / inline view).
11. 🟡 **Show ALL job-application documents (required + optional) at the end** of admin/manager
    view — whether submitted or not. `cr_applications/show.blade.php` (Submitted Documents section).
12. 🟡 **Doc colour states:** received = green, not-received/pending = yellow. Same view as #11.
13. 🟡 **Remove "Start Time" + "Clear Cache"** from the dashboard header on every screen. Files:
    `layouts/mainsite.blade.php` + `layouts/innerpages.blade.php` (grep "Start Time"/"Clear Cache").
14. 🔴 **Mailbox: unhide/decode the redirection link** — the subject shows raw MIME
    `=?utf-8?Q?=E2=80=94?=`. Decode the encoded subject (`mb_decode_mimeheader()`) in
    `MailboxController` before display.
15. 🟡 **Verification-code email: remove "HELLO, ADMIN!"** File:
    `resources/views/emails/send_code_email.blade.php`.
16. 🟡 **Emails from "Crazy Rays Solutions": wrong recipient in the mailbox view** — shows
    `To: nazleenph@gmail.com` instead of the real recipient. Fix the To rendering (use the actual
    recipient) in the mailbox message parser (`MailboxController`).

## C. HR Portal  (project: `washinton_hr`)
17. 🟡 **Add "My Profile" above the dashboard** (subcontractor HR portal). Navbar/dashboard —
    `partials/navbar-header.blade.php` already has "My Profile"; add a link/card above the
    dashboard content (`employee/dashboard.blade.php`).
18. 🟡 **Status "Document Verification" → "Documents Verification Pending".** The status label
    (employee status name). Likely an `hr_employee_statuses` row / status display in the list +
    dropdown. Rename the display (data or blade).
19. 🟡 **Until the account is fully Active, every status label must contain "Pending".** Review all
    non-Active status labels (Document Verification → …Pending, Pending Contract, etc.).
21. 🟡 **Remove Leaves + Gratuity + Tax for subcontractors** (only valid for in-house employees).
    **Existing schema (checked):** `hr_employment_types` = Permanent/Contract/Probation (lifecycle,
    not inhouse-vs-sub); `hr_employee_account_types` = Salary Only / Commission Only /
    Salary+Commission (pay structure, = CrazyRays #7). **Neither cleanly encodes inhouse vs
    subcontractor.** CrazyRays signups arrive via `HrBridgeController` (sets `employment_type_id=3`
    Probation).
    **RECOMMENDED — Option B: add an explicit `worker_type` enum (`inhouse` | `subcontractor`),
    default `inhouse`, on `hr_employees`.** One migration. `HrBridgeController` sets new CrazyRays
    signups = `subcontractor`; admin add/edit gets an inhouse/subcontractor selector. #21 becomes a
    single switch `$employee->worker_type === 'subcontractor'` → hide Leaves step + Gratuity + Tax
    Slab (blade) and skip their store/update + payroll/gratuity logic. Cleanest, future-proof,
    matches the client's own words, and ties to CrazyRays #5 "in-house" option.
    *Rejected:* overloading `employment_type` (a subcontractor can also be on probation) or
    `account_type` = Commission-Only (an inhouse commission employee would wrongly lose leaves/tax).
    Files: `add_employee.blade.php`/`edit_employee.blade.php` (Leaves/Gratuity/Tax sections),
    `AdminEmployeeController` store/update, `HrBridgeController` (~244), payroll/gratuity jobs,
    + migration + `Employee` model helper `isSubcontractor()`.

## D. Cross-project
20. ⏸️ **DEFERRED per user (2026-07-16) — ignore for now.** reCAPTCHA on every page. Revisit later;
    will need Google reCAPTCHA site-key + secret from the user.

---

## Open questions before implementing
- **P0 payment:** which action is the "admin confirms" source of truth (Admin-Payments confirm, or
  the order payment-status dropdown)? That decides the fix.
- **#21 subcontractor vs in-house:** how do we distinguish them? (Depends on CrazyRays #5 "in-house"
  option / an account flag.)
- **#20 reCAPTCHA:** provide site-key + secret + exact page scope.
