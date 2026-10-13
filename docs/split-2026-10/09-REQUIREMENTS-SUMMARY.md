# Both client emails, summarised — with every screenshot mapped

**Covers:** the 8 October email (recruitment pipeline) and the 12 October email (website changes),
plus all 20 attachments in `C:\Users\Dell\Downloads\hello`.
**Purpose:** one page to see the whole ask, what is already built, and what is left.

---

## 1. The two emails at a glance

| | 8 October | 12 October |
|---|---|---|
| **Subject** | Agent recruitment & onboarding | Hello Transport website |
| **Scope** | HR portal pipeline, statuses, emails, payroll | Public site: pages, forms, copy |
| **Size** | Large — weeks | Medium — the quote work is done |
| **Repos** | `washinton_hr` + `washinton_agent` | `washinton_agent` |
| **Detail** | [07-ONBOARDING-PIPELINE.md](07-ONBOARDING-PIPELINE.md) · [08-CREATION-POINTS-AND-RISK.md](08-CREATION-POINTS-AND-RISK.md) | [02-WEBSITE-CHANGES.md](02-WEBSITE-CHANGES.md) |

**They connect at one point:** the website application form (email 2, W-03) is what feeds the
Campaign Leads folder (email 1). Build the pipeline's intake and the website's form as one piece.

---

## 2. Email 2 (12 Oct) — the website

| ID | Change | Status |
|---|---|---|
| **W-01** | "Earn with Hello Transport" teaser section on the home page | ready |
| **W-02** | New page: Earn with Hello Transport (6 sections) + remove Login/Signup from header | ready |
| **W-03** | "Apply for an Opportunity" form | **feeds email 1** |
| **W-04** | "Application Submitted" screen, replacing "Account Created" | ready |
| **W-05** | Login page wording, 4 changes | ✅ **done** — `ee5f758` |
| **W-06** | Blog section, modelled on shipa1.com | blocked — who authors? |
| **W-07a** | Each service opens its own quote form | ✅ **done** — `5d9c41a` |
| **W-07b** | Remove "Vehicle Price" from quote forms | ✅ **done** — `5d9c41a` |

**Done:** 3 of 8. All 15 services now open the right form, boat has its dedicated fields, and the
login page is reworded for an agent audience.

---

## 3. Email 1 (8 Oct) — the pipeline

### The workflow

```
Application → Review → Shortlist → HR account → NDA → Questionnaire → Interview
  → Practical test → Offer → Documents → Verification → Contract → Acceptance
  → Onboarding → CRM account created, link shared → Start
```

**The core instruction:** a Hello signup creates a CRM account *today*. It must instead create a
**lead**, and the CRM account becomes the **last** step.

### Campaign Leads — 4 statuses *(new folder)*

Application Received/Pending → Under Review → **Shortlisted** *(moves to Subcontractors)* → Rejected

### Onboarding statuses — 5 of 7 are new

| Client's status | Already exists? |
|---|---|
| NDA Pending · Questionnaire Pending | **new** |
| In Progress *(screens locked)* | **new** |
| Documents Pending | **new** — id 7 is *Verification* Pending, a later stage |
| Verification / Management Approval | ✅ ids 7 and 9 |
| Contract Pending | ✅ id 8 |
| Onboarding | **new** |
| Active | ✅ id 1 |

### Emails — 6 trigger points

after signup · shortlisted *(HR link + credentials)* · rejected *(with reason)* ·
NDA/questionnaire/contract · management approval · CRM account created *(with link)*

### HR portal restructure

Separate **in-house** from **subcontractor** management across attendance, breaks, tickets, daily
activities, orders and payroll. In-house additionally gets Monthly Tax and Gratuity Payout;
subcontractors get Earning & Payouts instead of payslips.

### Documents — worldwide agents

Government photo ID · proof of address *(if required)* · tax ID *(country-dependent)* ·
payment/bank details · country-specific tax forms · selfie · experience letter *(optional)* ·
**workplace setup** (workstation, computer, internet/router, environment)

### Items 4–12

| # | Change |
|---|---|
| 4 | "Own or rent house" optional, at admin's discretion |
| 5 | Remove **Gender** and **Pay** from Profile Info; rename **State ID** → **ID Number** |
| 6 | Remove **Today's Orders** from the subcontractor dashboard |
| 7 | Remove **Subcontractor Name** column from Break List |
| 8 | Remove **Subcontractor** column from Tickets |
| 9 | Rename **Payslips** → **Earning & Payouts** |
| 10 | Payroll redesign — commission, not salary |
| 11 | Restrict agent account **by device details** |
| 12 | Capture IP at 4 points — **~90% already live** |

---

## 4. Every screenshot, mapped

### Email 1 — the HR portal (8 files)

