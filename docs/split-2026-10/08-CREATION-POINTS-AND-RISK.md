# Where the HR profile and CRM account get created — and the risk-free way to build it

**Written:** 13 October 2026, from a code and live-data audit of `washinton_agent` and `washinton_hr`.
**Answers:** at what point the HR profile is created, at what point the CRM account is created, what
is already built, and the lowest-risk path.

---

## 1. The short answer

| | Created when | Created by | Lives in |
|---|---|---|---|
| **Campaign Lead** | the applicant submits the website form | public site | `cr_applications` (`brand=hello`) |
| **HR profile** | admin clicks **Shortlist** | HR admin | `hr_employees` |
| **CRM account** | **contract accepted** → status reaches Onboarding | HR, over the existing bridge | `user` |

Everything between Shortlist and the CRM account happens **in the HR portal**. The agent only ever
sees the HR portal until the very last step, when they are emailed their CRM link.

```
  PUBLIC SITE          HR PORTAL                                           CRM
  ───────────          ─────────────────────────────────────────────       ───
  application  ──►  lead ──► shortlist ──► NDA ──► questionnaire ──►
                     │         │                                    interview
                     │         └── hr_employees created             (offline)
                     │                                                 │
                   reject                 documents ◄── unlock ◄───────┘
                                              │
                                          verification ──► contract ──► ACCEPTED
                                                                           │
                                                                    user created ──►  CRM link
```

---

## 2. What is already built

This is the important part. **Most of this pipeline already exists** — the client is right that "a
lot of things like NDA etc are already in the system". Verified against code and live data:

| Capability | State | Evidence |
|---|---|---|
| **NDA** — content, signature, document, require-toggle | **live** | `hr_employees.nda_required`, `nda_signature`, `nda_signed_at`, `nda_document_url`, `nda_content`, `nda_cnic_front/back`. **112 employees have signed.** |
| **Contract** — text, signature, acceptance | **live** | `hr_employees.contract`, `contract_signature`, `contract_accepted_at`. **130 accepted.** |
| **IP at NDA signing** | **live** | `nda_signed_ip` — **107 of 112 recorded** |
| **IP at contract signing** | **live** | `contract_signed_ip` — **95 of 130 recorded** |
| **IP at application** | exists | `cr_applications.ip_address` |
| **IP at login** | **live** | `user_login_activities` |
| **Documents** — upload, per-type settings, versioning, verification | **live** | `hr_employee_documents`, `hr_document_settings` |
| **Statuses** | 10 exist | incl. `Documents Verification Pending`, `Management Approval Pending`, `Pending Contract` |
| **HR → CRM account creation** | **live** | `EmployeeObserver::created()` → `/bridge/employee/sync` → `EmployeeSyncController::createOrUpdateUser()` |
| **SSO agent ↔ HR** | **live** | `hr_sso_tokens` |
| **Applications + review + approve** | **live, CrazyRays only** | `cr_applications`, `CrApplicationController@approve` |

> **Item 12 (capture IP at four points) is roughly 90% already done.** Three of the four points are
> live and recording. Only the historical gaps remain, which are rows predating the feature.

---

## 3. What is genuinely missing

Only four things. Everything else is wiring.

| # | Missing | Size |
|---|---|---|
| 1 | **Campaign Leads folder** — Hello applications arriving as leads, with review/shortlist/reject | medium |
| 2 | **Questionnaire** — nothing exists anywhere; content not yet supplied | medium |
| 3 | **Screen locking** — gating the agent's own HR screens on status, with admin unlock | medium, intrusive |
| 4 | **Deferring CRM creation** — today it fires the instant the HR profile is created | **small** |

---

## 4. The one change that matters most

Today, in `washinton_hr/app/Observers/EmployeeObserver.php`:

```php
public function created(Employee $employee)
{
    $this->syncEmployeeToAgent($employee);   // ← unconditional
}
```

Creating an HR profile **immediately creates the CRM account**. There is no status check. That single
line is the entire reason the current flow is "backwards" from what the client wants.

**The fix is not a rebuild — it is a gate:**

