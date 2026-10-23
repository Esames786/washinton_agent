# Hello Transport / CrazyRays: client issues, 23 Oct 2026

**Source:** client email (allenmanager@shipa1.com, 23 Oct, 1:35 AM) plus 5 screenshots: account revoke,
sign-out redirect, contract and W-9 e-mails, and the W-9 lock.

**Status:** all six items are fixed in code, tested and pushed. **Nothing is live until the deploy
below is done.**

| # | Item | Cause, in one line | State |
|---|---|---|---|
| F | Florida: Onsite panel (4 points) | Same request as Hello; the Hello work was on `main` only | ✅ code on `crazyrays`; SQL to run on florida |
| H1 | NDA auto-assigned at shortlist, no e-mail | The "NDA ready" e-mail only fired on a status *change*; shortlist *creates* the agent at NDA Pending | ✅ HR `d0dd025` |
| H2 | Two contract e-mails | Status → Pending Contract e-mails even with no contract; Save Contract e-mails again | ✅ HR `d0dd025` |
| H3 | CRM account created before Onboarding | A name/e-mail/phone/role edit synced to the CRM at any status | ✅ HR `d0dd025` |
| H4 | W-9 locks the CRM; belongs in HR, optional | The W-9 flag and form lived only in the CRM, and the gate treated it as a lock | ✅ CRM `edfe614` + HR `5e62e8c` |
| H5 | Revoked device → Sign out lands on the CrazyRays site | Logout treated every job applicant as CrazyRays, including Hello's own (`brand = hello`) | ✅ CRM `84cfca2` (Hello only) |
| M | Florida: both managers must edit/update accounts | Dashboard nav hid the subcontractor list from managers without permission 20 | ✅ crazyrays `ae0e81f` + SQL below |

---

## F. Florida (CrazyRays): Onsite panel

*"Need to add a panel with the name Onsite; access to fellow order takers' quotes and orders in the
same panel (All quotes); unhide the Auto Approach folder in the Onsite panel; copy only the Auto
Approach data from ShipA1 and show it in the florida Onsite panel."*

Identical to the Hello request of 22 Oct. The Hello commits were on `main` only. They are now on
`crazyrays` too:

| Commit (crazyrays) | What |
|---|---|
| `2551890` | Autos Approach folder shown again where the agent has permission 94 on their current panel (was hard-hidden with `@if (false)` since July) |
| `8077453` | Edit screen offers **All Qoutes** and the **Access Auto Approach (94) / Assign (145) / Filter (146)** permissions again |
| `2cde53d` | `docs/ONSITE-PANEL.sql` gains a check-first query (A0) so it runs on florida's database |

**Data:** florida's database (`crazyrayssolutio_crazzy`) started as a full copy of Hello's
(`docs/split-2026-10/04-DATA-MIGRATION.md`). So it most likely holds the same 617,379-row Auto
Approach list (`used_new_car_dealers`, identical to ShipA1 up to id 641416) and the same 12,284 rows
owned by ShipA1 user ids. I could not confirm this: florida's database is not reachable from here.
**A0 tells you.**

**Steps on florida:** see Deploy, step 3.

---

## H1. NDA assigned automatically: no e-mail

*"NDA is on auto assign — no email to the agent; if I assign it manually the agent is notified."*

- **Cause:** shortlisting a lead (`AdminCampaignLeadController::shortlist`) creates the HR record
  already at *NDA Pending* with `nda_required = 1`. The "Your NDA is ready to sign" e-mail
  (`AgentStageNotifier`) was only sent from `EmployeeObserver::updated()` on a status **change**,
  so it never fired. The shortlist e-mail mentions the NDA only as a later step. The manual
  "Require NDA" / "Save NDA" buttons do e-mail.
- **Fix:**
  - `EmployeeObserver::created()` sends "Your NDA is ready to sign" when a pipeline hire is created
    at NDA Pending with the NDA required.
  - Moving a hire to NDA Pending also sets `nda_required = 1`. Before, the e-mail told them to sign
    an NDA their portal never showed.
- **Effect:** at shortlist the agent now gets two e-mails, "You have been shortlisted" (set your
  password) and "Your NDA is ready to sign".

