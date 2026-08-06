# Hello Transport — Signup Round 2 + Florida cleanup (batch of 2026-08-05)
**Investigated against code + the 5 screenshots. Root causes below; nothing implemented yet.**

---

## Quick map

| # | Ask | Area found / root cause |
|---|---|---|
| 1 | Signup: First (req) / Middle (opt) / Last (opt); remove Display Name; remove Carrier — only Order Taker/Sales; add optional Experience block | `auth/register.blade.php` (carrier card L207-222, slug field L247-254) + `PublicSignupController@store` (slug required\|unique, last_name required, signup_type in:agent,carrier) + user.slug — must be auto-generated when field is removed |
| 2 | Hello documents += bank details, educational cert, selfie, workplace pic, laptop pic, current IP | `hr_document_settings` — those 6 exist (ids 5, 2, 14, 16, 19, 20) but are brand-tagged `crazyrays`. Fix = tag them `NULL` (shared) so BOTH brands get them; CR is unchanged (it already had them). Update `HelloOnboardingSeeder` SHARED list + live DB update |
| 3 | No email when admin activates a hello agent | Two candidate causes: (a) hello/florida `MAIL_ENCRYPTION=tls` on 465 still failing silently in the non-blocking try/catch around `AgentActivatedEmail` — check `laravel.log`; (b) the admin may be activating via a path that never emails (status toggle on edit screen vs the Employee Review activate button — only `DashboardController@630` + `EmployeeReviewController@143` send). Fix = audit every activation path to send the mail + fix env |
| 4 | T&C hidden by default → "View Terms & Conditions" button opens it → then accept | signup `register.blade.php` T&C block I added — wrap in collapsed container + button; checkbox stays required |
| 5 | W-9: full form editable in portal + **must show in HR admin panel** (currently not visible there) | W-9 exists in the gate + florida Employee Review only. HR was never given a W-9 card. `w9_forms` is in the SHARED DB → add a W-9 card to HR `subcontractors/show` (details, masked TIN, signature, submitted date) + an HR-side download route (HR has dompdf; can't reuse the agent-portal route across auth) |
| 6 | NDA (agent end): CNIC → **State ID** for Hello agents | `nda/modal.blade.php` (both apps): "CNIC Number" label + `CNIC Front / CNIC Back` uploads are hardcoded. Make them brand-aware: Hello → "State ID Number" + "State ID Front / Back" uploads (stored in the same `nda_cnic_front/back` columns; doc-mirror should target the State ID doc type id 21 instead of 10/11 for Hello). PDF labels too |
| 7 | NDA: Father's Name optional (agent end) | both `nda/modal.blade.php` (required attr + backend `father_name => required`) → nullable |
| 8 | **Florida login page** shows marketing chrome (About Us / Our Services / Get a Quote, Signup, marketing bullets circled) | florida is portal-only: on the CrazyRays deployment hide the marketing nav items + marketing copy on `/loginn`; Signup button → `/loginn` (signup already redirects to crazyrayssolutions.com.pk — client wants it going to login instead on florida). View: `main/auth/login2.blade.php` + its navbar/layout |
| 9 | **HR activation email shows the WRONG portal URL** (screenshot: hello agent got `hr.crazyrayssolutions.com.pk/subcontractor/login`) | `emails/hr_activated_agent.blade.php` uses `config('bridge.hrportal.base_url')` = the DEPLOYMENT's HR (florida → hr.crazyrays for everyone). HR's own `hr_activated.blade.php` uses `app.url` — same class of bug from the other side. Fix = per-PERSON brand: add `hr_login_url` to both brands in `config/brands.php` (hello → `https://hr.hellotransport.com`, crazyrays → `https://hr.crazyrayssolutions.com.pk`) and use `Brand::for($user)['hr_login_url']` in both emails |

## Implementation notes

### #1 signup fields
- Add `middle_name` input (optional) → store into `user.name`?? No — keep simple: `name` = first, `last_name` = last (nullable), middle appended to first or stored in existing hr mirror? **Decision: submit `middle_name` optional; save as part of `name` ("First Middle"); `last_name` nullable.** No schema change.
- Slug: field removed → controller auto-generates unique slug from email/name (`Str::slug(first.last) + n`).
- signup_type: remove carrier card; hidden input `agent`; backend keeps accepting `agent` only (validation `in:agent`); Dispatcher path stays for CR bridge only.
- Experience: optional textarea → forwarded to HR `skills` column (exists) — no schema change.

### #2 documents
Live SQL + seeder change: set `brand = NULL` where id IN (2,5,14,16,19,20). CR unchanged (verify with the same before/after count check as last time).

### #6 NDA state-id
In `nda/modal.blade.php` (agent + HR): `$__isHello = ($__brand['key'] ?? '') !== 'crazyrays'` →
labels: "State ID Number", "State ID Front", "State ID Back"; CNIC format placeholder removed for Hello.
Doc-mirror in `NdaController@sign` / `EmployeeNdaController@sign`: for Hello use doc-setting **21 (State ID)** instead of 10/11.
PDF (`nda/pdf.blade.php` both): label "CNIC Number" → brand-aware ("State ID Number" for Hello).

### #5 W-9 in HR admin
- HR `subcontractors/show` new card: query `w9_forms` by `agent_id` (shared DB), show legal name, classification, masked TIN, address, signed date/IP, signature image + **Download** (new HR route rendering the same PDF via dompdf; full TIN decryption requires the SAME APP_KEY — ⚠️ HR has a DIFFERENT APP_KEY, so HR cannot decrypt the TIN → show masked only, and render the PDF with masked TIN, or proxy the file `document_path` which the agent portal already generated (public/Uploads/w9_forms on the agent account — cross-account file access is NOT possible). **Practical fix: HR shows details + signature + link to the stored PDF via the agent portal's public `document_path` URL (`url on hello/florida`)**. Store an absolute `document_url` at generation time like the NDA does.
- "Editable in portal": re-open the W-9 from the gate button any time before submission (already), plus a portal menu link "My W-9" for agents who dismissed the gate — small route+page.

## Suggested order
1. #9 HR-URL bug + #3 activation-mail audit (small, high impact — wrong URLs reaching real people)
2. #1 + #4 signup form rework (one file + controller)
3. #2 documents (SQL + seeder + verify CR unchanged)
4. #6 + #7 NDA labels/optional father (both apps + PDFs + doc-mirror)
5. #8 florida login cleanup
6. #5 W-9: document_url column + HR card + "My W-9" link

## Open questions
1. #1 middle name — OK to store inside `name` ("First Middle")? (no new column)
2. #5 — is the masked TIN acceptable on the HR panel (full TIN only on hello/florida admin)? HR has a different APP_KEY so it mathematically cannot decrypt it.
3. #8 — on florida, should "Get a Quote" be removed entirely, or point to hellotransport.com's quote page?
