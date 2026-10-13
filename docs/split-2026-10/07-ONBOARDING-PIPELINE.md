# Agent recruitment & onboarding pipeline

**Source:** client email, 8 October 2026 (allenmanager@shipa1.com), plus 8 annotated screenshots.
**Status:** requirements and plan. No code written for any of this yet.
**Spans:** `washinton_agent` (public site) and `washinton_hr` (everything after the application).

---

## 1. The headline change

Today, a Hello signup **creates a CRM agent account immediately**. The client's instruction:

> *"hello CRM account will not create directly — everything will be managed in HR portal step by
> step. Admin first makes his HR profile, then at the end they create the CRM account."*

So the CRM account moves from being the **first** thing created to the **last**.

```
Website application → Review → Shortlist → HR account created → NDA → Questionnaire
   → Interview → Practical test → Offer → Documents → Verification → Contract
   → Acceptance → Onboarding → CRM account created, link shared → Start
```

Everything between "Review" and "CRM account" happens in the **HR portal**, which today has no
concept of an applicant at all.

---

## 2. Campaign Leads — a new folder in HR

Applications arrive as **leads**, not accounts. A new admin area in the HR portal, with four states:

| Status | Meaning |
|---|---|
| Application Received / Pending | just submitted |
| Under Review | being read |
| **Shortlisted** | → an HR account is created and the record moves to **Subcontractors** |
| Rejected | with a reason, which is emailed |

This is the piece that doesn't exist anywhere yet. Hello has **no application table** — only
CrazyRays has `cr_applications`. Per the decision of 13 Oct, Hello reuses `cr_applications` with a
`brand` column rather than getting its own table, so one set of screens serves both.

---

## 3. Statuses

The client listed 7. Three already exist, so **5 are new**:

| # | Client's status | In `hr_employee_statuses` today |
|---|---|---|
| 1 | NDA Pending | **new** |
| 1 | Questionnaire Pending | **new** |
| 2 | In Progress *(screens locked)* | **new** |
| 3 | Documents Pending *(awaiting upload)* | **new** — id 7 is *Verification* Pending, a later stage |
| 4 | Verification in Progress | id 7 `Documents Verification Pending` ✓ |
| 4 | Management Approval | id 9 `Management Approval Pending` ✓ |
| 5 | Contract Pending | id 8 `Pending Contract` ✓ |
| 6 | Onboarding | **new** |
| 7 | Active | id 1 `Active` ✓ |

### Screen locking

Two of these gate the agent's own HR portal:

- **In Progress** — after the questionnaire is submitted, the agent's screens lock while interview,
  mock test and offer happen offline. Admin needs a deliberate **unlock** action to move them to
  Documents Pending.
- **Management Approval** — locked again until an admin changes the status.

This needs a middleware in `washinton_hr` that gates a subcontractor's own screens on their current
status, plus an unlock control for admins. It is the single most intrusive change in this document,
because it touches every subcontractor-facing route.

---

## 4. Emails

Six trigger points, all to the applicant/agent:

| When | Contains |
|---|---|
| Right after signup | acknowledgement |
| Shortlisted | **HR portal link + credentials** |
| Rejected | the reason |
| NDA / Questionnaire / Contract ready | link to the relevant step |
| Moved to Management Approval | status update |
| CRM account created | **CRM portal link** |

Two of these ship credentials or portal links, so they need the same care as the existing approval
mail. `CR_MAIL_*` already exists as a second mailer; Hello's equivalent is the default mailer.

---

## 5. HR portal — split in-house from subcontractors

Currently one set of screens serves both. The client wants them managed separately:

| | In-house | Subcontractor |
|---|---|---|
| Attendance | ✓ | ✓ |
| Break list | ✓ | ✓ |
| Tickets | ✓ | ✓ |
| Daily activities | ✓ | ✓ |
| Leads info / orders | ✓ | ✓ (orders only) |
| Dashboard | ✓ | ✓ |
| **Monthly tax** | ✓ | — |
| **Gratuity payout** | ✓ | — |
| **Earning & Payouts** | — | ✓ (see §7) |

`employment_type` already distinguishes them, and a filter on it exists from an earlier batch — but
these are currently *shared screens with a filter*, not separate management areas.

---

## 6. Documents — worldwide agents

The current document set assumes Pakistan (CNIC). For worldwide agents:

1. Government photo ID — passport, national ID or equivalent
2. Proof of address — *only if required*
3. Tax identification — **varies by country**
4. Payment / bank details
5. Any additional country-specific tax or payment forms
6. Selfie
7. Experience letter *(optional)*
8. **Workplace setup** — four photos: workstation, computer/laptop, internet/router, general
   working environment

