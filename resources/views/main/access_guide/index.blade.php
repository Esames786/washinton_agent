@extends('layouts.innerpages')
@section('template_title', 'Portal Access Guide')
@section('content')
{{-- innerpages sidebar calls check_panel()/count helpers defined in this (function_exists-
     guarded) partial — every innerpages page must include it itself, this one was missing it
     (500 "Call to undefined function check_panel()" for managers/admins, client 2026-09-04). --}}
@include('partials.mainsite_pages.return_function')
@php
    /* Admin + Manager only — everyone else bounces to the dashboard. */
    $__u = Auth::user();
    $__isAllowed = (int) ($__u->role ?? 0) === 1
        || optional($__u->userRole)->name === 'Admin'
        || (int) ($__u->role ?? 0) === 9
        || optional($__u->userRole)->name === 'Manager';
@endphp
@if(!$__isAllowed)
    <script>window.location = '/dashboard';</script>
@else
@php
    // The definitive access list. Codes match $options_phone in edit_subcontractor exactly.
    // Grouped so an admin can find things the way they think about them.
    $groups = [
        'Order Folders (sidebar)' => [
            [0,'New','New incoming quotes folder — every fresh quote lands here first.'],
            [1,'Interested','Orders marked Interested by the agent.'],
            [2,'Follow More','Follow-up folder for quotes that need another call.'],
            [3,'Asking Low','Customers asking below workable price.'],
            [4,'Not Interested','Customers who declined.'],
            [5,'No Response','Customers who stopped answering.'],
            [6,'Time Quote','Quotes to be revisited at a specific time.'],
            [7,'Paymen tMissing','Booked but payment not submitted yet (Missing Payment folder).'],
            [8,'Booked','Confirmed bookings folder.'],
            [66,'Double Booking','Duplicate-booking review folder.'],
            [9,'Listed','Orders posted to the load board.'],
            [170,'Carrier Update Approval','Folder below Listed: orders held after a carrier update until an admin/manager approves them back to Listed.'],
            [10,'Schedule','Orders with pickup scheduled.'],
            [11,'Pickup','Vehicles picked up, in transit.'],
            [12,'Delivered','Delivered orders.'],
            [13,'Completed','Fully closed orders.'],
            [14,'Cancel','Cancelled orders folder.'],
            [16,'Owes Money','Customers with outstanding balance.'],
            [17,'Carrier Update','Orders whose carrier info was updated.'],
            [28,'On Approval','Orders waiting for approval.'],
            [29,'OnApproval Cancel','Cancelled while waiting on approval.'],
            [77,'Move OnApprovalCancel To Cancel','Button to push an OnApproval-Cancel order into the real Cancel folder.'],
            [30,'Approaching','Carrier-approaching workflow folder.'],
            [34,'Filtered Data','Advanced filtered order listing.'],
        ],
        'Quotes & Pricing' => [
            [18,'Car Quote','Create/see car & motorcycle quotes.'],
            [19,'Heavy Quote','Heavy equipment quotes.'],
            [92,'Freight Quote','Freight (dry van) quotes.'],
            [110,'Testing Quote','Testing-panel quotes.'],
            [22,'Old Quotes','Archive of old quotes.'],
            [113,'Allow Vehicle','May create vehicle-type quotes.'],
            [114,'Allow Heavy','May create heavy-type quotes.'],
            [115,'Allow Freight','May create freight-type quotes.'],
            [123,'Request Price Page','Access the price-request screen.'],
            [154,'Manager Check Price','Manager-level Check Price access.'],
            [93,'Freight Price checker','Price-checker role for freight.'],
            [72,'Offer Price','See/set the offer price.'],
            [75,'Port Price','Port pricing screen.'],
            [33,'Price Per Mile','Price-per-mile report.'],
            [90,'Demand Order','Demand-order screen.'],
        ],
        'Phone Number Visibility' => [
            [42,'Show Customer Number','See the customer\'s phone number on orders/search rows. Without it the number is hidden.'],
            [60,'Show Driver Number','See the driver\'s phone number.'],
            [121,'Show Pickup Phone','See pickup-location phone on order rows.'],
            [122,'Show Delivery Phone','See delivery-location phone on order rows.'],
            [41,'Update Phone Digits','Edit stored phone numbers.'],
            [124,'Block Phone View','View the blocked-numbers list.'],
            [125,'Block Phone Approve','Approve blocking a number.'],
            [134,'Call/SMS With App','Call/text through the calling app.'],
            [135,'Call/SMS Old','Legacy calling method.'],
            [158,'Zoom App','Zoom-based calling.'],
            [169,'R-Dialer (RingCentral Phone)','Open the built-in RingCentral dialer (Access Dialer button).'],
        ],
        'Subcontractors & HR' => [
            [20,'Add/Edit Subcontractor','Create and edit agent accounts (View Employees screen).'],
            [43,'Flag Subcontractors','Flag an agent for attention.'],
            [56,'Subcontractor Rating','Rate agents.'],
            [32,'Subcontractor Reports','Agent report screens.'],
            [132,'Agents Reports','Agent activity reports.'],
            [86,'Subcontractor Profile Filter','Filter agent profiles.'],
            [128,'Subcontractor Revenue (OT)','Revenue report — order takers.'],
            [127,'Subcontractor Revenue (DB)','Revenue report — delivery boys.'],
            [129,'Subcontractor Revenue (DIS)','Revenue report — dispatchers.'],
            [130,'Subcontractor Revenue (Private OT)','Revenue report — private OTs.'],
            [87,'Break Time','Break-time tracking.'],
            [88,'Freeze Time History','History of account freezes.'],
            [51,'Last Activity','See users\' last activity.'],
            [52,'Login Ip Address','See login IPs.'],
            [63,'Roles','Manage roles and their default permissions.'],
            [166,'CrazyRays Applications','The Campaign Users screen — review/approve CrazyRays job applications (the badge counts this screen\'s pending rows).'],
        ],
        'Payments & Invoices' => [
            [31,'Payment System','Legacy payment system screens.'],
            [89,'Payment System Advance Filter','Advanced filters in payment system.'],
            [164,'Admin Payment System','Admin side of the agent-payments module (approve/receive).'],
            [165,'Agent Payment System','Agent side — submit payments with screenshot proof.'],
            [23,'Transportation Invoice','Create/view transport invoices.'],
            [73,'Roro Invoice','RORO invoices.'],
            [91,'Sell Invoice','Sell invoices.'],
            [85,'Commission Range','Commission range settings.'],
            [161,'Commission Report','Commission reports.'],
            [46,'Revenue','Revenue screens.'],
        ],
        'Logout Questions' => [
            [116,'Logout Questions (Show Logout Questions)','Agent must answer the configured questions before logging out.'],
            [117,'Logout Questions Answer View','View agents\' submitted answers.'],
            [118,'Logout Questions Comments','Comment on logout answers.'],
            [120,'Logout Questions View & Add','Manage the question list.'],
        ],
        'Communication & Support' => [
            [163,'View Mailbox','The built-in email mailbox (assigned cPanel mailbox).'],
            [162,'cPanel Email Management','Manage cPanel email accounts.'],
            [131,'Cpanel Emails','Legacy cPanel email screen.'],
            [25,'View Emails','View sent emails.'],
            [151,'Chat Support','The live-chat support screen.'],
            [112,'Message Chats','Internal message chats.'],
            [104,'Whatsapp Access','WhatsApp tools.'],
            [36,'Questions/Answers','Internal Q&A module.'],
            [21,'Admin Issues','Report-to-admin issues list.'],
            [49,'Feedbacks','Customer feedback screens.'],
            [133,'Customer Reviews','Review management.'],
            [48,'Website Links','Review-website links management.'],
            [47,'Coupons','Coupon management.'],
        ],
        'Approaching & Marketing' => [
            [68,'Approaching Number Phone','Approach list — phone numbers.'],
            [69,'Approaching Number Website','Approach list — website leads.'],
            [70,'Approaching Assign','Assign approaching leads.'],
            [142,'Approaching Filter','Filter approaching leads.'],
            [94,'Access Auto Approach','Auto-approach module.'],
            [145,'Auto Approach Assign','Assign auto-approach leads.'],
            [146,'Auto Approach Filter','Filter auto-approach leads.'],
            [101,'Carrier Approaching Update','Update carrier-approach records.'],
            [102,'Carrier Approaching View','View carrier-approach records.'],
            [155,'Carrier Approaching view (New)','New carrier-approaching view.'],
            [156,'Carrier Approaching Assign','Assign carrier approaches.'],
            [157,'Carrier Approaching Filter','Filter carrier approaches.'],
            [140,'Dealer Approaching view','Dealer approach view.'],
            [144,'Dealer Approaching Assign','Assign dealer approaches.'],
            [141,'Dealer Approaching Filter','Filter dealer approaches.'],
            [143,'Day Dispatch C|S|B Assign','DayDispatch carrier/shipper/broker assignment.'],
            [136,'Day Dispatch C|S|B Filter','DayDispatch record filters.'],
            [137,'Day Dispatch view | Shipper','DayDispatch shipper records.'],
            [138,'Day Dispatch view | Carrier','DayDispatch carrier records.'],
            [139,'Day Dispatch view | Broker','DayDispatch broker records.'],
            [147,'Website Query','Website enquiry list.'],
            [148,'Website Query Assign','Assign website enquiries.'],
            [149,'How Did You Find Us?','Marketing-source answers.'],
            [150,'How Did You Find Us? Phone','Marketing-source (phone).'],
            [103,'Carrier Blocking','Block a carrier.'],
            [24,'Carriers','Carrier list.'],
            [38,'Customer','Customer list.'],
            [105,'Customer Nature (View/Update)','Customer nature notes.'],
            [106,'Customer Nature List/Filter','Customer nature listing.'],
        ],
        'Reports, Sheets & Data' => [
            [26,'Show Data','Data listing screens.'],
            [37,'New Show Data','Newer data listing.'],
            [27,'Sheets','Sheets module.'],
            [74,'Achievement Sheet View','View achievement sheets.'],
            [111,'Achievement Sheet Add/Edit','Edit achievement sheets.'],
            [107,'Achievement Sheet View Full Screen','Full-screen achievement view.'],
            [55,'Dispatch Report','Dispatch reports.'],
            [57,'Performance Report','Performance reports.'],
            [62,'QA Report','QA reporting.'],
            [64,'Update QA History','Edit QA history on orders.'],
            [65,'View QA History','View QA history on orders.'],
            [71,'Booker Name','See who booked the order.'],
            [44,'Transfer Quotes','Move quotes between agents.'],
            [54,'Shipment Status','Shipment status tools.'],
            [109,'Authorization Form List','Authorization forms.'],
            [100,'Field Labels','Field label management.'],
            [53,'Storage','Storage screens.'],
            [35,'Group','Group management.'],
            [50,'Managers Group','Managers group.'],
            [76,'Assign To Dispatcher','Assign orders to dispatchers.'],
            [79,'Profile','Profile screens.'],
            [153,'Profile Card','Profile card view.'],
            [152,'Templates','Email templates.'],
        ],
        'Portals, Guides & Misc' => [
            [159,'Washington Gateway Portal','Cross-portal Washington gateway access.'],
            [160,'AutoHaul Gateway Portal','AutoHaul quotes portal access.'],
            [167,'Guide Videos (Manage)','Upload/manage training guide videos.'],
            [168,'Guide Videos (View)','Watch training guide videos (Guide Videos menu item).'],
            [15,'Deleted','Deleted-records folder.'],
        ],
    ];
