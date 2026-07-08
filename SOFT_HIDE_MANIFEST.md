# Soft-Hide Manifest (Batch 5 · 2026-07-09)

**Rule:** items below are HIDDEN via `display:none` / a `soft-hidden` class, or commented `{{-- --}}` — **kept in code, never deleted**, so they can be re-enabled later. This file records *what* is hidden and *on which screen/file*.

Hiding mechanism: a shared CSS class `.soft-hidden { display:none !important; }` (added once to the layouts) + that class on each marked element, OR a Blade comment wrap for whole blocks.

Status legend: ⏳ pending · ✅ done

---

## A. Edit Employee — permissions list (ALL panel-type access modals)  ✅ DONE
Screen: Edit Employee (`/edit_subcontractor/{id}`) → the shared permissions checkboxes shown in
**every** panel-type access modal: Phone Quotes, Website Quotes, Test Quotes, Panel 4, Panel 5, Panel 6.
File: `resources/views/main/register/edit_employee.blade.php`
Mechanism: `$hiddenPermIds` array + `display:none` on each matching checkbox in the shared render
loop — so the marked items are hidden in **all** panel modals at once, kept in the DOM, and any
already-granted permission still functions.

**Hidden permission IDs** (Sections A + B combined):
`15,21,22,24,25,26,31,32,33,35,37,38,44,46,47,48,49,50,52,53,55,57,68,69,70,72,73,74,75,76,79,85,86,87,89,90,91,93,94,104,105,106,107,111,112,123,127,128,129,130,131,133,135,136,137,138,139,140,141,142,143,144,145,146,152,153,156,157,158,159,161`

Names hidden (⏳ list retained for reference):
Transfer Quotes, Coupons, Feedbacks, Storage, Dispatch Report, Performance Report,
Approaching Number Website, Approaching Filter, Auto Approach Filter, Offer Price,
Port Tracking, Port Price, Commission Range, Break Time, Payment System Advance Filter,
Sell Invoice, Access Auto Approach, Customer Nature (View/Update), Message Chats,
Revenue, Website Links, Managers Group, Login Ip Address, Approaching Number Phone,
Approaching Assign, Auto Approach Assign, Achievement Sheet View, Achievement Sheet View Full Screen,
Assign To Dispatcher, Profile, Employee Profile Filter, Demand Order, Freight Price checker,
Whatsapp Access, Customer Nature List/Filter,
Request Price Page, Employee Revenue (OT), Employee Revenue (DB), Employee Revenue (DIS),
Employee Revenue (Private OT), Cpanel Emails, Customer Reviews, Call/SMS Old,
Day Dispatch C|S|B|Assign, Day Dispatch C|S|B|Filter, Day Dispatch view|Shipper,
Day Dispatch view|Carrier, Day Dispatch view|Broker, Dealer Approaching view,
Dealer Approaching Assign, Dealer Approaching Filter, Templates, Profile Card,
Carrier Approaching Filter, Carrier Approaching Assign, Zoom App, Washington Gateway Portal,
Commission Report.

## B. Phone-Quotes-modal items — now hidden in ALL modals  ✅ DONE
Per client: these were marked only on the Phone Quotes modal but must hide in **every** panel-type
modal. Folded into the `$hiddenPermIds` list above (IDs 15,21,22,24,25,26,31,32,33,35,37,38,73):
Deleted, Old Quotes, Roro Invoice, View Emails, Payment System, Price Per Mile,
Group, New Show Data, Admin Issues, Carriers, Show Data, Employee Reports, Customer.

## B2. Panel Type Access modal — panel RENAMES  ✅ DONE
File: `edit_employee.blade.php` + `register/index.blade.php` (Panel Type Access checkboxes)
Names now: **Panel 1, Panel 2, Testing, Website, Panel 5, Panel 6**
(was: Auction→Panel 1, ProMAx→Panel 2 done earlier; Website Quote→Website, Panel Type 5→Panel 5,
Panel Type 6→Panel 6 done now).

