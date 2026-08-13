# Central Pricing (roadya.com) — Complete Request/Response Map
**Every price request in the ecosystem goes through ONE endpoint on central-gateway.** This document lists who calls it, with what identity, what the gateway does inside, and what each caller gets back. Companion overview: `ECOSYSTEM_CONNECTIONS.md`.

## 1. The single endpoint

```
POST https://roadya.com/api/v1/pricing/quote        (routes/api.php → PricingController@quote)
```

**Auth (middleware `internal.sig` → VerifyInternalSignature):** every caller sends
```
X-Api-Key:       <client key>            → looked up in gateway DB `api_clients` (is_active=1)
X-Api-Timestamp: <unix ts>
X-Api-Nonce:     <uuid>
X-Api-Signature: HMAC-SHA256(secret, "POST\n/api/v1/pricing/quote\n{ts}\n{nonce}\nsha256(body)")
```
The `api_clients` row carries the `platform_code`, allowed IPs, and secret. `daily.limit` middleware enforces a per-client daily quota (pricing only; the `/proxy/*` routes are unlimited). Every call is logged to `api_request_logs` with full request+response payloads — that table is the first place to debug "why did portal X get price Y".

**Request payload (canonical):**
```json
{
  "platform_code": "shipa1",
  "stops": [ {"stopNumber":1,"zipCode":"11580","state":"NY","city":"Valley Stream"},
             {"stopNumber":2,"zipCode":"10913","state":"NY","city":"Blauvelt"} ],
  "vehicles": [ {"year":2017,"make":"Toyota","model":"Sienna","type":"Car"} ],
  "referenceId": "WA-SHIPA1-289"
}
```

## 2. What the gateway does inside (PricingEngine::quote)

1. Normalises stops/vehicles (zip lane if both zips present, else state lane).
2. For EACH vehicle × EACH mode (**open AND enclosed — always both**):
   a. **Cache check** — `pricing_cache` by vehicle key `YMM:year|MAKE|MODEL`, transport mode, lane, within `pricing_match_month_window` months → cache hit skips the upstream call (the "Cache Hit" badge agents see).
   b. On miss — **CentralDispatch Market Intelligence** `POST /market-intelligence/list-prices` (via `CdHttp`, Cox Auto vnd.coxauto.v1+json). Returns lowPrice / meanPredictedPrice / highPrice / dispatchPrice / listingPrice. Result is cached for the month.
3. **Platform shaping (formatModeResponse)** — the SAME market numbers, different commercial treatment per `platform_code` (section 3).
4. Response: `{ primary: { modes: { open: {...}, enclosed: {...} } }, items: [...], count, cache_hit }` — each mode block always carries `driver_price {low, mid, high}`, `market {distances, cities, listing/dispatch price+status+dates}`, `transport_mode`, `cache_hit`.

## 3. Platform shaping — what each platform_code gets EXTRA

| platform_code | Owner portal | Pricing model | Extra fields in each mode block |
|---|---|---|---|
| `hello_transport` | washinton_agent | **Single offer** = driver mid + commission slab (`CommissionSlab` by price band) | `offer_price {value, commission, currency}` |
| `washington` | washinton_latest | Same single-offer model | `offer_price {value, commission}` |
| `shipa1` | shipa1_updated + the ShipA1 pricing email in both order portals | **Slab pricing**: driver mid + default adjuster (`shipa1_default_markup_open/enclosed` setting) → run through Fixed($)/percent slabs (`shipa1_price_count`, usually 3) | `offer_prices [ {slab_name, base_price, commission_rate, value…} ×3 ]`, `pricing_mode: "slab"`, `adjusted_base` |
| `washington-autohaul`, `hello-autohaul`, `shawntransport-autohaul`, `daydispatch-autohaul` | AutoHaul funnel entering each portal | Slab pricing like shipa1 but per-platform `AutohaulSetting` (+$100/+$200-style adjusters) + `AutohaulSlab` rows | `offer_prices[≤3]`, `pricing_mode: "slab"`, `adjusted_base` |
| `daydispatch` | daydispatchagent | Four computed figures (shipper_pay from low, carrier_ask from high, dispatch_avg from mid, prev_moved from dispatch price), each with its own adjustment setting | `offer_price {value, adjustment}` + `daydispatch_prices {market_rate…}` |

