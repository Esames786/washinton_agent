# Round-8 — Backlog Research & Plan (26 Aug 2026)

Round-7 (print crash, panel sweep, signup rules, badge fix) is COMPLETE — pending your deploy.
This MD covers the researched BACKLOG and what is being built now.

## Researched backlog

| # | Item | Status / Research | Action now |
|---|---|---|---|
| 1 | **Access Guide for admin & manager** (client asked twice, deferred since July) | The authoritative permission list is `$options_phone` in edit_employee.blade (codes 0-170: folders 0-17, quote types 18/19/92/110, phones 42/60/121/122, logout questions 116-120, payments 164/165, mailbox 163, guide videos 167/168, dialer 169, carrier approval 170, etc.). ~70 of them are soft-hidden (Batch-5) but still function. | **BUILD**: `/access-guide` page — admin+manager only, grouped by category, every code with its number, name and a plain-language definition, live search box. Linked from the profile dropdown. |
| 2 | **Check Price phase-2** (gateway computes only the requested mode) | Both portals ALREADY send `requested_mode` (open/enclosed) since phase-1. Gateway `PricingEngine::quote()` loops `$modes = ['open','enclosed']` unconditionally — every Check Price still costs 2 CentralDispatch calls, one wasted. central-gateway repo is clean (9dbc103). | **BUILD**: filter the mode loop when `requested_mode` is valid; absent/invalid = both modes (fully backward compatible — shipa1/autohaul/daydispatch callers unaffected). Needs roadya deploy. |
| 3 | Hardcoded OTP `123456` (hello + florida) | Real OTP needs reliable SMTP first (florida had 550 failures); universal code undermines password rotations. | **BLOCKED on your decision** — say go after SMTP verify and it's a 2-line change. |
| 4 | MAIL_ENCRYPTION=ssl on port 465 (all portals) | Env-only. | **User-side** — update cPanel envs. |
| 5 | Mailbox welcome greeting shows first name only ("Welcome, checking!") | Cosmetic; template uses `$userName` = first name. | Awaiting your preference (full name?). One-liner when decided. |

## Deploy map after this round
- washinton_agent (hello + florida): round-7 files + access guide. `git pull` + `view:clear` + `route:clear`.
- washinton_latest: round-7 print fix. `git pull` + `view:clear`.
- **central-gateway (roadya)**: PricingEngine phase-2 — first roadya deploy since April; `git pull` only (no migrations).