```php
public function created(Employee $employee)
{
    // CRM account is created at the END of onboarding, not here.
    // Left to the status transition below.
}

public function updated(Employee $employee)
{
    if ($this->reachedOnboarding($employee)) {
        $this->syncEmployeeToAgent($employee);   // same proven bridge
    }
    // ... existing field-change sync, unchanged
}
```

The bridge itself, the user creation, the role mapping, the slug generation and the permission
defaults all stay exactly as they are. **We change *when* it fires, not *what* it does.**

That is the difference between a week of risk and an afternoon of it.

---

## 5. The risk-free approach

The principle: **defer and reuse; do not rebuild.** Ordered by risk, lowest first.

### Phase A — cosmetic, zero risk *(~1 day)*
The five screenshot fixes: remove Gender/Pay from profile, rename State ID → ID Number, drop Today's
Orders, drop the redundant name columns from Break List and Tickets, rename Payslips.

Nothing structural. Fully reversible. Ships immediately and the client sees progress.

### Phase B — statuses, additive only *(~1 day)*
Insert the 5 new rows into `hr_employee_statuses` (NDA Pending, Questionnaire Pending, In Progress,
Documents Pending, Onboarding). **Adding a status changes nothing until something sets it.** No
existing employee moves.

### Phase C — the gate *(~1 day, the highest-value change)*
Gate `EmployeeObserver` as in §4. **Risk is real but contained:** if the gate is wrong, new agents
don't get a CRM account — visible immediately, and fixed by flipping one condition. No data is lost
and no existing agent is touched.

> Guard rail: run it first with the gate **logging but not blocking**, so we can confirm the status
> transition fires exactly once before it actually controls anything.

### Phase D — Campaign Leads *(~3–4 days)*
The new HR folder reading `cr_applications` where `brand = hello`. **Purely additive** — a new screen
over a table CrazyRays already uses. Shortlist creates the `hr_employees` row, which is the same
operation the admin does by hand today.

### Phase E — public application form *(~2–3 days)*
W-02/03/04 from the first email. The public form writes a lead instead of a user. The existing
`PublicSignupController` stays until the new one is proven, then is switched off — **not deleted**.

### Phase F — questionnaire *(~3 days, blocked)*
Needs the actual questions. New table, new form, new admin view.

### Phase G — screen locking *(~3–4 days, highest risk)*
Middleware gating subcontractor routes on status. **Left until last deliberately** because it can
lock real agents out of their own portal.

> Guard rails: apply to **new** agents only at first (those who entered via the new pipeline);
> exempt anyone already Active; and give admins the unlock control **before** the lock goes live.

### Phase H — Earning & Payouts, documents, device restriction
Independent of the pipeline, can slot in anywhere after A.

---

## 6. Why this ordering is the safe one

- **Nothing in A–D can lock anyone out or destroy data.** They add screens, add statuses, and defer
  one call.
- **The riskiest change (G) comes last**, by which point the pipeline it guards is already working.
- **Each phase is independently useful**, so stopping after any of them leaves a coherent system.
- **The existing signup keeps working throughout.** It is only retired in E, after its replacement
  has been seen to work.
- **The CRM bridge is not rewritten.** It is the one piece that already creates accounts correctly,
  and the plan reuses it untouched.

### What could still go wrong, and the containment

| Risk | Containment |
|---|---|
| The gate (C) stops CRM accounts being created at all | Log-only first; one condition to revert; no data loss |
| Screen lock (G) traps a live agent | Apply to new pipeline entrants only; exempt Active; ship the unlock first |
| Two application tables drift apart | There is only one — `cr_applications` with a `brand` column |
| HR profile created but onboarding abandoned | The employee simply sits at their status; no CRM account is ever made, which is the desired behaviour |
| Migration must run twice now | Hello and CrazyRays are separate databases — **run it on both**, and verify both |

---

## 7. What is needed before each blocked phase

| Phase | Blocked on |
|---|---|
| **F — questionnaire** | the actual questions |
| **H — Earning & Payouts** | where "Revenue Generated" comes from: booked orders, or admin entry? |
| **H — device restriction** | one device or an approved list, and what happens on a new laptop |
| **E — shortlist email** | generated password, or a set-password link? *(a link avoids emailing a password at all)* |

Phases A, B, C and D need nothing further and can start now.
