# How to start

**Read first:** [`01-SPLIT-ARCHITECTURE.md`](01-SPLIT-ARCHITECTURE.md) · [`02-WEBSITE-CHANGES.md`](02-WEBSITE-CHANGES.md)

Two pieces of work are on the table. They are **not** independent, and the order matters.

---

## The order that matters

> **Split the systems first. Build the website second.**

Every website change in `02` lands on the Hello side. If they are built before the fork, each one
has to be applied twice — once in `hello_agent` and once in `crazyrays_agent` — and the two copies
start drifting on day one.

The only exception is **W-07(b)**, deleting the Vehicle Price field. That is a one-line deletion,
the client has asked for it twice, and applying it twice costs nothing.

---

## Before any code is written

Three answers are needed. Work can start on the first two without them, but **the fork cannot**.

1. **Does the Washington CRM split too, or stay shared?**
   The agent portal's own `order` table holds 266 rows; the real order volume is in the Washington
   CRM. If that must split as well, this is a materially bigger project and should be scoped
   separately. *This is the single most important question.*

2. **Does any person work for both brands?**
   One `user` row can only be one brand. If anyone is shared, they need two accounts and we must
   decide which account keeps their history.

3. **RingCentral — one account or two?**
   Affects the dialer, webhooks and call records on both sides.

Questions 4–5 in `01 §8` (reference-data sync, cross-brand reporting) can be answered later, but
before cutover.

---

## Step 1 — Prove the partition, change nothing (½ day)

Write a read-only script that, for all 257 tables:

- classifies each as **user-owned**, **shared reference** or **brand-specific**
- for user-owned tables, counts rows per brand and flags any row whose user cannot be resolved

The audit behind `01 §2` already did this for eight tables and found **zero orphans**. This step
extends it to all of them and produces the definitive list the migration will run from.

**Done when:** every table is in exactly one bucket, and the orphan count is zero or each orphan is
explained.

## Step 2 — Build `crazyrays_db` (1 day)

Copy the full database, then prune each side with the rule in `01 §3A`. Reconcile against the
pre-split counts. Nothing is dropped; the original stays untouched.

**Done when:** `hello_db` + `crazyrays_db` row counts reconcile exactly to the original, table by table.

## Step 3 — Fork the repositories (1 day)

`washinton_agent` → `hello_agent` + `crazyrays_agent`; `washinton_hr` likewise. Preserve history.
Point each `.env` at its own database. **Issue a new bridge key pair per brand** — do not reuse the
existing one.

**Done when:** each portal runs against its own database, and calling the *other* brand's HR bridge
is rejected.

## Step 4 — Cut over, CrazyRays first (1 day + 1 week parallel)

CrazyRays is the larger, better-isolated side, and Hello is the one being rebuilt — so moving
CrazyRays first keeps the rebuild on a stable base. Old database stays read-only for a week.

## Step 5 — Hello website rebuild

Now `02` can be built, once, on the Hello fork. Suggested grouping:

| Ship together | Why |
|---|---|
| W-01, W-02, W-03, W-04, W-05 | One journey: teaser → landing page → apply → confirmation → login wording |
| W-07(a) | Per-service quote forms, independent |
| W-06 | Blog, least urgent |

W-07(b) ships now, before any of this.

---

## What I would do first, tomorrow

1. Put questions 1–3 to the client — they block the fork and nothing else moves without them.
2. Run **Step 1**. It is read-only, needs no answers, and turns the remaining unknowns into a list.
3. Ship **W-07(b)** — a visible win while the bigger decisions settle.

---

## Scope note

`01` and `02` together are a larger body of work than any single batch delivered so far: a database
separation, two repository forks, four portal deployments, and a seven-item website rebuild. It
should be quoted and scheduled as a project, not folded into the ongoing change batches.
