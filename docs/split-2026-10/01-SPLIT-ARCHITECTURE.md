# Separating Hello Transport and CrazyRays

**Status:** requirements and plan. No code or data has been changed.
**Written:** 12 October 2026
**Audit basis:** live `hellotransport_databases` @ 199.250.220.27, read-only queries on 12 Oct 2026.

---

## 1. What we have today

One database and one codebase serve two businesses.

| | |
|---|---|
| Database | `hellotransport_databases` — **257 tables** |
| Agent portal code | `washinton_agent` — serves **hellotransport.com** *and* **florida.crazyrayssolutions.com.pk** |
| HR portal code | `washinton_hr` — serves both brands, **65 `hr_*` tables in the same database** |
| Public site | `crazyrays` repo (crazyrayssolutions.com.pk) |

The only thing that says which business a record belongs to is **one column**: `user.is_crazyrays`.

```
users          55 Hello  /  95 CrazyRays
hr_employees   51 Hello  /  94 CrazyRays
orders         53 Hello  / 212 CrazyRays   (agent-portal order table, 266 rows)
```

---

> **Corrected 13 Oct 2026.** The orphan claim below was based on eight hand-picked tables and was
> too optimistic. Two corrections are in [`04-DATA-MIGRATION.md §3`](04-DATA-MIGRATION.md):
> `employee_id` resolves through `hr_employees`, not `user`; and several large tables carry a
> `user_id` belonging to the Washington CRM, so they are shared reference data, not brand-owned.
> The conclusion still holds — the split is clean — but the table classification in `04` is the
> one to migrate from.

## 2. The finding that makes this feasible

**Every user-keyed table splits cleanly on `user.is_crazyrays`, with zero orphans.**

Measured on live data:

| Table | Rows | Hello | CrazyRays | Orphan |
|---|---|---|---|---|
| `call_histories` | 978 | 286 | 692 | **0** |
| `activities` | 840 | 388 | 452 | **0** |
| `user_login_activities` | 941 | 272 | 669 | **0** |
| `attendances` | 374 | 132 | 242 | **0** |
| `count_days` | 244 | 65 | 179 | **0** |
| `carriers` | 84 | 30 | 54 | **0** |
| `cr_applications` | 111 | 0 | 94 | 17 ¹ |

¹ Public job applications submitted before an agent account exists. CrazyRays-only by nature, so they move with CrazyRays.

**There is no record owned by both brands.** That means the split is a data *partition*, not a data *untangling* — which is the difference between a few days of work and a few months.

---

## 3. Three classes of data

Every one of the 257 tables falls into exactly one bucket. This classification is the whole migration.

### A. User-owned — **partition** (127 tables)

Any table with `user_id`, `userId`, `agent_id`, `auth_id`, `employee_id` or `emp_id`. A row follows its user's brand.

> Rule: `DELETE FROM <table> WHERE <user column> IN (SELECT id FROM user WHERE is_crazyrays = <other brand>)`

Includes all 65 `hr_*` tables, which hang off `hr_employees.agent_id`.

### B. Shared reference — **copy to both**

Lookup data neither brand owns. Duplicated, then maintained independently.

| Table | Rows |
|---|---|
| `shipper_details_fetch` | 681,809 |
| `used_new_car_dealers` | 617,379 |
| `wp_vehiclelisting` | 117,081 |
| `zipcodes` | 43,054 |

These are ~96% of the database by size and need no decisions — they just get copied.

### C. Brand-specific — **move wholesale**

| Table | Goes to |
|---|---|
| `cr_campaigns` | CrazyRays |
| `cr_applications` | CrazyRays |
| `hr_document_settings` (has a `brand` column) | split on `brand` |

---

## 4. Target state

```
                    BEFORE                                AFTER

  hellotransport.com ─┐                    hellotransport.com ── hello_db
                      ├─ hellotransport_databases
  florida.crazyrays ──┘                    florida.crazyrays ─── crazyrays_db

  washinton_agent  (both brands)           hello_agent      /  crazyrays_agent
  washinton_hr     (both brands)           hello_hr         /  crazyrays_hr
```

