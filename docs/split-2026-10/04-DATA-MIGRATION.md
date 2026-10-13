# Data migration — Hello / CrazyRays

**Status:** plan. **Nothing has been copied, changed or deleted.**
**Written:** 13 October 2026, from read-only measurement of the live database.
**Rule set by client:** 13 October 2026 — see §2.

---

## 1. Source and target

| | |
|---|---|
| **Source** | `hellotransport_databases` @ 199.250.220.27 — 257 tables, **255 MB** |
| **Target** | `crazyrayssolutio_crazzy` @ same server — **empty, 0 tables** |
| Target user | `crazyrayssolutio_crazzy2` |

Both sit on the **same MariaDB 10.11.19 server**, but under different cPanel accounts, so **neither
database user can see both databases**. Verified: the source user sees only
`hellotransport_databases`, the target user only `crazyrayssolutio_crazzy`.

**Consequence:** no server-side `INSERT … SELECT` across databases, and SSH is closed on this host.
The copy must be driven by a client holding both connections.

---

## 2. The rule

> **Client, 13 October 2026:** *"all the data you move equally except user account and cr application
> lands to hello and crazzy."*

So the migration is far simpler than the earlier draft assumed:

| | Treatment |
|---|---|
| **Everything** — orders, calls, HR, attendance, activities, carriers, zipcodes, settings, templates, leads | **copy to both sides, equally** |
| **`user`** | **split on `is_crazyrays` — but Order Takers only**, see §2a |
| **`cr_applications`, `cr_campaigns`** | **CrazyRays** |

That is the whole classification. There is no per-table analysis to get wrong, no orphan question, no
decision about the 182 unresolved rows, and no decision about the website leads — all of it copies.

### What splits

| Table | Rows | Hello | CrazyRays |
|---|---|---|---|
| `user` | 150 | 55 | 95 |
| `cr_applications` | 111 | — | 111 |
| `cr_campaigns` | 9 | — | 9 |

`cr_applications` keeps all 111 rows including the 17 not yet linked to an agent — they are CrazyRays
job applications by nature.

### Everything else: 254 tables, copied whole

Including the parts previously flagged for a decision, now moot:

| | Rows | Was | Now |
|---|---|---|---|
| Carrier / dealer / zip masters | ~1.4 M | copy | **copy** |
| `carriers` (per-order) | 84 | split | **copy** |
| `order` | 268 | split | **copy** |
| HR tables (65) | — | split | **copy** |
| Calls, activities, attendance, logins | ~3,400 | split | **copy** |
| `shipa_query` (website leads) | 186 | undecided | **copy** |
| The 182 unresolved rows across 7 tables | 182 | undecided | **copy** |

### Hello still needs an application table

Hello has **no equivalent of `cr_applications`**. Its new apply flow
(`02-WEBSITE-CHANGES.md` W-03) needs one built. Build it on the `cr_applications` shape so the two
brands stay comparable and reportable.

---

## 2a. Privileged accounts are never deleted — on either side

**Client instruction, 13 Oct 2026:** *"make sure do not delete admin manager and important account on
either side."* This is not a refinement; without it the split breaks the CrazyRays portal outright.

### The system has exactly one admin

| id | Name | Email | Role | Brand |
|---|---|---|---|---|
| **1** | Super Admin | `admin@hellotransport.com` | **Admin** | **Hello** |

It is the **only** `role = 1` account in the database, and it is flagged Hello. The original
`DELETE FROM user WHERE is_crazyrays = 0` would have removed it from the CrazyRays copy, leaving that
portal **with no administrator at all** and no way to create one. User id 1 is also the system
fallback owner for unassigned website orders (`report.userId = 1`), so deleting it would break intake
as well.

### Only Order Takers are brand workforce

Of 150 accounts, 138 are Order Takers (role 2). Every other role is privileged or administrative:

| Role | Hello | CrazyRays |
|---|---|---|
| Admin | 1 | 0 |
| Manager | 1 | 1 |
| Dispatcher | 1 | 1 |
| Accountant / Onsite / HR Executive / Support Agent | 0 | 7 |

**Rule:** delete only `role = 2` accounts of the other brand. All 12 non-Order-Taker accounts are
kept on both sides, and user id 1 is never deleted under any condition. Keeping twelve accounts costs
nothing; deleting the wrong one locks people out of a live portal.

### What the split actually removes

| Side | Removed | Kept |
|---|---|---|
| CrazyRays copy | 52 Hello Order Takers | Super Admin, Reference Dispatcher, Hello Manager + all 95 CrazyRays |
| Hello (Phase 5) | 86 CrazyRays Order Takers | all 12 privileged accounts + its own 52 OTs |

---

## 3. The one consequence worth knowing

Copying operational data to both sides while splitting `user` leaves each portal holding rows whose
owning user no longer exists there.

Measured on live data:

| Portal | Rows it will hold belonging to the other brand | Owning `user` row present |
|---|---|---|
| CrazyRays | **7,793** (Hello-owned) | no |
| Hello | **19,375** (CrazyRays-owned) | no |

Concretely, on the CrazyRays portal:

- **Search Orders** will list Hello's 53 orders with a blank agent-name column
- **Reports and dashboard counts** will include the other brand's figures
- The **HR portal** will list the other brand's 51 employees

Nothing errors or crashes — the joins are `LEFT JOIN`, so those rows render with empty agent fields.
It is a reporting and tidiness issue, not a stability one.