@endphp
<style>
    .ag-wrap { max-width: 1100px; margin: 20px auto; padding: 0 12px; }
    .ag-head { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 6px; }
    .ag-head h3 { margin: 0; font-weight: 800; color: #0f172a; }
    .ag-sub { color: #64748b; font-size: 13px; margin-bottom: 14px; }
    .ag-search { max-width: 380px; }
    .ag-group { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; margin-bottom: 16px; overflow: hidden; }
    .ag-group h5 { margin: 0; padding: 11px 16px; background: #f8fafc; border-bottom: 1px solid #e5e7eb; font-size: 14px; font-weight: 700; color: #0f172a; }
    .ag-row { display: flex; gap: 12px; padding: 9px 16px; border-bottom: 1px solid #f1f5f9; font-size: 13px; align-items: baseline; }
    .ag-row:last-child { border-bottom: 0; }
    .ag-code { flex: 0 0 44px; font-family: monospace; font-weight: 700; color: #2563eb; }
    .ag-name { flex: 0 0 250px; font-weight: 600; color: #111827; }
    .ag-desc { flex: 1; color: #475569; }
    .ag-row.ag-hit .ag-name { background: #fef9c3; }
    @media (max-width: 700px) { .ag-row { flex-wrap: wrap; } .ag-name { flex-basis: 100%; } }
</style>
<div class="ag-wrap">
    <div class="ag-head">
        <h3>🔑 Portal Access Guide</h3>
        <input type="text" id="agSearch" class="form-control ag-search" placeholder="Search a permission… (name, number or word)">
    </div>
    <p class="ag-sub">
        Every access code you can grant in <b>Edit Subcontractor → Subcontractor Access</b>, with what it actually does.
        Permissions are <b>per panel</b> — granting a code on Multan does not grant it on Lahore.
        Admins (role&nbsp;1) automatically have everything.
    </p>
    @foreach($groups as $title => $items)
        <div class="ag-group" data-group>
            <h5>{{ $title }}</h5>
            @foreach($items as $it)
                <div class="ag-row" data-search="{{ strtolower($it[0] . ' ' . $it[1] . ' ' . $it[2]) }}">
                    <span class="ag-code">{{ $it[0] }}</span>
                    <span class="ag-name">{{ $it[1] }}</span>
                    <span class="ag-desc">{{ $it[2] }}</span>
                </div>
            @endforeach
        </div>
    @endforeach
</div>
<script>
    document.getElementById('agSearch').addEventListener('input', function () {
        var q = this.value.trim().toLowerCase();
        document.querySelectorAll('.ag-row').forEach(function (r) {
            var hit = !q || r.getAttribute('data-search').indexOf(q) !== -1;
            r.style.display = hit ? '' : 'none';
            r.classList.toggle('ag-hit', !!q && hit);
        });
        document.querySelectorAll('[data-group]').forEach(function (g) {
            var any = Array.prototype.some.call(g.querySelectorAll('.ag-row'), function (r) { return r.style.display !== 'none'; });
            g.style.display = any ? '' : 'none';
        });
    });
</script>
@endif
@endsection
