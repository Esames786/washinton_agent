# URL / Domain Migration — Hello Transport → CrazyRays Solutions

**Goal**

| Portal | Old domain | New domain |
|---|---|---|
| Agent portal (`washinton_agent`) | `hellotransport.com` (a.k.a. agent.hellotransport.com) | **`florida.crazyrayssolutions.com.pk`** |
| HR portal (`washinton_hr`) | `hr.hellotransport.com` | **`hr.crazyrayssolutions.com.pk`** |
| CR bridge / public signup (`crazyrays`) | `crazyrayssolutions.com.pk` | **unchanged** (stays the signup/marketing + bridge site) |

### Confirmed decisions (client, this round)
- **A — Hello website stays.** `hellotransport.com` remains live as the public Hello marketing site (Home, About, Get-a-Quote, etc.). **Only its Sign-up redirects to CrazyRays.** The logged-in *agent portal* moves to `florida.crazyrayssolutions.com.pk`.
- **Get-a-Quote must keep working cross-cPanel.** The public quote form is part of the `washinton_agent` app and **writes straight to the agents' database** (see §5b). Because the agent app + DB are moving to the CrazyRays cPanel, the Hello site's Get-a-Quote must submit **to that same app/DB** or quotes won't reach the order takers. → **§5b.**
- **B — Email migrates to the CrazyRays cPanel.** The whole app moves to the CrazyRays cPanel, so it uses that server's mail. Update `MAIL_*` + the hardcoded `@hellotransport.com` addresses → CrazyRays. → **§1a-mail + §2g.**
- **C — Resolved: the activation-email login link goes to the agent portal directly** (`florida.crazyrayssolutions.com.pk/loginn`), consistent with `DAYDISPATCH_VERIFY_AFTER_SIGNUP_URL`, which already sends CR agents to the agent `/loginn` after signup. → **§2a.** *(Plain-English explanation of what C even was is in §7.)*

Three Laravel apps talk to each other. **Most cross-app URLs are already `env()`-driven**, so the bulk of the work is `.env` edits + `config:clear`. A handful of values are **hardcoded in code** (config/brands, two email templates, one message, mail addresses, temp routes) and must be edited. RingCentral OAuth/webhook URLs must also be **re-registered in the RingCentral console**, not just changed in `.env`.

> ⚠️ Nothing here has been changed yet — this is the plan. Do the DNS/SSL prerequisites first, then env, then code, then clear caches, then re-register RingCentral, then verify.

---

## 0. Prerequisites (ops / hosting — before any code)

