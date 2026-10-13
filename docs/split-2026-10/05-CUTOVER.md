# Cutover — pointing CrazyRays at the new database

**Written:** 13 October 2026.
**Read this before changing any `.env`.** Changing the wrong one, or only one of a pair, breaks a
live portal.

---

## 1. Four portals share this database, not two

This is the fact that drives the whole cutover. From `config/bridge.php`:
*"four portals share the DB but not the filesystem."*

| # | Domain | Code | Brand | Database today |
|---|---|---|---|---|
| 1 | `hellotransport.com` | `washinton_agent` | Hello | `hellotransport_databases` |
| 2 | `florida.crazyrayssolutions.com.pk` | `washinton_agent` | **CrazyRays** | `hellotransport_databases` |
| 3 | `hr.hellotransport.com` | `washinton_hr` | Hello | `hellotransport_databases` |
| 4 | `hr.crazyrayssolutions.com.pk` | `washinton_hr` | **CrazyRays** | `hellotransport_databases` |

Each is a **separate deployment with its own `.env`**, so an `.env` change on one does not affect
the others. That is what makes the split possible — and also what makes a partial change dangerous.

> **Changing only `florida`'s `.env` breaks CrazyRays.** Its agent portal would read the new
> database while `hr.crazyrayssolutions.com.pk` still read the old one. A CrazyRays signup would
> land in the new database and be invisible to CrazyRays HR; attendance, documents and employee
> records would be split across two databases from the first minute.

**Portals 2 and 4 must be switched together, in one window.** Portals 1 and 3 stay on the old
database and are not touched.

The public site `crazyrayssolutions.com.pk` has its **own** database (`crazyray`) and is unaffected.

### Same server, but the two databases cannot see each other

Both databases sit on the **same host** under **different cPanel accounts**, so `DB_HOST` never
changes — only the database name, user and password. That is what makes the cutover a three-line
edit.

But "same server" does not mean one login can read both. Each MySQL user is granted **only its own
database**. Verified directly:

```
crazyrayssolutio_crazzy2 reading hellotransport_databases
  -> ERROR 1142: SELECT command denied
```

Two consequences:

- **Nothing breaks on cutover.** No code anywhere in `washinton_agent` or `washinton_hr` hardcodes
  the database name — checked — so every query follows whatever `.env` says. A cross-database query
  would have failed with access denied; there are none.
- **Server-side copying was not possible.** `INSERT … SELECT` across the two databases is refused,
  which is why the migration runs through a client holding both connections.
- If a **combined cross-brand report** is ever wanted after the split, it needs either an
  application-level merge over two connections, or a cross-database `GRANT` issued from WHM/root.

---

## 2. Order of work

Steps 1–3 are done or in progress; nothing below is live yet.

1. ✅ Copy all 257 tables into `crazyrayssolutio_crazzy`
2. ⏳ Verify — row counts compared table by table against source
3. ⏳ Phase 3 — remove the other brand's Order Takers only (admin, managers and all orders stay)
4. **Switch portals 2 and 4 together** — §3 below
5. Re-point the bridge between them — §4
6. Smoke-test — §5
7. One week later, and only if clean, prune the Hello side (`04-DATA-MIGRATION.md` Phase 5)

---

## 3. The `.env` changes

### Portal 2 — `florida.crazyrayssolutions.com.pk`

```ini
DB_DATABASE=crazyrayssolutio_crazzy
DB_USERNAME=crazyrayssolutio_crazzy2
DB_PASSWORD=<the new password>
# DB_HOST is unchanged — same server
```

### Portal 4 — `hr.crazyrayssolutions.com.pk`

The same three lines. **Both, or neither.**

### Leave portals 1 and 3 completely alone

`hellotransport.com` and `hr.hellotransport.com` keep pointing at
`hellotransport_databases`. Do not edit their `.env` at this stage.

### After editing each

```bash
php artisan config:clear && php artisan cache:clear
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

Then toggle MultiPHP in cPanel to clear the FPM OPcache — a cached `config.php` will otherwise keep
the old credentials live and the change will appear not to have worked.

---

## 4. The bridge between the two CrazyRays portals

Today the CrazyRays agent portal talks to **Hello's** HR portal, because the shared database made
that work:

```ini
HRPORTAL_BASE_URL=https://hr.hellotransport.com
```

Once the databases are separate that is wrong — florida would be calling a portal that reads a
different database. On **portal 2** it becomes:

```ini
HRPORTAL_BASE_URL=https://hr.crazyrayssolutions.com.pk
```

**Issue a new shared key for the CrazyRays pair.** `HRPORTAL_SHARED_KEY` on portal 2 must match
`HR_BRIDGE_SHARED_KEY` on portal 4, and must be **different** from the Hello pair's key — otherwise
either portal could call the other brand's HR.

Verify the isolation deliberately: call the Hello HR bridge with the CrazyRays key and confirm it is
**rejected**. A split that still accepts the old key is not a split.

---

## 5. Smoke test before declaring it live

On `florida.crazyrayssolutions.com.pk`:

- [ ] **Admin can log in** — `admin@hellotransport.com` (id 1, kept deliberately; see `04 §2a`)
- [ ] The CrazyRays **manager** can log in
- [ ] A CrazyRays **Order Taker** can log in and sees their own orders
- [ ] **Orders are all present** — `order` = 268, `order_history` = 7,166
- [ ] Create a test order; confirm it writes to the **new** database and not the old one
- [ ] Agent → HR **SSO** lands on `hr.crazyrayssolutions.com.pk`, not Hello's HR
- [ ] A new CrazyRays signup appears in **CrazyRays HR**
- [ ] Carrier lookup / zipcode autocomplete still work (the copied master tables)
- [ ] Pricing still reaches the central gateway

On `hellotransport.com`, confirm **nothing changed**: a Hello agent logs in, sees their orders, and
the database is still `hellotransport_databases`.

---

## 6. Rollback

Revert the `.env` on portals 2 and 4 to `hellotransport_databases`, re-run the cache commands, toggle
MultiPHP. Both are back on the old database within minutes.

This works because **nothing is deleted from the old database until Phase 5**, a week later. Until
then the old database remains a complete, working copy of everything.

Data written to the new database while CrazyRays ran on it would not be in the old one — so if a
rollback happens after real work has been done, that window's records need copying back. Keep the
rollback window short and decide quickly.

---

## 7. Still to decide

Two questions from `01-SPLIT-ARCHITECTURE.md §8` become real at this step:

- **RingCentral** — one account or two? The dialer config lives in the database that just forked.
- **The Washington CRM** (`washinton_latest`) — it is a **separate database** and is not part of
  this split, but both brands feed it. Confirm it should keep serving both.