Each side ends with its own database, its own repository, its own `.env`, and its own cPanel account.

---

## 5. Repository split

The two portals are **the same code with a brand flag**, so the split is a fork, not a rewrite.

| Today | Becomes |
|---|---|
| `washinton_agent` | `hello_agent` + `crazyrays_agent` |
| `washinton_hr` | `hello_hr` + `crazyrays_hr` |

**Recommended method — fork, then delete:**

1. Clone `washinton_agent` twice under the new names, preserving history.
2. In each copy, delete the other brand's code paths: `App\Support\Brand`, `is_crazyrays` checks, the CR-only controllers (`CrApplicationController`, `CrCampaignController`), CR blades and assets.
3. Keep both on the same Laravel version so a shared bug fix can still be cherry-picked across.

**Do not** try to extract a shared package first. The brand logic is threaded through ~60 blade files and a dozen controllers; a package extraction is a second project and will stall this one.

### Cost of the fork

A fix that applies to both must be applied twice. Accept that explicitly, and keep a short list of files that stay logically shared (quote forms, pricing client, bridge services) so cherry-picks are obvious.

---

## 6. Connectivity that must keep working

This is the part most likely to break silently. Each of these is a live integration today.

| Link | Direction | Must still work after split |
|---|---|---|
| Agent portal → HR portal | SSO + employee create/status | Hello agent → Hello HR only; CR agent → CR HR only |
| HR portal → Agent portal | bridge key, employee sync | Must not cross brands |
| Washington CRM → agent portals | lead/order flow | Both brands keep receiving |
| ShipA1 / Autohaul → Washington | quote intake | Unaffected, but verify |
| Central gateway (roadya) | pricing | Both brands keep calling it |
| RingCentral | dialer, webhooks | One account per brand, or shared with per-brand routing — **decision needed** |
| cPanel email / mailbox | per-agent mailboxes | Follows the agent |

**Each bridge has a shared key** (`config/bridge.php`). After the split there must be **two key pairs**, not one reused — otherwise either portal could call the other's HR.

---

## 7. Migration sequence

Nothing here is destructive until step 6.

1. **Freeze schema changes** on the shared database for the duration.
2. **Create `crazyrays_db`** as a full copy of `hellotransport_databases`.
3. **Prune each side** using the class-A rule above. Hello keeps `is_crazyrays = 0`; CrazyRays keeps `is_crazyrays = 1`.
4. **Verify row counts** against the pre-split numbers in §2. Totals must reconcile exactly.
5. **Fork the repos**, point each `.env` at its own database, issue new bridge keys.
6. **Cut over** one brand at a time — CrazyRays first, because it is the larger and better-isolated side, and because Hello is the one being actively rebuilt (see `02-WEBSITE-CHANGES.md`).
7. **Run both in parallel** for a week with the old database read-only as a fallback.

---

## 8. Decisions needed before step 2

These cannot be inferred from the data, and getting them wrong is expensive to undo.

1. **Shared staff.** Does any person work for both brands? Today one `user` row can only be one brand, so if someone does, they need two accounts — and we must decide which one keeps their history.
2. **Orders.** The agent-portal `order` table has only 266 rows, but the Washington CRM holds the real order volume. **Does the Washington CRM also need splitting, or does it stay shared and serve both brands?** This is the single biggest scope question.
3. **RingCentral.** One account or two?
4. **Reference data.** After copying, do `zipcodes` / dealer lists stay in sync, or diverge freely?
5. **Historical reporting.** Does anyone need a combined cross-brand report after the split? If yes, that needs building before the data is separated.

---

## 9. Risks

| Risk | Mitigation |
|---|---|
| A table is missed in the class-A list and keeps the other brand's rows | Generate the list from `information_schema` at migration time, not from this document |
| Bridge keys reused, portals cross-talk | New key pair per brand, verified by deliberately calling the wrong portal and confirming rejection |
| Cherry-pick drift between the two forks | Keep the shared-file list; review it monthly |
| Cutover loses in-flight data | Old database kept read-only for one week; no `DROP` until sign-off |
| Washington CRM assumptions | Resolve decision #2 before any code is forked |
