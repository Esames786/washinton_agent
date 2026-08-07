# Work Summary — After July Invoice (1 – 7 August 2026)

**Prepared for:** Hello Transport / HR Portal / Crazy Rays / ShipA1 Project
**Basis:** git version history of all projects since 1 Aug 2026 (everything after the July invoice period) + direct production database work.
**Note:** the July invoice covered 3 portals; this period also includes a **4th portal (ShipA1 / washington.shawntransport.com)** which was not billed in July.

## PROJECT OVERVIEW

| Portal | Web addresses / area | Code updates | Files changed |
|---|---|---|---|
| Hello Transport — agent & order portal | hellotransport.com / florida.crazyrayssolutions.com.pk | 10 | 84 |
| HR portal | hr.hellotransport.com / hr.crazyrayssolutions.com.pk | 11 | 36 |
| Crazy Rays sign-up / careers site | crazyrayssolutions.com.pk | 2 | 3 |
| **ShipA1 order portal (new this period)** | washington.shawntransport.com → shipa1.com branding | 2 | 14 |
| Central pricing engine — reviewed, not affected | roadya.com | 0 | 0 |

**Total code updates: 25 Files changed: 137 Portals worked: 4** — in 7 days.

Lines of code: ~4,400 added / ~520 removed across the four portals.

---

## MODULE DETAILS

### A) Hello Transport Sign-up & Onboarding (agent portal)

| Work item | What was delivered | Scale |
|---|---|---|
| Hello sign-up reopened & rebuilt | First/Middle/Last name fields, automatic username, agent-only signup, experience box that feeds the HR profile, timezone picker, new "Morning 10am–5pm" shift, US State ID instead of CNIC. | 1 flow, 2 rounds |
| Official Terms & Conditions | The signed-off Hello Transport LLC T&C document (10 sections incl. W-9 / 1099-NEC clause) placed on the sign-up behind a View/Accept button. | 1 document |
| **W-9 tax form (new feature)** | Full IRS Form W-9 filled and e-signed online by the agent: tax ID stored encrypted (HR sees last 4 digits only), PDF generated and downloadable, admin assigns it NDA-style (Send / Cancel buttons on both the agent review screen and the HR profile), email notice to the agent. | 1 feature |
| Contract e-signing | Contract acceptance now captures a drawn signature + signing IP, visible in HR. | 1 feature |
| NDA branding | NDA shows "State ID" for Hello staff and "CNIC" for Crazy Rays staff (form + PDF, both portals); father name optional; uploaded State ID mirrored into the document checklist. | 2 portals |
| Onboarding gates | Verification gate and check-in gate updated for the new brand rules. | 2 gates |

### B) Payments (agent portal)

| Work item | What was delivered | Scale |
|---|---|---|
| **Payment-method system (new)** | When sending a booking link the agent picks the payment method (Credit Card / Zelle / CashApp / Venmo / PayPal); the customer sees only that method's payment step and enters a transaction reference for non-card methods. | 1 system |
| Card number masking | Agents now see only the last 4 digits and never the security code; the full card stays visible only on the permission-gated admin/manager screen. | All agent screens |
| Payment approval flow | "Confirmation Pending → Received" flow completed across every customer submit path (card, alternative methods, booking link, Pay Now) — chased through 3 review rounds until every path was covered. | All paths |
| Payment crash fixes | Fixed a crash on short/empty phone numbers in booking emails (safe masking helper, 10 places) and surfaced previously-silent send-link errors to the agent. | 10+ places |

### C) Brand-per-Domain Engine (agent + HR portals)

| Work item | What was delivered | Scale |
|---|---|---|
| Domain brand detection | Each domain now reliably shows its own brand (logos, favicons, titles, sidebars) — including protection against server misconfiguration; ~12 hardcoded Crazy Rays spots fixed on Hello domains. | ~12 screens |
| Per-person emails | Activation and notification emails link to the person's own portal (Hello agent → hellotransport, CR staff → crazyrays) instead of the sending server's domain. | Both portals |
| Customer emails to Hello | All customer-facing email templates (booking, reminders ×11, quotes, invoices, order links) re-based so customers only ever see hellotransport.com. | ~28 templates |
| Florida portal-only | florida.crazyrays is now a pure staff portal: marketing pages redirect to login, and the marketing menu, footer columns, social icons and login-page marketing bullets are removed. | 1 deployment |
| Company badges & filter | Hello vs Crazy Rays badges on the HR subcontractor list + a Company filter. | 1 screen |

