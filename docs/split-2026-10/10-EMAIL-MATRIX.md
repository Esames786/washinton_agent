# Emails through the pipeline — keeping the existing ones intact

**Why this matters:** the client's instruction is that an email goes out at each stage, *and* that
everything is managed in the HR portal. Those two pull in opposite directions, because **almost all
the email code lives in the agent portal, not HR.**

---

## 1. Where email lives today

| | Mailables | Templates |
|---|---|---|
| `washinton_agent` | **23** | 23 |
| `washinton_hr` | **1** (`HrActivatedEmail`) | 1 |

The HR portal can currently send exactly one email. The pipeline needs **six trigger points**, all
fired from HR.

---

## 2. The six triggers, mapped to what exists

| # | Trigger | Existing mailable | Where | Status |
|---|---|---|---|---|
| 1 | Right after signup | `WelcomeEmail` · `CrApplicationConfirmationMail` | agent | **reuse — but retime, see §3** |
| 2 | Shortlisted — HR link + credentials | — | — | **new** |
| 3 | Rejected, with reason | `CrApplicationRejectedMail` | agent | **reuse** |
| 4 | NDA / Questionnaire / Contract ready | — | — | **new** |
| 5 | Moved to Management Approval | — | — | **new** |
| 6 | CRM account created, link shared | `AgentActivatedEmail` · `CrApplicationApprovedMail` | agent | **reuse** |

**Three of six already exist.** All three are in the agent portal, with branding already handled by
`Brand::for($user)` so each sends under the right brand.

---

## 3. The one that breaks if we do nothing

`PublicSignupController.php:212`:

```php
Mail::to($user->email)->send(new WelcomeEmail($user->name, $user->email));
```

This fires **when the account is created**. Under the new flow there is no account at signup — only
an application. So without a change this email either stops firing or fires at the wrong moment.

**It must be retimed, not deleted:** move the send to *application submitted*, where it becomes
trigger 1. The mailable and template stay exactly as they are; only the moment changes.

The reverse is true of trigger 6. `AgentActivatedEmail` fires when an agent is activated in the CRM.
Since the CRM account now gets created at the *end* of onboarding, that email lands naturally at the
right point with **no change at all** — the deferral puts it where the client wants it.

---

## 4. Recommended approach — HR triggers, agent sends

Two options:

**A. Build six mailables in the HR portal.** Duplicates templates, branding and layout that already
work, in a repo with one mail template to its name. Two places to change a logo.

**B. HR calls the agent portal over the existing bridge, which sends.** ✅ **Recommended.**

HR already calls the agent portal for employee sync. One more endpoint — "send notification *X* for
employee *Y*" — lets every email keep using the 23 proven mailables, the existing templates, and
`Brand::for()` branding. Nothing is duplicated, and the client's "keep them intact" is satisfied
literally: the same code sends the same emails, just triggered from a new place.

```
HR portal                              Agent portal
  status changes  ──── bridge ────►  existing Mailable ──► applicant
```

> The bridge, its shared key and its failure handling already exist and are in daily use for
> employee sync. This adds a route, not a mechanism.

### Fallback

HR has working SMTP of its own (`MAIL_*` in its `.env`, CrazyRays HR on the CrazyRays mailer). If the
bridge is unreachable, HR can send a plain-text version itself so a shortlist email is never silently
lost. Worth having, because trigger 2 carries the agent's portal credentials.

---

## 5. What each new email needs

| # | Contains | Care needed |
|---|---|---|
| 2 | HR portal link **+ credentials** | **Send a set-password link, not a generated password.** Avoids putting a password in an inbox, and avoids storing one to display |
| 4 | Link to NDA / questionnaire / contract | Should deep-link to the step, so the agent is not hunting for it |
| 5 | Status update only | No link needed; purely informational |

Triggers 2 and 6 both carry portal links, so both need the right brand's URL — `florida` for
CrazyRays, `hellotransport.com` for Hello. `Brand::for()` already resolves this.

---

## 6. Checklist so nothing is lost

- [ ] `WelcomeEmail` retimed from account-creation to application-submitted *(§3)*
- [ ] `CrApplicationRejectedMail` reused for trigger 3, with the rejection reason passed through
- [ ] `AgentActivatedEmail` confirmed to still fire once the CRM account is deferred — it should
      need **no change**, but verify, because it is the agent's only notice that they can start
- [ ] New bridge endpoint for HR-triggered sends
- [ ] Three new mailables: shortlisted, step-ready, management-approval
- [ ] Every link brand-resolved, so a CrazyRays agent is never sent to hellotransport.com
- [ ] An email failure must **never** block a status change — wrap every send, as
      `WelcomeController::sendLoginCode()` already does