## H2. Two contract e-mails

*"Changing status to Contract Pending e-mails the agent, and the contract is assigned manually, so
the agent gets two e-mails."* Ben's inbox shows both: "Your contract is ready" and "Action
Required: New Contract Assigned".

- **Cause:** two independent senders.
  - Status → Pending Contract sent "Your contract is ready" **even when no contract had been
    saved**.
  - "Save Contract" sent "New Contract Assigned".
- **Fix:** one e-mail, whichever order HR works in.
  - "Your contract is ready" goes only when a contract is saved and not yet accepted.
  - "Save Contract" stays quiet for a pipeline hire who hasn't reached Pending Contract yet, because
    the status change will e-mail.

  | HR does | Agent receives |
  |---|---|
  | Save contract → set Pending Contract | "Your contract is ready" |
  | Set Pending Contract → save contract | "Action Required: New Contract Assigned" |
  | Edit the contract again later | "New Contract Assigned" (the contract really changed) |
- Not changed: the CRM's own review modal (`/employee-review/save-contract`) still e-mails "Review
  your Contract". It is only used by admins on agents who already have a CRM account.

## H3. CRM account created before Onboarding

*"CRM user is created when Pending Contract is selected; need to change it to Onboarding."*

- The code already intended Onboarding (`EmployeeObserver::CRM_READY`). Ben's history shows what
  really happened:

  | Time | Event |
  |---|---|
  | 00:04 | Management Approval Pending |
  | **00:10** | **CRM account created** |
  | 00:12 | contract saved, then Pending Contract |
  | 00:16 | Onboarding |
  | 00:18 | Active |

  The account appeared **before** Pending Contract, with no status change at that moment.
- **Cause:** besides the Onboarding rule, the observer synced to the CRM whenever the name, e-mail,
  phone or role changed, **at any status**, and that sync creates the account when none exists.
  Saving the Edit form is enough. The other way is the "Create CRM account now" button.
- **Fix:** for a pipeline hire with no CRM account who hasn't reached Onboarding, those edits no
  longer sync. The account is created at Onboarding (or Active), as intended.
- **Still possible on purpose:** the **"Create CRM account now"** button creates it immediately.
  HR used to need it before sending a W-9; with H4 they no longer do.

## H4. W-9: locks the CRM; should be an optional HR step

*"HR account is active; on assigning the W-9 the CRM is locked. Need to add the W-9 form in the HR
workflow, which needs to be optional if HR…"* (the sentence is cut off in the screenshot).

**Assumption:** "optional" = HR decides per agent; only agents HR sends it to are asked. **Please
confirm the end of that sentence.**

- **Cause:**
  - The W-9 flag lived on the CRM `user` row, so HR could only send it once the CRM account existed.
  - The form existed only in the CRM.
  - The CRM's verification gate treated an outstanding W-9 as a full lock (blurred, unclickable).
- **Fix:**
  - **HR:** "Send W-9" works at any stage (`hr_employees.w9_required`). The agent fills the W-9 in
    the **HR portal**: a card on their Profile (the same form as the CRM's), and a reminder on other
    pages. "Cancel W-9 Request" works as before.
  - **Storage:** HR forwards the form to the CRM (`POST /bridge/w9/submit`, bridge key). The CRM
    validates it, encrypts the tax number with its own key and renders the PDF exactly as before,
    into the same `w9_forms` table.
    - Before the CRM account exists, the row is keyed by the HR record (`w9_forms.user_id` is now
      nullable).
    - The account sync attaches it when the account is created.
  - **CRM:** an outstanding W-9 is a dismissible reminder with the same form, **not a lock**. The
    NDA and document checks still lock as before.
  - The admin W-9 card in HR shows for every Hello subcontractor, with or without a CRM account,
    and reads forms filed from either portal.
- **Also fixed:** the CRM's own W-9 e-mail went out as **"Action Required: Review your Contract"**
  with contract wording (the mail class turned `w9` into `contract`). It now says W-9.

## H5. Revoked device → Sign out → CrazyRays website

- **Cause:** logout sends CrazyRays users back to crazyrayssolutions.com.pk, and counted anyone
  with a row in `cr_applications` as CrazyRays. Hello's own signup writes to that table too (Ben:
  `brand = hello`). Any Hello agent who applied through the website had this on every logout, not
  only on the device page.
