# Batch 7 — Issues (investigation + plan)

Status legend: 🔴 bug/urgent · 🟡 change · ❓ needs decision. Investigate = file located; fix pending approval.

---

## ‼️ P0 — Payment stuck at "Confirmation Pending" after admin confirms (Hello / washinton_agent)
**Screenshot:** order #214 → `Payment: Confirmation Pending` even though admin confirmed.
**Root cause (from Batch-6 #8 work):** booking submit now sets `autoorder.paid_status = 3`
(=Confirmation Pending) — `NewQuote.php:2682` + `:5742`. It only becomes "Received" when an
admin sets `paid_status = 2` via the **payment-status dropdown** (`NewQuote@store_payment_status`).
But the admin's usual "confirm" flow is the **AgentOrderPayment / Admin-Payments confirm**
(`NewPaymentSystemController@confirm`) which sets `AgentOrderPayment.payment_status='Payment
Confirmed'` and does **NOT** touch `autoorder.paid_status` → order stays at 3.
**Fix options:** (a) in the admin payment-confirm action, also set the order's `paid_status = 2`;
or (b) show "Received" when `paid_status ∈ {2,3}` AND the linked AgentOrderPayment is confirmed;
or (c) revert to the old single-state and just relabel. **Needs decision on which confirm action
is the source of truth.** Files: `NewQuote.php` (2682/5742/store_payment_status ~3381),
`NewPaymentSystemController@confirm`, `return_function.blade.php` pay_status() (~296),
`CallHistory.php` pay_status() (~2017).

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
    Hide those wizard steps/fields + skip their logic when the record is a *subcontractor* (vs
    in-house). Needs a way to distinguish subcontractor vs in-house (account/employment type, or
    the new "in-house" flag from CrazyRays #5). Files: `add_employee.blade.php` /
    `edit_employee.blade.php` (Leaves step, Gratuity, Tax Slab fields) + `AdminEmployeeController`
    store/update validation + payroll/gratuity jobs.

## D. Cross-project
20. ❓ **reCAPTCHA on every page (CrazyRays + HR dashboard).** **This is a joint task:** I can add
    the reCAPTCHA widget + server-side verification, **but you must provide the Google reCAPTCHA
    v2/v3 site-key + secret** (from your Google reCAPTCHA account) and confirm which pages.
    Note: crazyrays already has some recaptcha infra (`recaptchaSecret` used in the shipa1 quote
    flow) — may already have keys in config. **Confirm keys + scope before I wire it.**

---

## Open questions before implementing
- **P0 payment:** which action is the "admin confirms" source of truth (Admin-Payments confirm, or
  the order payment-status dropdown)? That decides the fix.
- **#21 subcontractor vs in-house:** how do we distinguish them? (Depends on CrazyRays #5 "in-house"
  option / an account flag.)
- **#20 reCAPTCHA:** provide site-key + secret + exact page scope.