**This is the expected result of the rule as given**, and it stays reversible: either side can be
pruned later in one pass, because the per-table user keys are already mapped in
[`TABLE-CLASSIFICATION.md`](TABLE-CLASSIFICATION.md) (Class A = the 113 user-keyed tables).

If you would rather each portal saw only its own history, the optional step in §4 Phase 3 does it —
same migration plus one scripted pass, no extra risk.

---

## 4. Method

A migration script holding both PDO connections, run from a controlled machine. Resumable, with a
verification pass. **Append-only into an empty target — the source is never written to.**

### Phase 1 — Schema (minutes)
For each of the 257 tables: `SHOW CREATE TABLE` on the source, execute on the target. No data.
*Check:* target table count = 257, every column and index matching.

### Phase 2 — Data (≈255 MB)
Copy every table in batches of 1,000 rows, source → target, in primary-key order so it can resume.
*Check:* row count per table matches the source exactly, table by table.

### Phase 3 — Place the three split tables

On the **target (CrazyRays) copy only**:

```sql
-- Only Order Takers. Admin, managers and every other privileged account stay (§2a).
DELETE FROM `user`
WHERE is_crazyrays = 0
  AND role = 2            -- Order Taker
  AND id <> 1;            -- Super Admin, belt and braces
```

*Removes 52 of Hello's 55; keeps Super Admin, the Reference Dispatcher and the Hello Manager.*

`cr_applications` and `cr_campaigns` stay as copied — they are already CrazyRays'.

Later, on the **Hello side** (Phase 5, after CrazyRays is verified live):

```sql
DELETE FROM `user` WHERE is_crazyrays = 1 AND role = 2 AND id <> 1;   -- 86 CrazyRays OTs
DELETE FROM cr_applications;                                          -- CrazyRays-only
DELETE FROM cr_campaigns;
```

*Expected result:* CrazyRays holds 98 accounts (its own 95 + 3 kept Hello privileged); Hello holds 64
(its own 55 + the 9 kept CrazyRays privileged). All other tables identical on both sides.

> **Optional — only if §3 is unwanted.** To give each portal only its own history, additionally run
> the Class-A prune from `TABLE-CLASSIFICATION.md` *before* deleting the users. User-keyed tables
> resolve directly; HR tables resolve through their employee:
>
> ```sql
> DELETE FROM <table>
> WHERE <user key> IN (SELECT id FROM `user` WHERE is_crazyrays = 0);
>
> DELETE FROM <hr table>
> WHERE employee_id IN (
>   SELECT h.id FROM hr_employees h
>   JOIN `user` u ON u.id = h.agent_id
>   WHERE u.is_crazyrays = 0);
> ```
>
> The `user` delete must come **last** — these queries depend on it.

### Phase 4 — Verify
- Target table count = 257; row counts equal to source on all 254 copied tables
- Target `user` = 98 rows; **at least one `role = 1` admin present** and both managers intact
- Zero Hello **Order Takers** remain (`is_crazyrays = 0 AND role = 2`)
- `cr_applications` = 111, `cr_campaigns` = 9
- Spot-check: a known CrazyRays agent still has their orders, HR record, attendance and documents

### Phase 5 — Hello side
Hello keeps the **existing** database. Only after CrazyRays is verified and live does Hello drop its
CrazyRays users and the CR tables — so there is always one intact copy of everything.

---

## 5. Order of operations

1. Confirm `crazyrayssolutio_crazzy` is the intended target and safe to populate
2. **Take a full backup of the source.** cPanel → Backup, download it, verify it opens
3. Phases 1–2 — the target is populated, nothing live yet
4. Phases 3–4 on the target — split and verify, still nothing live
5. Point `florida.crazyrayssolutions.com.pk` at the new database, issue new bridge keys
6. Run CrazyRays live on the new database for one week, Hello untouched on the old one
7. Only then, Phase 5

**At no point before step 7 is a single row deleted from the live database.** If anything is wrong,
CrazyRays is pointed back at the old database and nothing is lost.

---

## 6. Risks

| Risk | Mitigation |
|---|---|
| Copy interrupted midway | Batched in primary-key order and resumable; Phase 4 compares every table's row count |
| Source still changing during the copy | Run Phase 2 at a quiet hour; re-copy `user` and the small operational tables immediately before cutover |
| Foreign keys or triggers reject the delete order | Checked in Phase 1; the `user` delete runs last regardless |
| Auto-increment collisions later | Both sides keep the original IDs; they diverge only after cutover |
| Bridge keys reused, portals cross-talk | New key pair per brand, verified by deliberately calling the wrong portal and confirming rejection |
| Hello pruned too early | Phase 5 is deliberately last, after a week of CrazyRays running clean |
| A portal left with no admin | Only role 2 is ever deleted; the script aborts if no `role = 1` account is present, and previews before it deletes |
| Each portal showing the other brand's history | Expected under the current rule — see §3; reversible with the optional Phase 3 prune |

---

## 7. What I need to proceed

1. **Confirmation that `crazyrayssolutio_crazzy` is the intended target** and is safe to populate
2. Confirmation that a **full backup** of the source has been taken and verified
3. A quiet window for Phase 2

With those, Phases 1–3 are about an hour's run and change nothing live.

Still open, but not blocking this migration — from
[`01-SPLIT-ARCHITECTURE.md §8`](01-SPLIT-ARCHITECTURE.md): does the Washington CRM split too, does
anyone work for both brands, and is RingCentral one account or two.