**Rule of thumb:** the market data is identical for everyone — only `offer_price` / `offer_prices` differs, and it is controlled ENTIRELY by gateway-side settings/slabs per platform (admin-tunable on roadya, no portal deploy needed to change margins).

## 4. Every caller — file-level map

### washinton_agent (hellotransport.com + florida)
| Call site | platform_code | Trigger | What it does with the response |
|---|---|---|---|
| `app/Http/Controllers/OrderPricingController.php` (`POST orders/pricing/check`, + `pricing/history`) | `hello_transport` | **"Check Price (New)"** button on the 6 quote screens (car/freight/heavy × new/edit) | Renders the "Pricing Result" modal — Open + Enclosed cards with Low/Mid/High bars, Offer, Commission, market details; result also stored for "Previous Prices (New)" |
| `app/Http/Controllers/phone_quote/AutohaulQuoteController.php@store` | `config('gateway.autohaul.platform')` → `hello-autohaul` | AutoHaul lead arriving from hualt | Stores the quote (`platform_code='hello-autohaul'`) with slab prices; round-robins it to an eligible agent |
| `app/Http/Controllers/phone_quote/NewQuote.php@fetchShipA1Pricing` (helper `callGatewayDirect` for autohaul creds) | `shipa1` (request_hauling=0) or autohaul | ShipA1/AutoHaul order's **booking-confirmation email** | Builds the 3-tier table (Standard "No ETA" / Express "ETA Available" / Premium "1-3 days") from `offer_prices` for open+enclosed |
| `app/Services/CentralGateway/GatewayClient.php` | — | shared HTTP client | signs the HMAC headers from `services.central_gateway` config |

### washinton_latest (washington.shawntransport.com)
Identical trio, own credentials: `OrderPricingController` (`platform_code: washington`) for Check Price (New) on its 6 quote screens; `NewQuote@fetchShipA1Pricing` (`shipa1`/`washington-autohaul`) for ShipA1 booking emails; `Services/CentralGateway/GatewayClient`.

### shipa1_updated (shipa1.com)
`app/Http/Controllers/QuoteController.php`: detects autohaul leads (`_gateway_platform` → `washington-autohaul`, else `shipa1`) → gateway quote → shows slab offers on the site → then forwards the whole quote to washinton_latest via `POST https://roadya.shipa1.com/api/v2/website-quote` (order created as paneltype 4). `app/Service/CentralGateway/GatewayClient.php` signs.

### hualt (autohaulingquotes.com)
Does NOT call the gateway itself. `app/Services/WashingtonService.php` (→ washinton_latest, `washington-autohaul` key) and `app/Services/HelloTransportService.php` (→ washinton_agent, `hello-autohaul` key) forward the lead; the RECEIVING portal calls the gateway with its autohaul platform code.

### daydispatchagent
`app/Http/Controllers/HelloListingPricingController.php` + `app/Http/Livewire/Frontend/Dashboard.php` (`platform_code: daydispatch`) via its own `Services/CentralGateway/GatewayClient.php`.

## 5. "The central work will work perfect if…" — health checklist

For pricing to work end-to-end, per portal you need exactly four things:
1. **An active `api_clients` row on roadya** with the right `platform_code`, key/secret matching the portal's env (`services.central_gateway.*` / `gateway.autohaul.*`), and the portal server's IP in `allowed_ips` (if set).
2. **Signature clock sanity** — HMAC includes the timestamp; a badly skewed server clock = 401 Unauthorized.
3. **Daily limit headroom** — `daily.limit` middleware; hitting the quota returns an error the portals surface as "pricing unavailable" (orders still save without a price).
4. **Platform settings/slabs configured** on roadya (shipa1 markup+slabs, AutohaulSetting per autohaul code, commission slabs for hello_transport/washington, daydispatch adjustments) — otherwise offers compute from zero adjusters.

**Debug order when a portal "gets no price":** roadya `api_request_logs` (was the request received? what came back?) → `[GW]`/`[PRICING]` lines in roadya laravel.log (auth vs engine failure) → `MI_LIST_PRICES_FAIL` entries (upstream CentralDispatch issue) → portal-side log of the try/catch around the gateway call.

**Where the domain migration could NOT break it:** clients are identified by API key, never by caller domain — that's why the CrazyRays migration required zero roadya changes.
