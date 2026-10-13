# Migration log — what actually happened

**Date:** 13 October 2026
**Result:** CrazyRays is live on its own database. Hello is untouched.

---

## 1. What was done

| Step | Result |
|---|---|
| Schema | 257 tables created in `crazyrayssolutio_crazzy` |
| Data | ~1.9 M rows copied, source read-only throughout |
| Split (`user`) | 52 Hello Order Takers removed from the CrazyRays copy |
| HR cleanup | 51 Hello employees + 2,901 history rows removed from the CrazyRays copy |
| Verify | 257 tables compared against source, twice |

**Nothing was deleted from `hellotransport_databases` at any point.** It remains a complete copy of
everything, and is the rollback.

### Why no `mysqldump`

The source has **199 MyISAM tables**. `mysqldump` takes `LOCK TABLES` on those, which would have
frozen the live portal. The copy used plain `SELECT` paging instead — no locks on production.

The target session also needed `sql_mode=''`: the source carries legacy `0000-00-00` dates that
strict mode rejects outright, so inserts would have failed partway through on real data.

---

## 2. The bug the verify caught

The first verify reported `pstatus` **one row short**: source 22, target 21.

The missing row was **`{"id": 0, "name": "NEW"}`** — the default order status.

**Cause:** the copy paged with `WHERE <pk> > $last` starting at `$last = 0`, so any row whose
primary key is exactly **0** was silently skipped.

**Impact if missed:** orders in the NEW folder would have rendered with a blank status label on the
CrazyRays portal — a confusing display bug, not a loud failure, so it could have run for weeks.

**Fix:** all 250 key-paged tables were checked for a row at primary key 0. Only `pstatus` had one.
It was inserted; source and target now both read 22.

The script was corrected to start below the smallest key rather than at 0:

```php
$last = (int) src()->query("SELECT COALESCE(MIN(`$key`),0) FROM `$t`")->fetchColumn() - 1;
```

> **If this migration is ever re-run or adapted, keep that line.** It is the difference between a
> clean copy and a silent one-row gap in a lookup table.

---

## 3. Final verification

237 of 257 tables matched exactly. All 20 differences are accounted for:

| Kind | Tables | Explanation |
|---|---|---|
| Deliberate — `user` split | 1 | −52 Hello Order Takers |
| Deliberate — HR cleanup | 15 | −2,952 rows total, matching the cleanup report exactly |
| **Live activity** | 4 | `activities` +1, `agent_active_times` +2, `attendances` +2, `user_login_activities` +4 |

The four positives are CrazyRays users working on the new database since the copy — they confirm the
portals really are writing to the new database.

**No table is unexpectedly short.**

---

## 4. Accounts, after the split

Per client instruction, *"do not delete admin manager and important account on either side"* and
*"do not remove any orders keep it there — on both sides."*

| Brand | Kept on the CrazyRays database |
|---|---|
| CrazyRays | 86 Order Takers, 3 Onsite, 2 HR Executive, Manager, Dispatcher, Accountant, Support Agent |
| Hello (deliberate) | Super Admin (id 1), Manager (id 373), Reference Dispatcher (id 53) |

Only role 2 (Order Taker) was ever deleted. Id 1 is the system's **only** admin and the fallback
owner for unassigned website orders, so it is kept on both sides permanently.

Orders verified unchanged either side of every delete: `order` 268, `order_history` 7,166,
`sheet_details` 944, `shipa_query` 186. Verified there is **no foreign key referencing `user`**, so
no cascade could reach them.

---

## 5. Backup

Everything removed from the CrazyRays HR side was written to disk first, as restorable `INSERT`
statements, and re-read before any delete ran:

```
D:/laragon2/backups/crazyrays-split-2026-10-13/hello-hr-records-20261013-113813.sql
2,952 rows · 1,620 KB · 26 INSERT statements
```

Restore with:

```bash
mysql -h 199.250.220.27 -u crazyrayssolutio_crazzy2 -p crazyrayssolutio_crazzy < <that file>
```

The same records also still exist in `hellotransport_databases`.

---

## 6. Live configuration

| Portal | Database |
|---|---|
| `hellotransport.com` | `hellotransport_databases` — unchanged |
| `hr.hellotransport.com` | `hellotransport_databases` — unchanged |
| `florida.crazyrayssolutions.com.pk` | `crazyrayssolutio_crazzy` |
| `hr.crazyrayssolutions.com.pk` | `crazyrayssolutio_crazzy` |
| `crazyrayssolutions.com.pk` (public) | its own database; bridges to **florida** |

The public site's `DAYDISPATCH_*` URLs were repointed from `hellotransport.com` to florida. Before
that, CrazyRays signups from the public website were being written into **Hello's** database — the
shared database had hidden it, and the split made it a real fault.

---

## 7. Still outstanding

**Not urgent, but should be done:**

1. **Rotate the bridge key for the CrazyRays pair.** All four portals still share
   `c689d516…cf5`. Routing is correct today, so nothing crosses — but a future mistyped URL would be
   *accepted* and write to the wrong database instead of being rejected. New key to apply across
   florida (3 slots), hr.crazyrays (2 slots) and the public site (`HRPORTAL_SHARED_KEY`).
2. **`MAIL_ENCRYPTION=tls` on port 465** in all three CrazyRays `.env` files. Hello uses `ssl` and
   its mail works; 465 is implicit SSL, so CrazyRays mail is likely failing.
3. **`MAIL_FROM_NAME="Hello Transport HR"`** on hr.crazyrays, sending from a CrazyRays address.

**One week out, only if CrazyRays has run clean:** prune the Hello side — remove the 86 CrazyRays
Order Takers, `cr_applications` and `cr_campaigns` from `hellotransport_databases`. Keep admin and
managers there too. Until then, Hello holds a complete copy of everything.

**Still unanswered** (`01-SPLIT-ARCHITECTURE.md §8`): does the Washington CRM split too? RingCentral
turned out to be **already two separate apps** — Hello `8bJN7jIb…`, florida `1KvxuDGB…` — so that
question is closed.
