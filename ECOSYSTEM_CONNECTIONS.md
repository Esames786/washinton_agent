# Workspace Ecosystem — Connection Map
**What every project is, and exactly what talks to what.** Companion deep-dive for pricing: `CENTRAL_PRICING_MAP.md` (same folder).

## 1. The projects

| Folder | Production domain(s) | What it is | Database |
|---|---|---|---|
| **washinton_agent** | hellotransport.com **and** florida.crazyrayssolutions.com.pk | Agent & order portal (quotes, bookings, payments, dialer). ONE codebase, TWO cPanel deployments — brand decided per domain/user | `hellotransport_databases` (shared) |
| **washinton_hr** | hr.hellotransport.com **and** hr.crazyrayssolutions.com.pk | HR portal (subcontractors, documents, attendance, payroll, tickets). Same two-deployment pattern | `hellotransport_databases` (shared) |
| **crazyrays** | crazyrayssolutions.com.pk | Public CrazyRays sign-up / careers site (no own user data — forwards everything) | none of its own (talks via API) |
| **washinton_latest** | washington.shawntransport.com (+ API host **roadya.shipa1.com**) | ShipA1's order portal ("Washington") — same order engine as washinton_agent but separate deploy | **its own DB** |
| **shipa1_updated** | shipa1.com | ShipA1 customer-facing marketing/quote site | own |
| **hualt** | autohaulingquotes.com | AutoHaulingQuotes marketing/quote site (+ /admin/login portal) | own |
| **central-gateway** | roadya.com | **Central pricing engine + API proxy** — the only system that talks to CentralDispatch Market Intelligence | own (`gateway` connection) |
| **daydispatchagent / daydispatchhr** | daydispatch marketplace + its HR clone | Separate product family; shares ONE DB between the two; uses the same central-gateway for pricing | own shared pair |

**Key structural fact:** hellotransport.com, florida.crazyrays…, hr.hellotransport.com, hr.crazyrays… are FOUR separate cPanel file systems sharing ONE MySQL DB (`hellotransport_databases` @ 199.250.220.27). Data written on one domain is visible everywhere instantly; **files uploaded on one domain exist only on that domain's disk** (this is why the doc-file serving route / `portal_file_url()` helpers exist).

## 2. Connection matrix — who calls whom

| # | From → To | Endpoint / mechanism | Auth | Purpose |
|---|---|---|---|---|
| 1 | crazyrays → washinton_agent (florida) | `POST /api/cr-application` | CORS allow-list + throttle | Job application forwarded (incl. real applicant IP) |
| 2 | crazyrays → washinton_agent (florida) | `POST /api/bridge/register`, `/bridge/login`, `/bridge/verify-otp` | throttle; bridge login endpoint | Sign-up/sign-in from the CR site rides the agent portal's user table (SSO bridge) |
| 3 | washinton_agent ⇄ washinton_hr | `POST /api/employee/attach-documents` (+ other `Bridge\HrBridgeController` endpoints) | `X-Bridge-Key` shared key (per pair) | Agent-portal signup/NDA docs sync into HR employee records; HR activation state flows back |
| 4 | washinton_agent → washinton_hr | per-person `hr_login_url` links in activation emails; `AGENT_PORTAL_DASHBOARD_URL` back-links in HR | n/a (links) | Cross-portal navigation, brand-correct per person |
| 5 | daydispatchagent ⇄ daydispatchhr | same bridge pattern, **separate bridge keys** | `X-Bridge-Key` | Same sync for the DayDispatch family |
| 6 | hualt → shipa1_updated | quote form forward (`WashingtonService` / `HelloTransportService` route through the same pattern) | env platform key | AutoHaul lead enters ShipA1 funnel |
| 7 | hualt → washinton_latest | forward with `platform=washington-autohaul` (`AUTOHAUL_API_KEY`) | env api key | AutoHaul lead becomes a Washington order/quote |
| 8 | hualt → washinton_agent | forward with `platform=hello-autohaul` (`HELLOTRANSPORT_API_KEY`) → `POST autohaul-quote` (`AutohaulQuoteController@store`, round-robin agent assignment) | env api key + blocked-IP check | AutoHaul lead becomes a Hello quote |
| 9 | shipa1_updated → washinton_latest | `POST https://roadya.shipa1.com/api/v2/website-quote` (and `/website-quote-auction`) → `NewQuote@websiteShipa1Quote` | throttle 30/min | ShipA1 website quote becomes a Washington order (**paneltype 4**) |
| 10 | shipa1_updated → central-gateway | `POST /api/v1/pricing/quote` (`platform_code: shipa1` or autohaul) | X-Api-Key + HMAC | Live price for the ShipA1 quote funnel |
| 11 | washinton_agent → central-gateway | `OrderPricingController@check` (`platform_code: hello_transport`), `AutohaulQuoteController` (`hello-autohaul`), `NewQuote@fetchShipA1Pricing` (`shipa1` / autohaul) | X-Api-Key + HMAC | "Check Price (New)" on 6 quote screens; autohaul quotes; ShipA1 booking-email pricing |
| 12 | washinton_latest → central-gateway | same three call sites (`platform_code: washington` / `washington-autohaul` / `shipa1`) | X-Api-Key + HMAC | Same, for the Washington portal |
| 13 | daydispatchagent → central-gateway | `HelloListingPricingController`, Livewire `Frontend\Dashboard` (`platform_code: daydispatch`) | X-Api-Key + HMAC | Marketplace listing pricing |
| 14 | central-gateway → CentralDispatch | `POST /market-intelligence/list-prices` via `CdHttp` ('core' service) | Cox Auto credentials (gateway-held ONLY) | The actual market price data — **no portal ever calls CentralDispatch directly** |
| 15 | washinton_agent → RingCentral | R-Dialer embeddable + WebPhone instances (permission 169) | RingCentral OAuth | Click-to-call |
| 16 | All portals → SMTP | per-brand mailers (Hello sends customer/order mail; CrazyRays sends recruitment mail) | SMTP creds per deployment (SSL port 465) | Split email identity |