1. **DNS**: create `florida.crazyrayssolutions.com.pk` and `hr.crazyrayssolutions.com.pk` A-records → the servers hosting the agent app and HR app respectively.
2. **cPanel**: add each as an Addon/Subdomain pointing at the correct docroot (agent app `public/`, HR app `public/`).
3. **SSL**: issue certs (AutoSSL/Let's Encrypt) for both new hostnames. All three apps use `SESSION_SECURE_COOKIE=true`, so HTTPS must work before login will.
4. **Get-a-Quote hosting** (§5b): decide **Option 1** (host `hellotransport.com` on the same app+DB as `florida.crazyrayssolutions.com.pk` — recommended) vs Option 2 (separate site + cross-domain API). This determines whether Get-a-Quote "lands" with the order takers.
5. Decisions A/B/C are answered (see top + §7); only §7-D (old-domain 301s) is still open.

---

## 1. `.env` changes

### 1a. Agent portal — `washinton_agent/.env` (server: hellotransport public_html)

```diff
- APP_URL=https://hellotransport.com
+ APP_URL=https://florida.crazyrayssolutions.com.pk

# Inbound bridge public URL (crazyrays calls this)
- BRIDGE_PUBLIC_URL=https://hellotransport.com
+ BRIDGE_PUBLIC_URL=https://florida.crazyrayssolutions.com.pk

# Add the new host to the allow-list (keep the others during cutover)
- BRIDGE_ALLOWED_ORIGINS=crazyrayssolutions.com.pk,hellotransport.com
+ BRIDGE_ALLOWED_ORIGINS=crazyrayssolutions.com.pk,florida.crazyrayssolutions.com.pk,hellotransport.com

# Outbound bridge to HR portal
- HRPORTAL_BASE_URL=https://hr.hellotransport.com
+ HRPORTAL_BASE_URL=https://hr.crazyrayssolutions.com.pk

# RingCentral OAuth redirect + webhook (see §4 — also re-register in RC console)
- RINGCENTRAL_REDIRECT_URL=https://hellotransport.com/here
+ RINGCENTRAL_REDIRECT_URL=https://florida.crazyrayssolutions.com.pk/here
- RINGCENTRAL_WEBHOOK_URL=https://hellotransport.com/r/webhook
+ RINGCENTRAL_WEBHOOK_URL=https://florida.crazyrayssolutions.com.pk/r/webhook
```

**Email migration (decision B — app is moving to the CrazyRays cPanel, so use its mail):**
```diff
- MAIL_HOST=mail.hellotransport.com
+ MAIL_HOST=mail.crazyrayssolutions.com.pk
- MAIL_USERNAME=support@hellotransport.com
+ MAIL_USERNAME=support@crazyrayssolutions.com.pk        # create this mailbox in the CR cPanel (or reuse careers@)
- MAIL_PASSWORD=[cCj3THz8iua
+ MAIL_PASSWORD=<new CR mailbox password>
- MAIL_FROM_ADDRESS=support@hellotransport.com
+ MAIL_FROM_ADDRESS=support@crazyrayssolutions.com.pk
# MAIL_FROM_NAME can stay "HELLO-TRANSPORT" for Hello-branded customer mail, or switch per brand.
- CONTACT_LEAD_EMAIL=info@hellotransport.com
+ CONTACT_LEAD_EMAIL=info@crazyrayssolutions.com.pk      # create/alias this mailbox on the CR cPanel
# Mailbox / cPanel API (admin unified-mailbox feature) also move to the CR host:
- MAILBOX_DEFAULT_DOMAIN=hellotransport.com  / MAILBOX_IMAP_HOST=mail.hellotransport.com  / MAILBOX_SMTP_HOST=mail.hellotransport.com
+ MAILBOX_DEFAULT_DOMAIN=crazyrayssolutions.com.pk / MAILBOX_IMAP_HOST=mail.crazyrayssolutions.com.pk / MAILBOX_SMTP_HOST=mail.crazyrayssolutions.com.pk
- CPANEL_HOST=secure.shipa1.com / CPANEL_USER=hellotransport / CPANEL_TOKEN=...
+ CPANEL_HOST=<CR cPanel host> / CPANEL_USER=crazyrayssolutio / CPANEL_TOKEN=<new CR API token>
```
> ⚠️ Deliverability: customer-facing mail will now be sent **from a CrazyRays address**. If any Hello-branded email must still appear to come from a `@hellotransport.com` address, keep that mailbox alive and set SPF/DKIM for it — otherwise recipients may see "via crazyrayssolutions.com.pk". Confirm with client.

**Leave unchanged:** `CRAZYRAYS_BASE_URL=https://crazyrayssolutions.com.pk` (CR bridge/signup — still lives there), `CENTRAL_GATEWAY_*`, `AUTOHAUL_*`, `STRIPE_*`, `BREVO_API_KEY`, all bridge **keys**.

### 1b. HR portal — `washinton_hr/.env` (server: ~/hr.hellotransport.com)

```diff
- APP_URL=https://hr.hellotransport.com
+ APP_URL=https://hr.crazyrayssolutions.com.pk

# Live-chat iframe + bridge back to the agent portal
- AGENT_PORTAL_URL=https://hellotransport.com
+ AGENT_PORTAL_URL=https://florida.crazyrayssolutions.com.pk
- HELLOTRANSPORT_BRIDGE_URL=https://hellotransport.com
+ HELLOTRANSPORT_BRIDGE_URL=https://florida.crazyrayssolutions.com.pk

# ADD this line — the "Return to Agent Portal" button uses config('bridge.agent_portal.dashboard_url'),
# which falls back to https://hellotransport.com/dashboard when the env var is absent (it is today).
+ AGENT_PORTAL_DASHBOARD_URL=https://florida.crazyrayssolutions.com.pk/dashboard
```

`SESSION_COOKIE=hr_portal_session` and `SESSION_DOMAIN=` (empty) can stay — each app keeps its own session; cross-app auth is via the bridge/SSO token, not a shared cookie.

### 1c. CR bridge / signup — `crazyrays/.env` (server: crazyrayssolutions public_html)

```diff
# Outbound bridge to the AGENT portal (note trailing slash kept)
- DAYDISPATCH_BASE_URL=https://hellotransport.com/
+ DAYDISPATCH_BASE_URL=https://florida.crazyrayssolutions.com.pk/
- DAYDISPATCH_VERIFY_AFTER_SIGNUP_URL=https://hellotransport.com/loginn
+ DAYDISPATCH_VERIFY_AFTER_SIGNUP_URL=https://florida.crazyrayssolutions.com.pk/loginn
- DAYDISPATCH_DASHBOARD_URL=https://hellotransport.com/dashboard
+ DAYDISPATCH_DASHBOARD_URL=https://florida.crazyrayssolutions.com.pk/dashboard
- DAYDISPATCH_LOGOUT_URL=https://hellotransport.com/logout
+ DAYDISPATCH_LOGOUT_URL=https://florida.crazyrayssolutions.com.pk/logout

# Outbound bridge to the HR portal
- HRPORTAL_BASE_URL=https://hr.hellotransport.com/
+ HRPORTAL_BASE_URL=https://hr.crazyrayssolutions.com.pk/
```

`APP_URL=https://crazyrayssolutions.com.pk` stays. `DAYDISPATCH_*_ENDPOINT`, `HRPORTAL_*_ENDPOINT`, and all **keys** stay.

---

## 2. Hardcoded code changes (must edit — not env-driven)

### 2a. Agent brand config — `washinton_agent/config/brands.php`
The `crazyrays` brand `login_url` (used as the agent-activation email's login link for CR users) currently points at the CR bridge. **Decision C resolved → point it at the agent portal login:**
```php
'crazyrays' => [
    ...
-   'login_url' => 'https://crazyrayssolutions.com.pk/login',
+   'login_url' => 'https://florida.crazyrayssolutions.com.pk/loginn',
    'email'     => 'info@crazyrayssolutions.com.pk',   // already CR — fine
],
```
The `hellotransport` brand block stays as-is (**decision A**: Hello brand/site remains at `hellotransport.com`). Its `login_url` (`hellotransport.com/loginn`) is only used by non-CR (Hello) agents — leave it unless those agents are also moved to the new portal (they aren't, per this round).

### 2b. HR-activation email — HR login link is HARDCODED (this is one of the "links shared on approval")
Two templates hardcode `https://hr.hellotransport.com/subcontractor/login`:
- `washinton_agent/resources/views/emails/hr_activated_agent.blade.php` (lines ~48, 49, 61) — sent from the agent portal on approval.
- `washinton_hr/resources/views/emails/hr_activated.blade.php` (lines ~48, 49, 61) — HR-side copy.

Change all occurrences to `https://hr.crazyrayssolutions.com.pk/subcontractor/login`.
*(Better long-term: drive it from `config('bridge.hrportal.base_url')` / `env('HRPORTAL_BASE_URL')` so future moves are env-only — optional.)*

### 2c. Agent-activation email — agent login link
`washinton_agent/resources/views/emails/agent_activated.blade.php` uses `$brand['login_url']` (from §2a) with a fallback `https://hellotransport.com/loginn`. Once §2a is set, CR users get the right link; the fallback only matters if `login_url` is ever null.

### 2d. User-facing message — `washinton_agent/app/Http/Controllers/Bridge/BridgeAuthController.php:251`
```php
'message' => 'This account is not registered through CrazyRays Solutions. Please log in directly at hellotransport.com.',
```
Update the domain in this text to the agent's public login domain (cosmetic, but customer-visible).

### 2e. crazyrays temp maintenance routes — `crazyrays/routes/web.php`
`/artisan/fix-daydispatch-url` (~lines 197–223) hardcodes the old `https://hellotransport.com*` values. These are **temporary** routes — either **delete them** (preferred) or update the hardcoded values so a stray hit can't rewrite `.env` back to the old URLs.

### 2f. HR observer default — `washinton_hr/app/Observers/EmployeeObserver.php:68`
`env('HELLOTRANSPORT_BRIDGE_URL', 'https://hellotransport.com')` — env-driven (fixed by §1b). Optionally update the hardcoded fallback string too.

### 2g. Hardcoded `@hellotransport.com` email addresses in code (decision B — email migrates)
These are **notification recipients / from-fallbacks** literally written in code (not env). Once mail moves to the CR cPanel, the `@hellotransport.com` mailboxes may stop existing, so update them to CR addresses (or an env var). Occurrences:
- `app/Http/Controllers/FrontendController.php:233` — `Mail::to('info@hellotransport.com')` (Get-a-Quote company copy) and `:269` uses `env('CONTACT_LEAD_EMAIL', 'info@hellotransport.com')`.
- `app/Http/Controllers/InstantQuoteApiController.php:327` — `Mail::to('info@hellotransport.com')`.
- `app/Http/Controllers/phone_quote/NewQuote.php` — `$recipients = ['info@hellotransport.com']` at lines ~1182, 1677, 1785, 2471, 3163, 5648, 5828.
- `app/Http/Controllers/phone_quote/callhistory/CallHistory.php:180,182,1321,1373` — `Mail::to(['info@hellotransport.com'])`.
- Mailable from-fallbacks: `AgentActivatedEmail.php:27`, `AgentActionRequiredMail.php:26`, `SendCodeMail.php:41`, `WelcomeEmail.php:25`, `CrApplicationReceivedMail.php:19` — all `config('mail.from.address', '…@hellotransport.com')`. These already prefer `MAIL_FROM_ADDRESS` from `.env` (§1a-mail), so the literal fallback only fires if that env is unset — updating `.env` covers them; edit the literals only if you want a safe CR default.
> Cleanest: replace the hardcoded `info@hellotransport.com` recipients with `env('CONTACT_LEAD_EMAIL')` (already used in one spot) so it's a single env knob.

---

## 3. The CR-approval email (both login links) — where they come from
When a CR application/agent is **approved & activated** in the agent portal, `EmployeeReviewController` sends **two** emails:
- **Agent login link** → `AgentActivatedEmail` (`EmployeeReviewController.php:131`) → template `agent_activated.blade.php` → link = brand `login_url` (**§2a**).
- **HR login link** → `Mail::send('emails.hr_activated_agent', …)` (`EmployeeReviewController.php:179`) → template `hr_activated_agent.blade.php` → link = **hardcoded HR URL (§2b)**.

So after §2a + §2b, both links in the approval email point to the new domains. *(Separately, `CrApplicationController.php:284` sends `CrApplicationApprovedMail` using `config('bridge.crazyrays.base_url')` = `CRAZYRAYS_BASE_URL` — that stays on `crazyrayssolutions.com.pk`.)*

---

## 4. RingCentral (agent portal) — must re-register, not just edit .env
The R-Dialer OAuth + webhooks are pinned to the old host. After §1a:
1. In the **RingCentral developer console** for app id `1c8XT3x9wuZblEQxuFx3Ro`: set the **OAuth Redirect URI** to `https://florida.crazyrayssolutions.com.pk/here`.
2. Update/re-create the **webhook subscription** to `https://florida.crazyrayssolutions.com.pk/r/webhook` (old subscriptions won't auto-move; the app renews subscriptions using `RINGCENTRAL_WEBHOOK_URL`).
3. Confirm `RINGCENTRAL_CALL_CONTROL_ENABLED` state is intended (currently `false`).

Until DNS/SSL for the new host resolve, RingCentral callbacks will 404 — do this **after** cutover.

---

## 5. "Hello signup → CrazyRays signup" redirect
Public signup on the agent portal is `Route::get('/register', 'PublicSignupController@showForm')` (`routes/web.php:116`). To send hello signups to the CR signup for now, make `showForm()` redirect to the CR signup URL, e.g.:
```php
public function showForm() {
    return redirect()->away(rtrim(config('bridge.crazyrays.base_url', 'https://crazyrayssolutions.com.pk'), '/'));
    // or a specific CR signup path, e.g. .../apply
}
```
Confirm the exact CR signup path with the client (bare domain vs `/apply` vs `/signup`). Leave `POST /register` intact or guard it, in case any external form still posts to it.

---

## 5b. ⭐ Get-a-Quote must still work & "land" (the cross-cPanel concern)

**How it works today (all inside the `washinton_agent` app, one server, one DB):**
- Page: `resources/views/main/frontend/get-qoute.blade.php`.
- Form → `<form action="{{ route('Post.Instant.Quote') }}">` → **`POST /Post-Instant-Quote`** → `FrontendController@submitQuoteRequest`, which **creates an `AutoOrder` row** (`source='Website'`, `paneltype=4` = *Website Quote* panel, `pstatus=0`), emails the customer + company, then redirects to the **confirmation page** (`Frontend.qoute.confirmation`).
- Helper AJAX (populate dropdowns): **`GET /get_zip`, `/getmake`, `/getmodel`** — all called with **relative** `url('/…')`.

**Why the move breaks it:** the form + helpers use **relative URLs**, so they only hit whatever app serves the page. After the move the *order takers and the live DB live in the agent app on the CrazyRays cPanel* (`florida.crazyrayssolutions.com.pk`). If `hellotransport.com` serves Get-a-Quote from a **separate** app/DB, submitted quotes land in the **wrong (or dead) database** and never reach the agents — "doesn't land."

**Two ways to guarantee it lands — pick one with the client:**

**Option 1 — Same app + same DB on both domains (recommended, zero code rewiring).**
Host `hellotransport.com` (Hello marketing + Get-a-Quote) **from the same `washinton_agent` deployment and database** as `florida.crazyrayssolutions.com.pk` — e.g. add `hellotransport.com` as a **parked/alias domain** on the CrazyRays cPanel pointing at the same `public/`. Then:
- Relative `route()/url()` resolve to whatever host the visitor is on → Get-a-Quote posts locally, writes the shared DB, order takers see it on Panel 4. ✅ No API/CORS work.
- Only add the §5 Sign-up redirect and keep DNS for `hellotransport.com` → CrazyRays cPanel.
- If `hellotransport.com` must physically stay on the **old** server, it instead needs **remote MySQL** to the CR DB (`DB_HOST` = CR db host + remote-access grant) so both point at one database.

**Option 2 — Hello site is a separate app; Get-a-Quote calls the agent API cross-domain.**
Only if the client insists the two are fully separate deployments. Then, on the Hello site:
- Point the form + helpers at an **absolute agent base URL** instead of relative — make a `QUOTE_API_BASE=https://florida.crazyrayssolutions.com.pk` env and build `action="{{ rtrim(env('QUOTE_API_BASE'),'/') }}/Post-Instant-Quote"` and the `/get_zip /getmake /getmodel` AJAX URLs the same way.
- Add **CORS** on the agent app for these routes allowing origin `https://hellotransport.com` (Laravel `config/cors.php` / a middleware) — cross-site POST from the browser needs it.
- Decide where the customer lands after submit: either the agent's confirmation page (they leave `hellotransport.com`) or return JSON and show a Hello-branded confirmation locally.
- CSRF: `POST /Post-Instant-Quote` is a web route with CSRF — a cross-domain POST won't carry a valid token. Either move it to an **API route** (`routes/api.php`, no CSRF, key-protected) or add it to the `VerifyCsrfToken` `$except` list. **Option 2 is materially more work and more fragile** — prefer Option 1.

> Recommendation: **Option 1.** It satisfies "get a quote should work and land properly" with no code changes to the quote flow — just hosting `hellotransport.com` against the same app/DB and adding the Sign-up redirect.

---

## 6. After every change — clear & re-cache (each app, on its server)
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
# if the app runs cached config in prod:
php artisan config:cache
```
`.env` edits do nothing until `config:clear` (and a fresh `config:cache` if you use cached config).

---

## 7. Decisions — status

- **A — ANSWERED.** Hello site stays on `hellotransport.com` (marketing + Get-a-Quote); only Sign-up redirects to CR (§5). Agent portal → `florida.crazyrayssolutions.com.pk`. Get-a-Quote landing handled in **§5b** (host both on the same app/DB).
- **B — ANSWERED.** Email migrates to the CrazyRays cPanel mail (§1a-mail + §2g).
- **C — ANSWERED → agent portal login (`florida.crazyrayssolutions.com.pk/loginn`).** *(Plain-English below.)*
- **D — still open:** during the switch-over, do you want `hellotransport.com`'s old agent-portal URLs and `hr.hellotransport.com` to **301-redirect** to the new hosts (so old bookmarks/emails keep working), or a hard cutover? Recommended: temporary 301s on `hr.hellotransport.com` → `hr.crazyrayssolutions.com.pk` and on any deep agent links.

### What decision "C" actually was (plain English)
When a CrazyRays agent's account is **approved**, they get an email with a **"Log in" button**. That button needs a web address, and there were two candidates:
1. **The CrazyRays site** — `crazyrayssolutions.com.pk/login` — where they originally applied. Logging in *there* runs the bridge, which then signs them into the agent portal behind the scenes (single sign-on).
2. **The agent portal itself** — `florida.crazyrayssolutions.com.pk/loginn` — the actual order-management app's own login page.

"C" was just: *which of those two addresses should the approval email's login button point to?* We picked **#2 (the agent portal directly)** because your signup flow already sends new CR agents to the agent portal's `/loginn` after they register (`DAYDISPATCH_VERIFY_AFTER_SIGNUP_URL`), so the approval email now matches that same front door. Set in `config/brands.php` (§2a). *(If you'd rather they always enter through the CrazyRays site and be SSO'd in, we'd use #1 instead — say the word and I'll switch it.)*

---

## 8. Cosmetic / brand-only references — LEAVE (decision A: Hello brand stays)
Public marketing + email-branding files under `washinton_agent/resources/views/` still say `hellotransport.com`: `frontend/*` (about-us, privacy-policy, quote-confirmation, dashboard), `layouts/*`, `emails/includes/footer.blade.php`, and most `emails/*` templates (brand name/footer). `washinton_hr/public/commission-slab-guide.html` too. **These are the public Hello brand — leave them as `hellotransport.com`.** (Per-user CR branding already swaps these for CR agents via `config/brands.php` at runtime.)

---

## 9. Final sweep (run in each project after edits, to catch stragglers)
```bash
# from each project root — should return only intended/cosmetic hits
grep -rns --exclude-dir={vendor,node_modules,storage} \
  -e "hellotransport\.com" -e "hr\.hellotransport" app config resources routes
```
Cross-check anything new against §2 / §8 before deploying.

---

## Quick file index (functional touchpoints)
| Project | File | What | §|
|---|---|---|---|
| agent | `.env` | APP_URL, BRIDGE_PUBLIC_URL, BRIDGE_ALLOWED_ORIGINS, HRPORTAL_BASE_URL, RINGCENTRAL_* | 1a |
| agent | `.env` | **MAIL_*, MAILBOX_*, CPANEL_*, CONTACT_LEAD_EMAIL** → CrazyRays | 1a-mail |
| agent | `config/brands.php` | crazyrays `login_url` → `florida…/loginn` | 2a |
| agent | `resources/views/emails/hr_activated_agent.blade.php` | hardcoded HR login link | 2b |
| agent | `app/Http/Controllers/Bridge/BridgeAuthController.php:251` | user-facing message text | 2d |
| agent | `app/…/FrontendController.php`, `InstantQuoteApiController.php`, `NewQuote.php`, `CallHistory.php`, mailables | hardcoded `info@/support@hellotransport.com` | 2g |
| agent | `app/Http/Controllers/PublicSignupController.php` (`/register`) | signup → CR redirect | 5 |
| agent | `resources/views/main/frontend/get-qoute.blade.php` + `FrontendController@submitQuoteRequest` | **Get-a-Quote — host on same app/DB (Option 1) or add absolute API base + CORS (Option 2)** | 5b |
| agent | `config/bridge.php` | env-driven (no edit) | — |
| HR | `.env` | APP_URL, AGENT_PORTAL_URL, HELLOTRANSPORT_BRIDGE_URL, +AGENT_PORTAL_DASHBOARD_URL | 1b |
| HR | `resources/views/emails/hr_activated.blade.php` | hardcoded HR login link | 2b |
| HR | `app/Observers/EmployeeObserver.php:68` | env-driven fallback | 2f |
| crazyrays | `.env` | DAYDISPATCH_* (base/verify/dashboard/logout), HRPORTAL_BASE_URL | 1c |
| crazyrays | `routes/web.php` (~197–223) | temp route hardcodes — delete/update | 2e |

*Public Hello-brand marketing/email files stay on `hellotransport.com` — see §8.*
