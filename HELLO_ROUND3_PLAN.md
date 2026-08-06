# Hello Round 3 — 10 points (batch of 2026-08-06)
> **STATUS (2026-08-07): ALL IMPLEMENTED.** #3 + #5 applied directly to the live DB (already working).
> The rest need deploy: washinton_hr (both HR portals), washinton_agent (hello + florida), washinton_latest.
> #7/#9 were stale deploys — verify with one fresh application + one activation after deploying.
**Theme (per client): florida.crazyrays = Pakistan staff, hello = international. Most issues are
brand leaks — CNIC vs State ID wording, PK-format validation hitting US people, CrazyRays labels/
links reaching Hello agents. Verified against code + all 11 screenshots. Root causes below.**

---

## Point-by-point — what I found

| # | Ask | Root cause found | Fix |
|---|---|---|---|
| **1** | Application-received/welcome email says "CNIC" for Hello applicants | `emails/welcome*/pending-approval` email body hardcodes "…such as your CNIC, educational certificates…" | Brand-aware wording: State ID for Hello, CNIC for CR (find the exact template via the WelcomeEmail mailable) |
| **2** | Hello agent's HR portal profile dropdown says "**Back to CrazyRays Dashboard**" | Found: `washinton_hr/partials/navbar-header.blade.php:374` — hardcoded label (the other back-link in `layout/master` was already fixed; this dropdown is a SECOND spot) | Same `Brand::for($employee)` name + per-brand dashboard URL |
| **3** | Hello agent **cannot check in** — "Shift Attendance rule not found for employee." | Found: `AttendanceServiceTrait:65` — check-in requires a row in `hr_shift_attendance_rules` for the employee's shift; the new Hello shift **"Morning (10am-5pm)" (id 10) has no rules row** | Insert rules for shift 10 (copy shift 1's grace values) — live DB + add to `HelloOnboardingSeeder` so future shifts don't repeat this |
| **4** | HR edit-subcontractor: father/gender/marital should be optional; CNIC demands 13 digits; phone fields red for US numbers | Backend father/gender/marital are ALREADY `nullable` — the red-required styling is **front-end** (`edit_employee.blade` attrs). The real blocker: `cnic => regex ^(\d{13}|\d{5}-\d{7}-\d)$` (lines 467 & 876) — PK-only format rejects US State IDs; phone regex probably fine but front-end `pattern` may not be | Brand-aware validation: for Hello subcontractors accept a loose State-ID format (`3–20` alphanum); relax the front-end required markers + patterns; keep the strict CNIC rule for CR staff |
| **5** | W-9 download from HR admin errors | Screenshot shows the built URL: `hr.hellotransport.com/login/Uploads/w9_forms/…` → 404. My fallback strips `/loginn$` but HR's brand `login_url` ends `/login`, AND the domain is wrong anyway — the file lives on the **agent portal** (hellotransport.com), not the HR domain | Fix fallback to the agent-portal base (`https://hellotransport.com`); **backfill `w9_forms.document_url`** for existing rows via DB; new submissions already store the absolute URL |
| **6** | Payment status STILL "Pending" after submit (3rd time reported) | my-payments + booking-form (card/zelle) paths are done. The remaining path: the **Pay Now → `/order_payment` flow (`NewQuote@order_payment` ~2482)** — the one from the client's earlier crash screenshot — needs tracing: it books/emails but does not set `paid_status = 3` | Set `paid_status = 3` (Confirmation Pending badge) on that submit path too; admin's existing status dropdown sets 2 = Received. Then re-test end-to-end |
| **7** | CR application "Signup IP" shows "—" | Code is correct (crazyrays forwards `ip_address` → florida stores it). App #241 (Aug 6) has NULL → **crazyrays portal was likely not redeployed** after that change | Verify crazyrays `git pull` + `view:clear`; then a fresh application shows the IP. No code change expected |
| **8** | Florida: remove About Us / Our Services / Get a Quote + footer marketing — "only home page" | My earlier middleware redirects the marketing **routes**, but the login page **renders** the marketing navbar + footer around the sign-in box (both circled) | Hide the marketing nav items, footer Services/Useful-links columns, socials and the left-panel marketing bullets when the deployment brand is crazyrays (login layout + footer partial). Home + Login stay |
| **9** | HR-activation email still links `hr.crazyrayssolutions.com.pk` for a Hello agent | The per-person `hr_login_url` fix exists in code (both apps) — screenshot is from Aug 6, likely **pre-deploy**, BUT double-check: the HR-side `HrActivatedEmail` passes `$brand` as a plain STRING and my blade matches on it — verify both templates render the right URL after deploy | Verify deploy; test one activation for a Hello agent and one for CR |
| **10** | Use the official **Hello Transport T&C document** (10 sections, attached PDF) on the Hello signup page | Signup currently shows the generic contract template | Store the FULL provided T&C text as a blade partial (`partials/hello_terms.blade.php`) and show it in the signup's collapsed T&C box for Hello; it stays behind the "View Terms and Conditions" button. (Doc also mandates W-9 before payment — consistent with our W-9 flow) |

## Answer to your florida question (permanent redirect)

**Don't redirect `/loginn` itself to crazyrayssolutions.com.pk** — that's where agents actually sign
in; redirecting it would lock everyone out of the portal. What gets us the same result safely:
- `/register` (signup) on florida **already** redirects to crazyrayssolutions.com.pk ✅
- All marketing **routes** (about, services, quote…) already redirect to `/loginn` ✅ (last round)
- The remaining problem is **cosmetic**: the login page still *renders* the marketing nav + footer.
  Fix #8 removes those elements on the CR deployment — after that, florida is effectively
  "login-only", which is what the client wants, without breaking agent sign-in.

## Execution order
1. **#3 check-in blocker** (live DB rules row — agents are blocked right now) + #5 W-9 URL backfill
2. #2 dropdown label + #1 email wording (small)
3. #4 brand-aware edit validation (backend regex + front-end markers)
4. #6 Pay-Now flow paid_status trace + fix
5. #8 florida chrome strip
6. #10 official T&C partial
7. #7 + #9: deploy-verification items (report back after crazyrays + both portals are pulled)

## Needs your confirmation
- #4: for Hello staff, is a loose State-ID rule OK (letters+digits, 3–20 chars)? US formats vary by state.
- #7/#9 look like stale deploys — please confirm crazyrays + both HR/agent portals were pulled after the last round before I chase phantom bugs.