## C. Edit Employee — "Shipment Status" access modal  ✅ DONE
Mechanism: CSS `<style>` block in `edit_employee.blade.php` — `div:has(> input#emp_access_shipNN)`.
Hidden ids: ship 20 (Relist), 21 (Price Raise), 22 (Approach Id), 23 (Different Port),
24 (Carrier Update), 25 (Storage), 26 (Approaching), 27 (Auction Update Request),
28 (Move To Storage), 29 (Double Booking), 33 (Auction Update).

## D. Edit Employee — "Action" access modal  ✅ DONE
Mechanism: CSS block — `.col-sm-6:has(> #emp_access_actionNN)`.
Hidden ids: action 11 (Carrier Record), 12 (Storage Record), 13 (Move to Storage),
15 (Message Center), 16 (Call Logs Center), 18 (Delete Order), 19 (Feedback),
109 (Revert to New), 110 (Allow Price Giver).

## E. Edit Employee — tabs & quotes area
File: `edit_employee.blade.php`
✅ DONE — tabs **Show Data** (`#exampleModal3`), **Profile Access** (`#exampleModal5`),
**Employee Report** (`#exampleModal7`) hidden via `button[data-target]` CSS.
✅ DONE (rest) — **Assign Daily Quotes** (`#assign_daily_qoute`), **Quotes Assign → All Quotes**
(`.col-sm-4:has(#all_qoute)`), **CSRs And Seller Agents** (`#all_ot`) hidden via CSS; the
`penalytype` panel radios **Panel 2/Testing/Website/Panel 5/6** wrapped in a `display:none` span
(Panel 1 kept). All in `edit_employee.blade.php`.

---
### Sections A–E: ✅ COMPLETE (all in edit_employee.blade.php).

### Sections F–I: ✅ COMPLETE — CSS `<style>` block added to BOTH nav layouts
(`mainsite_pages/nav.blade.php` + `mainsite_p/nav.blade.php`) hiding management items + user-dropdown
Guides/Access-Dialer by href, and the Add/View Subcontractor header icons via `:has()`. The purple
"Access Dialer" button got inline `display:none`. Add Employee button hidden in `view_register.blade.php`.

## F. Header nav — Management dropdown  ✅ DONE
Files: `partials/mainsite_pages/nav.blade.php` + `partials/mainsite_p/nav.blade.php`
Hide these `a.dropdown-item` by href: `add_guide_list` (Add Guide), `field_labels` (Field Labels),
`email-templates` (Custom Email Templates), `view_template` (Templates), `profile_card_data`
(Profile Card), `price_request_assign_dispatcher` (Dispatcher Price Assign), `commission`
(Commission Report), `cpanelemails`/Cpanel Emails, Roro Invoice, Show Data.
Plan: one `<style>` block per nav file targeting those hrefs.

## G. Header nav — user dropdown  ⏳ (next)
Hide: **Guides** (nav line ~1098), **Access Dialer** (~1122 + the purple button ~581). Both layouts.

## H. Header nav — people icon  ⏳ (next)
Hide the **View Subcontractor** `<li>` (icon `fa-users`, href `view_subcontractor`) ~line 253. Both layouts.

## I. View Employees — Add Employee button  ⏳ (next)
File: `view_register.blade.php` (+ list page). Hide the **Add Employee** button.

## F. Header nav — Management dropdown
Files: `partials/mainsite_pages/nav.blade.php`, `partials/mainsite_p/nav.blade.php`
Hide (⏳): Add Guide, Field Labels, Custom Email Templates, Templates, Profile Card,
Dispatcher Price Assign, Commission Report, Cpanel Emails, Roro Invoice, Show Data.

## G. Header nav — user dropdown (top-right)
Hide (⏳): Guides, Access Dialer.

## H. Header nav — icons
Hide (⏳): the **View Employees people icon** in the top header.

## I. View Employees screen
File: `resources/views/main/register/view_register.blade.php` (+ list page)
Hide (⏳): the **Add Employee** button.

---

## Notes
- Question for client: hidden permissions — should they stay FUNCTIONAL if already granted (just not shown) or be fully inert? Default assumption: **display-only hide** (still function if already set), since it's "soft hide."
- Some items appear both as a header-menu link AND a permission — hiding is applied at each place it renders.