Items 3 and 5 are country-dependent, so the document requirements need to be driven by the
applicant's country rather than a fixed list. `hr_document_settings` already has a `brand` column
and a per-type model, so this extends an existing mechanism rather than inventing one.

---

## 7. Payroll → "Earning & Payouts"

Subcontractors are paid **commission on revenue**, not salary. The current Payslips screen shows
salaried fields that do not apply — the client circled every one: Subcontractor ID, Name, Code,
CNIC, Department, Designation, Basic Pay, Net Pay.

**Statement (per month):**

| Item | Example |
|---|---|
| Revenue Generated | $18,500 |
| Commission Rate | 10% |
| Gross Commission | $1,850 |
| Adjustments | −$100 |
| **Net Commission Payable** | **$1,750** |
| Payment Method | Wise |
| Payment Reference | HT-0926-001 |
| Status | Paid |

**History table:**

| Month | Revenue Generated | Commission Rate | Commission | Method | Reference |
|---|---|---|---|---|---|
| Sep 2026 | $18,500 | 10% | $1,850 | Bank Transfer | HT-0926-001 |
| Aug 2026 | $14,200 | 10% | $1,420 | Wise | HT-0826-014 |
| Jul 2026 | $11,800 | 8% | $944 | PayPal | HT-0726-009 |

Rename the screen and the sidebar item to **Earning & Payouts** (subcontractors only; in-house keeps
payroll).

---

## 8. Smaller HR fixes

Each maps to a screenshot.

| # | Change | Screenshot |
|---|---|---|
| 4 | "Own or rent house" becomes optional, at admin's discretion | — |
| 5 | Remove **Gender** and **Pay** from Profile Info; rename **State ID** → **ID Number** | `2.JPG` |
| 6 | Remove **Today's Orders** from the subcontractor dashboard — there is already an Orders page | `1 (1).JPG` |
| 7 | Remove the **Subcontractor Name** column from Break List | `3.JPG` |
| 8 | Remove the **Subcontractor** column from Tickets | `4.JPG` |
| 9 | Rename **Payslips** → **Earning & Payouts** | `5.JPG` |
| 10 | Payroll redesign | §7 |
| 11 | Restrict an agent account **by device details** | — |
| 12 | Capture IP at four points | below |

### 12 — IP capture

Record the IP when the agent:

1. submits the application
2. signs the NDA
3. signs the contract
4. logs into the portal

`cr_applications.ip_address` already exists, and `user_login_activities` already records logins. NDA
and contract signing are the two that need adding.

> Items 6, 7 and 8 all remove a column or panel that is redundant **because the agent is looking at
> their own record** — their own name in every row of their own break list. Worth applying the same
> eye to the rest of the subcontractor screens while in there.

---

## 9. Suggested phasing

This is several weeks of work across two repositories. Ordered so each phase is useful on its own.

| Phase | Contents | Depends on |
|---|---|---|
| **A — quick wins** | §8 items 5, 6, 7, 8, 9 (label and column removals) | nothing |
| **B — Earning & Payouts** | §7 commission statements | A |
| **C — application intake** | W-02/03/04 from email 1: the public Earn page, apply form, submitted screen, writing to `cr_applications` with `brand=hello` | migration |
| **D — Campaign Leads** | §2 HR folder, 4 statuses, review and shortlist, email 1 and 3 | C |
| **E — onboarding states** | §3 five new statuses, screen locking, admin unlock, emails 2 and 4–6 | D |
| **F — documents** | §6 worldwide set, country-driven requirements | E |
| **G — separation** | §5 in-house vs subcontractor management | independent |
| **H — device restriction** | §8 item 11, §8 item 12 IP capture | independent |

**Phase A is a day's work and visible immediately.** Phase E is the largest and riskiest, because
screen-locking touches every subcontractor route.

---

## 10. Open questions

1. **Questionnaire content** — the questions themselves have not been supplied. Needed before E.
2. **Commission data source** — where does "Revenue Generated" come from? Orders booked by that
   agent in the month, or entered manually by admin? This decides whether §7 is a report or a
   data-entry screen.
3. **Device restriction (item 11)** — restrict to one device, or approve a list? What happens when
   an agent changes laptop?
4. **Credentials in email** — a generated password, or a set-password link? A link is safer and
   avoids emailing a password at all.
5. **Country-specific tax forms** — which countries, and which forms? Or is it a free upload slot
   labelled per country?
6. **"Own or rent house" optional (item 4)** — optional for everyone, or hidden unless the admin
   turns it on per agent?
7. Does the questionnaire lock apply to **in-house** staff too, or subcontractors only?