- **Fix:** logout, the home-page redirect and the CrazyRays bridge login count only CrazyRays
  applications (`CrApplication::crazyrays()`). **Hello (`main`) only**: florida has only CrazyRays
  applications and no `brand` column, so its original check is right. The cherry-pick to
  `crazyrays` was reverted (`5dc3843`) before any deploy: that scope doesn't exist there and every
  florida logout would have failed.

---

## Deploy (in this order)

1. **hellotransport.com** (CRM, `main`):
   ```bash
   cd ~/public_html
   git pull
   php artisan migrate --path=database/migrations/2026_10_23_000001_w9_forms_user_id_nullable.php --force
   php artisan optimize:clear
   ```
2. **hr.hellotransport.com** (HR, `main`), *after* step 1: HR's W-9 form posts to the CRM.
   ```bash
   cd ~/hr.hellotransport.com
   git pull
   php artisan migrate --path=database/migrations/2026_10_23_000001_add_w9_required_to_hr_employees.php --force
   php artisan optimize:clear
   ```
3. **florida** (CRM, `crazyrays`):
   ```bash
   cd ~/florida.crazyrayssolutions.com.pk
   git pull
   php artisan migrate --path=database/migrations/2026_10_22_000001_create_user_devices.php --force   # if not run yet
   php artisan migrate --path=database/migrations/2026_10_23_000001_w9_forms_user_id_nullable.php --force
   php artisan optimize:clear
   ```
   Then on florida's database, with `docs/ONSITE-PANEL.sql`:
   1. **A0**: note `total` / `max_id`.
   2. **A1–A3**: Onsite panel; admin and managers get it, with Auto Approach.
   3. **A5a**: free rows still owned by ShipA1 users.
   4. Per Onsite agent, on Edit Subcontractor:
      - tick the **Onsite** panel;
      - in its permissions window tick **Access Auto Approach**;
      - set **Qoutes Assign → All Qoutes**;
      - Update.
   5. ShipA1's newer rows: **B1/B2** with A0's `max_id` as the cut-off (or the whole table if A0
      shows 0 rows), import, then **A5a** again.

The CrazyRays HR portal (hr.crazyrayssolutions.com.pk) is not involved.

## Quick checks after deploy

- **H1:** shortlist a test lead → the inbox gets "You have been shortlisted" and "Your NDA is ready
  to sign".
- **H2:** save the contract and set Pending Contract (either order) → exactly one contract e-mail.
- **H3:** edit a hire's phone at Management Approval → no CRM account. Set Onboarding → the account
  appears.
- **H4:**
  1. Send W-9 to a hire with no CRM account. The HR profile shows the W-9 card.
  2. Submit it. The HR admin card shows ✓ Submitted with the PDF.
  3. In the CRM, an agent with a pending W-9 sees a reminder, not the lock.
- **H5:** sign out as a Hello agent (or from the revoked-device page) → hellotransport.com login.
- **F:** florida admin → panel dropdown shows Onsite → Autos Approach appears in the sidebar there
  only.

## Notes

- **Two e-mails at activation** (not raised, not changed): moving an agent to Active sends "Your
  Hello Transport HR account is now active" *and* "Welcome aboard — your CRM account is ready".
- **HR test safety:** HR's `phpunit` used `.env`, which is the **live** shared database, and its
  attendance tests write attendance rows. Tests now run only against a local copy (`phpunit.xml`
  forces it; `tests/TestCase` refuses anything else). Those attendance tests now fail locally
  ("Employee with ID 1 not found") instead of writing to live.
- **Tests added:**

  | Repo | File | Tests |
  |---|---|---|
  | HR | `OnboardingEmailsTest` | 5 |
  | HR | `W9WorkflowTest` | 6 |
  | CRM | `W9OnboardingTest` | 5 |
  | CRM | `LogoutRedirectTest` | 2 |
  | CRM | `OnsitePanelTest` | 4 |

  All pass. The HR tests fail on the old code.
