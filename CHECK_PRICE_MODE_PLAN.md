# Check Price — "Open / Enclosed first" change map
> **STATUS (2026-08-13): PHASE 1 IMPLEMENTED** — all 12 blades (selector + guard + single-mode
> render + breakdown filter) and both OrderPricingControllers (requested_mode recorded). Verified
> 7/7 replacements per file, controllers lint-clean. Phase-2 gateway optimisation still pending.
**Client ask:** on the Hello (hellotransport.com) and Washington (washington.shawntransport.com) quote forms, the agent must pick **Open Transport or Enclosed Transport BEFORE clicking Check Price**, and the result should use that choice.

## How Check Price works today (verified in code)

1. Button `#checkPriceNew` ("Check Price (New)") on the quote screens → AJAX `POST /orders/{id}/pricing/check`.
2. `OrderPricingController@check` builds stops+vehicles from the ORDER and calls the central gateway with a FIXED platform code (`hello_transport` on hello, `washington` on washington). It never sends a transport mode.
3. The gateway ALWAYS computes **both** modes (open + enclosed) in one response — `primary.modes.open` and `primary.modes.enclosed`.
4. The blade's JS renders the "Pricing Result" modal with **two cards side by side**: `renderModeCard('Open', modes.open)` + `renderModeCard('Enclosed', modes.enclosed)`, plus a breakdown table looping `['open','enclosed']`.
5. A snapshot goes to `order_price_requests` (offer_open + offer_enclosed both stored) for "Previous Prices (New)".

**Why "autohaul quotes look different":** autohaul-originated LEADS are priced at intake by `AutohaulQuoteController` with platform `hello-autohaul` / `washington-autohaul` → the gateway returns **slab prices** (`offer_prices[3]`). The Check Price button on an order always uses `hello_transport` / `washington` → **single offer + commission**. Two different platform codes, two response shapes — the button itself is the same everywhere.

## The change — 3 touch points per screen, same pattern in BOTH projects

**No gateway change needed now.** The gateway already returns both modes; we ask the agent first and show only the chosen one. (A later "central pricing fix" can pass the mode upstream so the gateway skips computing the unused mode — noted at the bottom.)

### A) Blades — 6 per project (12 total), all share the identical JS block

| Project | Blades (each has button + JS + modal) |
|---|---|
| washinton_agent | `main/phone_quote/new_quote/index.blade.php` (car new), `new/new_edit.blade.php` (car edit), `new_quote_frieght/index.blade.php`, `new/new_edit_frieght.blade.php`, `new_quote_heavy/index.blade.php`, `new/new_edit_heavy.blade.php` |
| washinton_latest | the same 6 paths |

Per blade, three anchors (line numbers from `new/new_edit.blade.php` in washinton_agent as reference; find the same markers in each file):

1. **Button area** (`id="checkPriceNew"`, ~line 2567): add a required selector right before the button —
   `<select id="checkPriceMode" class="form-control d-inline-block" style="width:auto"><option value="">-- Transport Type --</option><option value="open">Open Transport</option><option value="enclosed">Enclosed Transport</option></select>`
   (pre-select from the order's existing `transport` column when set).
2. **Click handler** (`$('#checkPriceNew').on('click'…)`, ~line 9353): guard first —
   `var mode = $('#checkPriceMode').val(); if(!mode){ showError('Please select Open or Enclosed transport first.'); $('#priceModalNew').modal('show'); return; }`
   and send it: `data: { mode: mode }` on the AJAX call.
3. **Renderers** (~lines 9409-9410 card calls + ~9424 breakdown loop): render only the chosen mode —
   `${renderModeCard(mode === 'enclosed' ? 'Enclosed' : 'Open', modes[mode])}` (full-width `col-md-12` instead of two `col-md-6`), and `[mode].forEach(...)` in the breakdown. Market-details header already uses the primary mode object — point it at `modes[mode]` too.

### B) Controllers — 1 per project

`app/Http/Controllers/OrderPricingController.php@check` (both projects):
- accept + validate `mode` (`nullable|in:open,enclosed`; treat missing as legacy = both, so nothing breaks before all blades are deployed);
- store the chosen mode in the `order_price_requests` snapshot (reuse `request_payload` json — no migration needed);
- keep returning the full gateway response (UI filters). Optionally also echo `chosen_mode` in the JSON for the modal title.

### C) Explicitly NOT touched
- `AutohaulQuoteController` (both) — autohaul intake pricing stays as is (client asked only about the quote-form button).
- `NewQuote@fetchShipA1Pricing` — ShipA1 booking-email tiers keep showing both modes (customer-facing email).
- central-gateway — zero changes in this phase.

## Later "central pricing fix" (phase 2, when you say go)
Pass `transport_mode` through `GatewayClient` → gateway `PricingEngine::quote` honours it and computes only that mode (halves CentralDispatch calls + cache writes for Check Price). Backwards-compatible: absent param = both modes, so old portals keep working. Files: gateway `PricingEngine.php` (mode loop, ~line 79) + both portals' `OrderPricingController` payload.

## Estimate
12 blades × (selector + guard + renderer) + 2 controllers ≈ **3–4 hours** including a click-through of all 12 screens. Phase-2 gateway optimisation ≈ 1 hour more, separate deploy (roadya).