### D) HR Portal

| Work item | What was delivered | Scale |
|---|---|---|
| Brand-conditional documents | Hello staff and Crazy Rays staff are asked for different document sets (State ID vs CNIC, etc.), driven by a production-safe seeder; verified byte-identical for existing CR staff. | 21 doc types |
| **Document versioning (new rule)** | Subcontractors can no longer delete uploaded documents — removal is HR-only (new admin Remove button). Re-uploading keeps every old version on record and downloadable, labelled Current / Older version with timestamps. | 4 screens |
| Add/edit subcontractor validation | Accepts US State IDs and US phone formats for Hello staff (front + back end); father name / gender / marital status optional for Hello; Crazy Rays rules unchanged. | 2 screens |
| Attendance & timezone | Per-employee timezone support (default Asia/Karachi — CR staff unaffected, verified across 72 scenarios) incl. the daily attendance cron; attendance rules created for the new Hello shift (was blocking check-in). | 1 system |
| W-9 in HR | W-9 card on the HR profile (masked tax info, signature, Send/Cancel request buttons) + download link fixed and backfilled for existing forms. | 1 screen |
| Review-round fixes | "Back to … Dashboard" branding, activation email wording (CNIC → State ID), login screens, payslip/print branding, check-in gate, NDA screens. | ~10 items |

### E) Crazy Rays Sign-up Site

| Work item | What was delivered | Scale |
|---|---|---|
| Sign-up IP capture | Applicant's real IP captured at the sign-up site and forwarded through the secure bridge; shown on the application review screen. | 1 feature |
| Apply-flow updates | Apply modal and layout kept consistent with the new onboarding rules. | 2 screens |

### F) ShipA1 Order Portal — NEW (not billed in July)

| Work item | What was delivered | Scale |
|---|---|---|
| Customer-facing rebrand | Every customer email now says shipa1.com instead of the internal shawntransport address; raw links hidden behind buttons. | 6 templates |
| Payment-method port | The same payment-method step (card / alternative methods with reference) ported to ShipA1's customer booking flow, with its own payment-accounts settings. | 1 system |
| Payment approval flow | Card submissions now follow the same "Confirmation Pending → Received" admin approval as the main portal (previously skipped approval), and the status badge renders correctly. | 3 fixes |
| Crash fixes | Same phone-masking crash fix applied to its booking emails. | 5 templates |

### G) Direct Production Work (not visible in code counts)

| Work item | What was delivered |
|---|---|
| Live database changes | Applied directly on production: W-9 table + URL backfill, sign-up IP column, new shift + its attendance rules, document brand tags, timezone columns, NDA/contract signature columns, payment-method columns. |
| Production troubleshooting | Email delivery diagnosis (SMTP encryption), RingCentral dialer support, deploy verification of client-reported issues (several turned out to be stale deployments — verified and closed without unnecessary code churn). |

### H) Central Pricing Engine Review

roadya.com reviewed against all changes this period — no code changes needed, not billed.

---

## INVOICE SUMMARY (amounts to be filled)

| # | Project / Work Item | Description | Amount (PKR) |
|---|---|---|---|
| 1 | Hello Transport — Agent & Order Portal | Sign-up & onboarding rebuild, W-9 feature, contract e-sign, payment-method system + masking + approval flow, brand engine, customer email re-base, florida portal-only. | |
| 2 | HR Portal | Brand-conditional documents, document versioning rule, State-ID/US validation, timezone + attendance, W-9 in HR, review-round fixes. | |
| 3 | Crazy Rays Sign-up Site | IP capture through bridge, apply-flow updates. | |
| 4 | ShipA1 Order Portal *(new)* | Customer rebrand to shipa1.com, payment-method port, approval flow, crash fixes. | |
| | **Subtotal** | | |
| | **Total Payable** | | |

## NOTES
Prepared from the 1–7 Aug 2026 version history of the four active portals plus direct production database work. "Code updates" = commits; "files changed" = unique files touched in the period. The three planning/handover documents produced in the period (Round-2 plan, Round-3 plan, meeting plan) are included in the counts.
