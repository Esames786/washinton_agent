# Deployment Guide — Round-5 batch (13 Aug 2026)
Everything below is LOCAL-UNCOMMITTED right now. No database changes and no new env keys in this
round — it is code-only. Deploy order doesn't matter between projects, but deploy BOTH domains of
each shared codebase together.

---

## 1. washinton_agent → deploy to BOTH hellotransport.com AND florida.crazyrayssolutions.com.pk

**What's in it:**
- Check Price: required Open/Enclosed selector on all 6 quote screens (car/freight/heavy × new/edit); result modal shows only the chosen mode; choice recorded in price history.
- CrazyRays accounts blocked on the Hello login (redirected to florida) — WelcomeController.
- Payment-method radios no longer leak into the dashboard "Report to Admin" modal (mainsite + innerpages layouts).
- Florida OTP/verify page: marketing navbar + footer hidden (frontend-master layout).
- (Also carries the 3 new doc files: ECOSYSTEM_CONNECTIONS.md, CENTRAL_PRICING_MAP.md, CHECK_PRICE_MODE_PLAN.md — documentation only.)

**Steps (each cPanel):**
```
git pull
php artisan view:clear
php artisan route:clear   # WelcomeController route logic changed? no new routes — clear anyway, cheap
```
**Env check (one-time, should already be set):** florida has `IS_AGENT_PORTAL=true`; hello does NOT. The CR-login block and the verify-page chrome both key off this flag.

**Test after deploy:**
1. hello + florida: open a car quote → Check Price without choosing → alert; choose Open → modal shows ONE card labelled Open. Repeat once on a freight or heavy screen.
2. hello: log in with a CrazyRays account → blocked with the florida link. florida: same account logs in fine.
3. hello dashboard → Report to Admin → NO "Customer payment method" radios. Then any order → send booking link modal → radios still there.
4. florida: log out → login → OTP screen → no Home/About/Get-a-Quote navbar, no footer.

---

## 2. washinton_latest → deploy to washington.shawntransport.com (ShipA1 portal)

**What's in it:**
- **NEW: Mobile IP Manager** at `/mobile-ip` — phone-installable (Chrome → Add to Home screen), super-admin-only login, "Whitelist this IP" one-tap + manual add + Active/Disable toggle. Has its OWN login on purpose: the portal login rejects non-whitelisted IPs, so this tool must sit outside it. New files: MobileIpController, mobile/ip_manager blade, public/mobile-ip/* (manifest, sw, icons), routes, + `android-ip-app/` (optional APK wrapper, not deployed to the server's runtime).
- **Zelle/CashApp booking fix** — "The firstname must be a string" is gone (nullable card fields). This is blocking live customer payments → deploy this one first.
- Check Price Open/Enclosed selector on its 6 quote screens (same behaviour as hello).
- (NewQuote.php also still carries the earlier paid_status 2→3 + Confirmation-Pending badge changes if not yet pulled — same file, same pull.)

**Steps:**
```
git pull
php artisan view:clear
```
**Test after deploy:**
1. Open a booking link for an order with Zelle or CashApp method → submit with a reference → succeeds, order goes to Booked with payment badge "Confirmation Pending".
2. One Check Price on a quote screen: selector required, single-mode result.

---

## 3. washinton_hr → deploy to BOTH hr.hellotransport.com AND hr.crazyrayssolutions.com.pk

**What's in it:**
- Subcontractor documents lifecycle: status badge now reacts to progress (Pending upload → "Submitted — awaiting HR verification" → "✓ Documents verified").
- Upload LOCKS once all required documents are submitted (form hidden AND server-side rejected) — changes go through HR only.
- Admin profile: "Approve All Documents" disappears once every current document is verified.

**Steps (each cPanel):**
```
git pull
php artisan view:clear
```
**Test after deploy:**
1. A subcontractor mid-onboarding: upload form visible until the last required doc, then the 🔒 locked notice replaces it; badge flips to "Submitted — awaiting HR verification".
2. Admin verifies all docs (or Approve All) → button disappears; the agent's badge shows "✓ Documents verified".
3. Confirm a NOT-fully-submitted agent can still upload (the lock must not fire early).

---

## 4. crazyrays / central-gateway / others
**No changes this round.** crazyrays is clean at its last deploy; roadya untouched (phase-2 pricing
optimisation is planned but NOT in this batch — nothing to deploy there).

---

## Constant reminders (apply every deploy)
- Deploy hello + florida TOGETHER, and both HR domains TOGETHER — same repo, shared DB; a half-deploy shows two behaviours on one database.
- No migrations and no seeders needed this round. No env edits needed (only verify IS_AGENT_PORTAL as above).
- If a screen looks unchanged after pull: `php artisan view:clear` again and hard-refresh (Ctrl+F5) — these changes are mostly blade/JS.