| File | Shows | Requirement |
|---|---|---|
| `1 (1).JPG` | Subcontractor dashboard, **Today's Orders** circled | item 6 — remove it; Orders already exists in the sidebar |
| `2.JPG` | Subcontractor profile. **State ID**, **Gender**, **Pay** circled | item 5 — remove Gender and Pay, rename State ID → ID Number |
| `3.JPG` | Break List, **Subcontractor Name** column circled | item 7 — redundant, it's their own list |
| `4.JPG` | Tickets, **Subcontractor** column circled | item 8 — same reason |
| `5.JPG` | Payslips list. Title, sidebar item and **every column** circled | items 9 + 10 — all salaried fields, wrong for commission agents |
| `13.png` | Payslip structure: Revenue → Rate → Gross → Adjustments → **Net Payable** → Method → Reference → Status | item 10 — the statement layout |
| `12.png` | History table: Month · Revenue · Rate · Commission · Method · Reference | item 10 — the list layout |
| `paid payout.png` | Simpler variant: Revenue · Rate · Earned · Paid · Status | item 10 — monthly summary header |

### Email 2 — the website (12 files)

| File | Shows | Requirement |
|---|---|---|
| `For home page.JPG` | protrucklogistics.org 4-card row, **Agent Program** circled | **W-01** — card style: icon, heading, two lines, one button, above "Trusted By" |
| `how to become an agent.JPG` | protrucklogistics.org/agents.html — **4 numbered steps** on a dark band: Application · Initial Call · Evaluation · Onboarding | **W-02** section 3 — the model for "How it works" |
| `account created.JPG` | Current `/register` success: "ACCOUNT CREATED! … pending admin approval" | **W-04** — replace with "Application Submitted". Header also shows the Login/Signup buttons W-02 removes |
| `login.JPG` | Login page annotated **a–d** | **W-05** ✅ done |
| `our services qoute form.JPG` | Every service opening the **car** form | **W-07a** ✅ done |
| `boat qoute form.JPG` | ShipA1's boat form — the field list | **W-07a** ✅ done |
| `vehicle price remove.JPG` | Vehicle Price field circled | **W-07b** ✅ done |
| `Terms & Condition - Signup page.pdf` | T&C text | **W-03** — the legal checkbox content |
| `screencapture-protrucklogistics-org-agents-html…png` | full agents page | **W-02** reference |
| `screencapture-atransllc…png` | full page | **W-02** reference |
| `screencapture-yescontainers-careers-sales-agents…png` | full page | **W-02** reference |
| `screencapture-air7seas-freight-agent…png` | full page | **W-02** reference |

---

## 5. What is already built

The pipeline is far less green-field than the email implies. Verified against code and live data:

| | State |
|---|---|
| NDA — content, signature, document, require-toggle | **live — 112 signed** |
| Contract — text, signature, acceptance | **live — 130 accepted** |
| IP at NDA signing | **live — 107 of 112** |
| IP at contract signing | **live — 95 of 130** |
| IP at application / at login | exists / live |
| Documents — upload, per-type settings, versioning, verification | **live** |
| HR → CRM account creation over the bridge | **live** |
| Applications, review, approve | **live, CrazyRays only** |

**Genuinely missing: four things.** Campaign Leads · the questionnaire *(nothing exists anywhere)* ·
screen locking · deferring CRM creation.

---

## 6. Where to start

Phases from [08-CREATION-POINTS-AND-RISK.md](08-CREATION-POINTS-AND-RISK.md), lowest risk first:

| Phase | Work | Blocked? |
|---|---|---|
| **A** | Items 5, 6, 7, 8, 9 — the five screenshot fixes | no — **~1 day** |
| **B** | Add the 5 new statuses | no |
| **C** | Gate CRM creation + "Create CRM account now" button | no |
| **D** | Campaign Leads folder | no |
| **E** | W-01/02/03/04 public pages | no |
| **F** | Questionnaire | **questions not supplied** |
| **G** | Screen locking — *last, deliberately* | no |
| **H** | Earning & Payouts · documents · device restriction | **partly** |

---

## 7. Open questions

1. **Where does "Revenue Generated" come from** — orders booked by that agent, or admin entry?
   Decides whether Earning & Payouts is a report or a data-entry screen.
2. **Questionnaire content** — not supplied; blocks F.
3. **Shortlist email** — generated password, or a set-password link? *(a link avoids emailing a
   password at all)*
4. **Device restriction** — one device, or an approved list? What happens on a new laptop?
5. **Country-specific tax forms** — which countries and forms, or a free slot labelled per country?
6. **Blog** — who authors, and is an admin screen needed, or is seeded content enough for launch?
7. **Header Login/Signup removal** — every page, or the home page only?
8. **"Own or rent house" optional** — for everyone, or per agent at the admin's discretion?
9. Does the questionnaire lock apply to **in-house** staff too, or subcontractors only?