## 3. The flows in plain words

**Hiring (CrazyRays):** applicant on crazyrayssolutions.com.pk → Get Started (WFH/In-house first) → application POSTed to florida (`cr-application`, IP captured) → reviewed on florida Employee Review → approve creates `user` + HR `hr_employees` row (bridge) → activation email links the person's own HR portal → onboarding docs/NDA/W-9 → HR verifies → agent activated.

**Hiring (Hello):** applicant on hellotransport.com/register (State ID, official T&C, timezone, shift 10) → same user+HR pipeline, Hello-branded end to end.

**Order life:** agent quotes (Check Price via gateway) → books (payment method picker → customer emailorder2 step → paid_status 3 Confirmation Pending → admin sets Received) → listed → carrier update ⇒ **Carrier Update Approval (pstatus 36)** → approve → Listed 9 → dispatch → delivered. Same engine on washinton_agent and washinton_latest.

**ShipA1 funnel:** shipa1.com quote form → gateway slab prices shown → quote forwarded to washinton_latest as paneltype 4 → booking email carries slab tiers (Standard/Express/Premium) → Washington portal handles the order.

**AutoHaul funnel:** autohaulingquotes.com form → forwarded to BOTH shipa1 funnel and hello/washington ('*-autohaul' platform codes, +$100/+$200 adjusters, 3 offer prices).

## 4. Files & uploads — which disk has what

| Upload | Lives on | Viewed from elsewhere via |
|---|---|---|
| HR employee documents | the HR domain where uploaded (per-brand) | `admin.employees.documents.file` route (serves local, else probes sibling portals and redirects) |
| NDA ID images (agent-portal signup) | hellotransport.com / florida | same doc-file route (probes agent portals too) |
| W-9 PDFs | hellotransport.com | absolute `w9_forms.document_url` |
| Agent payment screenshots | agent portal where uploaded | `portal_file_url()` helper (local → sibling domain) |
| Customer links in emails | always hellotransport.com (`customer_url()`) / shipa1.com | n/a |

## 5. Danger zones (what breaks what)

- **Brand leaks:** any hardcoded name/logo/URL will surface on the wrong domain — always `Brand::for($person)` (person-owned things: emails, NDA, labels) vs `Brand::current()` (domain chrome).
- **Shared DB:** a migration/seeder run for one portal instantly affects all four hello/CR deployments — seeders must be updateOrInsert, never truncate.
- **pstatus space:** 20–27 is a reserved req-ship filter range; 36 = Carrier Update Approval; permission code 170 = its folder (36 and 92 are TAKEN by other permissions).
- **paid_status:** 0 Pending / 1 Updated / 2 Received / 3 Confirmation Pending — every customer submit path must set 3, only admins set 2.
- **Gateway is the only CentralDispatch caller** — if roadya.com is down, "Check Price" and ShipA1/AutoHaul quote pricing degrade everywhere at once (each caller has a try/catch fallback, orders still save).
- **Files ≠ DB:** never build an uploaded-file URL with `asset()` on multi-deployment tables — use the serving route / helpers above.
