<?php

namespace App\Http\Controllers;

use App\Mail\QuoteSubmissionMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FrontendController extends Controller
{
    public function __construct()
    {
        // #8 (round-2): florida is a PORTAL, not a marketing site — every public marketing page
        // (About Us, Our Services, Get a Quote, FAQ, …) redirects straight to the login. The
        // Hello landing keeps its marketing pages (is_agent_portal false there). GET only, so
        // the Hello site's form POSTs (quote submit etc.) are untouched.
        $this->middleware(function ($request, $next) {
            if (config('app.is_agent_portal') && $request->isMethod('get')) {
                return redirect('/loginn');
            }
            return $next($request);
        });
    }

    public function home(Request $request)
    {
        // On the agent-portal deployment (florida) there is no public marketing site —
        // land straight on the login screen.
        if (config('app.is_agent_portal')) {
            return redirect('/loginn');
        }

        // Logged-in CR users visiting the hellotransport marketing page get sent back to CrazyRays
        if (Auth::check()) {
            $isCrUser = (int) (Auth::user()->is_crazyrays ?? 0) === 1
                || $request->session()->get('cr_origin') === 'crazyrays'
                || \App\CrApplication::where('email', Auth::user()->email)->exists();
            if ($isCrUser) {
                $crBase = rtrim(config('bridge.crazyrays.base_url', 'https://crazyrayssolutions.com.pk'), '/');
                return redirect()->away($crBase ?: 'https://crazyrayssolutions.com.pk');
            }
        }

        return view('main.frontend.home');
    }

    public function aboutUs()
    {
        return view('main.frontend.about-us');
    }

    public function faq()
    {
        return view('main.frontend.faq');
    }

    public function terms()
    {
        return view('main.frontend.terms-conditions');
    }

    public function contactUs()
    {
        return view('main.frontend.contact-us');
    }

    public function testimonials()
    {
        return view('main.frontend.testimonials');
    }

    public function privacy()
    {
        return view('main.frontend.privacy-policy');
    }

    /**
     * Show the quote request form.
     * Reads ?type= from query string (Car, Heavy Equipment, Dryvan, Motorcycle, ATV/UTV, Golf Cart).
     */
    public function quoteRequest(Request $request)
    {
        // W-07(a): each service page opens its own form. Types come from
        // config/hello_services.php `quote_type`; keep the two lists in step.
        $allowed = ['Car', 'Heavy Equipment', 'Dryvan', 'Motorcycle', 'ATV/UTV', 'Golf Cart',
                    'Boat', 'RV', 'RoRo'];
        $type = in_array($request->query('type'), $allowed)
            ? $request->query('type')
            : 'Car';

        return view('main.frontend.get-qoute', compact('type'));
    }

    /**
     * Handle quote form submission — saves to ShipaQuery so it appears in /view_query.
     */
    public function submitQuoteRequest(Request $request)
    {
        $type      = $request->Select_Vehicle ?? 'Car';
        $isVehicle = in_array($type, ['Car', 'Motorcycle', 'ATV/UTV', 'Golf Cart']);

        $zipPattern = ['required', 'string', 'max:100', 'regex:/^[^,]+,\s*[A-Za-z]{2},\s*\d{5}$/'];

        // RoRo ships OVERSEAS, so its destination is a country and city. Holding it to the
        // US "City, ST, 12345" pattern rejected every genuine RoRo enquiry — the only way
        // through was to type a fake US address.
        $isRoro = $type === 'RoRo';

        $request->validate([
            'Custo_Name'        => 'required|string|max:100',
            'Custo_Phone'       => 'required|string|max:30',
            'Custo_Email'       => 'required|email|max:150',
            'From_ZipCode'      => $zipPattern,
            'To_ZipCode'        => $isRoro ? 'nullable|string|max:30' : $zipPattern,
            'To_Country'        => $isRoro ? 'required|string|max:100' : 'nullable',
            'To_City'           => $isRoro ? 'required|string|max:100' : 'nullable',
            'Carrier_Type'      => $isVehicle ? 'required|string' : 'nullable',
            'Carrier_Condition' => $isVehicle ? 'required|string' : 'nullable',
        ], [
            'To_Country.required'     => 'Please enter the destination country.',
            'To_City.required'        => 'Please enter the destination city.',
            'From_ZipCode.regex'      => 'Origin must be in format: City, ST, 12345 (e.g. Brooklyn, NY, 11234)',
            'To_ZipCode.regex'        => 'Destination must be in format: City, ST, 12345 (e.g. Dallas, TX, 75201)',
            'Carrier_Type.required'   => 'Please select a carrier type.',
            'Carrier_Condition.required' => 'Please select vehicle condition.',
        ]);

        // Parse origin / destination — "City, State, Zip" or bare zip
        $originParts      = array_map('trim', explode(',', $request->From_ZipCode));
        $destinationParts = array_map('trim', explode(',', $request->To_ZipCode));

        $origincity       = $originParts[0] ?? '';
        $originstate      = $originParts[1] ?? '';
        $originzip        = $originParts[2] ?? $originParts[0] ?? '';
        $originzsc        = trim("{$origincity},{$originstate},{$originzip}", ',');

        if ($isRoro) {
            // International: city + country stand in for city + state, so the order taker
            // still sees a readable destination in the usual column.
            $destinationcity  = trim((string) $request->To_City);
            $destinationstate = trim((string) $request->To_Country);
            $destinationzip   = trim((string) $request->To_ZipCode);
            $destinationzsc   = trim("{$destinationcity},{$destinationstate},{$destinationzip}", ',');
        } else {
            $destinationcity  = $destinationParts[0] ?? '';
            $destinationstate = $destinationParts[1] ?? '';
            $destinationzip   = $destinationParts[2] ?? $destinationParts[0] ?? '';
            $destinationzsc   = trim("{$destinationcity},{$destinationstate},{$destinationzip}", ',');
        }

        // Vehicle identity — varies by type
        $year  = null;
        $make  = null;
        $model = null;

        // Website point 7 — heavy equipment, boats and RVs follow ShipA1's forms: separate
        // year / make / model, dimensions in feet AND inches, trailer type, load and unload
        // method, condition, auction and modification. RoRo is NOT here: it is international
        // and asks for a destination country rather than measurements.
        $dimensional = ['Heavy Equipment', 'Boat', 'RV'];

        if (in_array($type, $dimensional)) {
            $year  = $request->Vehicle_Year  ?? null;
            $make  = $request->Vehicle_Make  ?? null;
            $model = $request->Vehicle_Model ?? null;
        } elseif (in_array($type, ['Car', 'Motorcycle', 'ATV/UTV', 'Golf Cart'])) {
            $year  = $request->Car_Year  ?? null;
            $make  = $request->Car_Make  ?? null;
            $model = $request->Car_Model ?? null;
        }
        $ymk = trim("{$year} {$make} {$model}");

        // Per-vehicle price from the public quote form (single vehicle here).
        $vehiclePrice = $request->Vehicle_Price ?? null;

        // Transport / shipping mode
        // Cars/Motorcycles/ATV/Golf Cart → Carrier_Type (Open/Enclosed/Drive Away)
        // Heavy Equipment & Dryvan      → Shipping_Mode (FTL/LTL)
        // RV asks it as Load Type; heavy and boat carry their trailer in trailer_type instead.
        $transport = match (true) {
            $type === 'Dryvan'                              => $request->Shipping_Mode ?? null,
            $type === 'RV'                                  => $request->Load_Type ?? null,
            in_array($type, ['Heavy Equipment', 'Boat'])    => null,
            default                                         => $request->Carrier_Type ?? null,
        };

        // Weight — dedicated field per type
        $weight = null;
        if (in_array($type, $dimensional)) {
            $weight = $request->Vehicle_Weight ?? null;
        } elseif ($type === 'Dryvan') {
            $weight = $request->Freight_Weight ?? null;
        }

        // Dimensions — Heavy Equipment only
        // Feet and inches arrive separately, as ShipA1 asks for them.
        $isDim    = in_array($type, $dimensional);
        $lengthFt = $isDim ? ($request->Length_Ft ?? null) : null;
        $lengthIn = $isDim ? ($request->Length_In ?? null) : null;
        $widthFt  = $isDim ? ($request->Width_Ft  ?? null) : null;
        $widthIn  = $isDim ? ($request->Width_In  ?? null) : null;
        $heightFt = $isDim ? ($request->Height_Ft ?? null) : null;
        $heightIn = $isDim ? ($request->Height_In ?? null) : null;

        // car_type drives which table the order taker moves the lead into:
        //   1 = Car / Motorcycle / ATV / Golf Cart  → order table (standard)
        //   2 = Heavy Equipment                     → order table (heavy fields)
        //   3 = Dryvan / Freight                    → order table + order_freight table
        $carType = match($type) {
            'Heavy Equipment', 'Boat', 'RV', 'RoRo' => 2,
            'Dryvan'                                => 3,
            default                                 => 1,
        };

        // Additional info — freight class + commodity for Dryvan
        // Stored in add_info so it transfers when the lead is converted to an order
        $addInfo = null;
        if ($type === 'Dryvan') {
            $parts = [];
            if ($request->frieght_class)        $parts[] = 'Freight Class: '  . $request->frieght_class;
            if ($request->Shipment_Preferences) $parts[] = 'Commodity: '      . $request->Shipment_Preferences;
            if ($request->Shipping_Mode)        $parts[] = 'Shipping Mode: '  . $request->Shipping_Mode;
            $addInfo = implode(' | ', $parts) ?: null;
        }

        // Heavy / boat / RV photo (optional) — now offered on all three, as ShipA1 does.
        $image = null;
        if ($isDim && $request->hasFile('Vehicle_Image')) {
            try {
                $image = $request->file('Vehicle_Image')->store('quote-images', 'public');
            } catch (\Throwable $e) {
                Log::warning('FrontendController: quote image upload failed: ' . $e->getMessage());
            }
        }

        // Boat type and RV type both land in the `category` column ShipA1 uses for them;
        // RV also has its own rv_type column, which is filled too so either reader works.
        $category = match ($type) {
            'Boat'  => $request->Boat_Category ?? null,
            'RV'    => $request->RV_Type ?? null,
            default => null,
        };

        try {
            $query = \App\ShipaQuery::create([
                // Customer
                'oname'            => $request->Custo_Name,
                'oemail'           => $request->Custo_Email,
                'ophone'           => $request->Custo_Phone,
                'main_ph'          => $request->Custo_Phone,

                // Origin
                'origincity'       => $origincity,
                'originstate'      => $originstate,
                'originzip'        => $originzip,
                'originzsc'        => $originzsc,
                'oterminal'        => 0,

                // Destination
                'destinationcity'  => $destinationcity,
                'destinationstate' => $destinationstate,
                'destinationzip'   => $destinationzip,
                'destinationzsc'   => $destinationzsc,
                'dterminal'        => 0,

                // Vehicle / load identity
                'ymk'              => $ymk,
                'year'             => $year,
                'make'             => $make,
                'model'            => $model,
                'vehicle_price'    => $vehiclePrice,
                'type'             => $type,
                'condition'        => $request->Carrier_Condition ?? null,
                'transport'        => $transport,
                'vehicle_opt'      => 'make',

                // Dimensions — feet and inches, as ShipA1 records them
                'length_ft'        => $lengthFt,
                'length_in'        => $lengthIn,
                'width_ft'         => $widthFt,
                'width_in'         => $widthIn,
                'height_ft'        => $heightFt,
                'height_in'        => $heightIn,
                'weight'           => $weight,

                // Freight details encoded in add_info (order taker reads on conversion)
                'add_info'         => $addInfo,

                // Website point 7 — heavy / boat / RV fields (ShipA1 parity).
                // All null for car-like and freight quotes.
                'trailer_type'         => $isDim ? ($request->Trailer_Type ?? null) : null,
                'load_method'          => $request->Load_Method ?? null,
                'unload_method'        => $request->Unload_Method ?? null,
                'load_type'            => $type === 'RV' ? ($request->Load_Type ?? null) : null,
                'category'             => $category,
                'rv_type'              => $type === 'RV' ? ($request->RV_Type ?? null) : null,
                'boat_on_trailer'      => $type === 'Boat' ? ($request->Boat_On_Trailer ?? null) : null,
                'available_at_auction' => $request->Available_At_Auction ?? null,
                'link'                 => $request->Available_At_Auction === 'Yes' ? ($request->Auction_Link ?? null) : null,
                'modification'         => $request->Modification ?? null,
                'modify_info'          => $request->Modification === 'Yes' ? ($request->Modify_Info ?? null) : null,
                'image'                => $image,

                // RoRo destination, kept in its own columns as well as destinationzsc
                'to_country'           => $isRoro ? $request->To_Country : null,
                'to_city'              => $isRoro ? $request->To_City : null,
                'roro'                 => $isRoro ? 'RORO SHIPMENT' : null,

                // Meta — car_type tells order taker which pipeline to use
                'paneltype'        => 4, // Batch 6: Hello website quotes land on Website Quote panel (4), not Panel 2
                'pstatus'          => 0,
                'source'           => 'Website',
                'car_type'         => $carType,
            ]);
        } catch (\Throwable $e) {
            Log::error('FrontendController submitQuoteRequest: ' . $e->getMessage());
        }

        // Client (26-Aug): website quotes must appear in the NEW folder / search IMMEDIATELY,
        // not only after an order taker is assigned. Create the unassigned order right away —
        // shipa1_queryAssignDirect already reassigns an existing linked order instead of creating
        // a duplicate, so the Assign OT flow keeps working unchanged.
        if (isset($query)) {
            try {
                $this->createUnassignedOrderFromQuery($query);
            } catch (\Throwable $e) {
                Log::error('FrontendController: unassigned order creation failed', ['query_id' => $query->id ?? null, 'error' => $e->getMessage()]);
            }
        }

        // Send confirmation emails — non-blocking, failure does not affect the redirect
        if (isset($query)) {
            try {
                Mail::to($request->Custo_Email)->send(new QuoteSubmissionMail($query, 'customer'));
                Log::info('FrontendController: customer confirmation email sent', ['email' => $request->Custo_Email]);
            } catch (\Throwable $e) {
                Log::warning('FrontendController: customer email failed', ['error' => $e->getMessage()]);
            }

            try {
                Mail::to('info@hellotransport.com')->send(new QuoteSubmissionMail($query, 'company'));
                Log::info('FrontendController: company notification email sent');
            } catch (\Throwable $e) {
                Log::warning('FrontendController: company email failed', ['error' => $e->getMessage()]);
            }
        }

        return redirect()->route('Frontend.qoute.confirmation');
    }

    /**
     * Create the UNASSIGNED order for a fresh website quote so it is visible in the New
     * folder / search / dashboard summary immediately. Field-copy mirrors the create branch
     * of DashboardController@shipa1_queryAssignDirect — that method finds this order via
     * $query->order_id and simply assigns it (its existing dedupe branch), never duplicating.
     * Contact-form leads (no vehicle/route) intentionally do NOT come through here.
     */
    private function createUnassignedOrderFromQuery(\App\ShipaQuery $query): void
    {
        if (!empty($query->order_id)) {
            return; // already linked
        }

        $order = new \App\AutoOrder();
        $order->order_taker_id  = null; // unassigned — “Assign To: Not Assigned” until Assign OT
        $order->oname           = $query->oname;
        $order->oemail          = $query->oemail;
        $order->ophone          = $query->ophone;
        $order->main_ph         = $query->main_ph;
        $order->ymk             = $query->ymk;
        $order->year            = $query->year;
        $order->make            = $query->make;
        $order->model           = $query->model;
        $order->vehicle_price   = $query->vehicle_price ?? '';
        $order->type            = $query->type;
        $order->vehicle_opt     = $query->vehicle_opt;
        $order->condition       = $query->condition;
        $order->car_type        = $query->car_type;
        $order->transport       = $query->transport;
        $order->originzsc       = $query->originzsc;
        $order->originzip       = $query->originzip;
        $order->originstate     = $query->originstate;
        $order->origincity      = $query->origincity;
        $order->destinationzsc  = $query->destinationzsc;
        $order->destinationzip  = $query->destinationzip;
        $order->destinationstate= $query->destinationstate;
        $order->destinationcity = $query->destinationcity;
        $order->add_info        = $query->add_info;
        $order->cname           = $query->cname;
        $order->cemail          = $query->cemail;
        $order->paneltype       = $query->paneltype;
        $order->ip_address      = $query->ip_address;
        $order->ip_details      = $query->ip_details ?? null;
        $order->ipcity          = $query->ipcity;
        $order->ipregion        = $query->ipregion;
        $order->ipcountry       = $query->ipcountry;
        $order->iploc           = $query->iploc ?? null;
        $order->ippostal        = $query->ippostal;
        $order->source          = $query->source ?? 'Website';
        $order->pstatus         = 0;

        // Heavy Equipment dimensions (car_type=2)
        $order->length_ft       = $query->length_ft ?? null;
        $order->width_ft        = $query->width_ft  ?? null;
        $order->height_ft       = $query->height_ft ?? null;
        $order->weight          = $query->weight    ?? null;

        $order->save();

        // Freight detail row (car_type=3 → Dryvan)
        if ((int) $query->car_type === 3) {
            $freight = new \App\order_freight();
            $freight->order_id         = $order->id;
            $freight->total_weight_lbs = $query->weight ?? null;
            if ($query->add_info) {
                foreach (explode(' | ', $query->add_info) as $part) {
                    if (strpos($part, 'Freight Class: ') === 0) {
                        $freight->frieght_class = trim(str_replace('Freight Class: ', '', $part));
                    } elseif (strpos($part, 'Commodity: ') === 0) {
                        $freight->shipment_prefences = trim(str_replace('Commodity: ', '', $part));
                        $freight->commodity_detail   = $freight->shipment_prefences;
                    } elseif (strpos($part, 'Shipping Mode: ') === 0) {
                        $freight->trailer_type = trim(str_replace('Shipping Mode: ', '', $part));
                    }
                }
            }
            $freight->save();
        }

        // Linked records (userId 1 = system, like other customer-originated flows)
        $payment = new \App\orderpayment();
        $payment->orderId = $order->id;
        $payment->save();

        $card = new \App\creditcard();
        $card->orderId = $order->id;
        $card->save();

        $rep = new \App\report();
        $rep->userId  = 1;
        $rep->orderId = $order->id;
        $rep->pstatus = 0;
        $rep->save();

        $single = new \App\singlereport();
        $single->userId  = 1;
        $single->orderId = $order->id;
        $single->pstatus = 0;
        $single->save();

        // Link the query to its order — this is what makes Assign OT reuse it.
        $query->order_id = $order->id;
        $query->save();
    }

    /**
     * Handle contact form submission — saves as ShipaQuery lead.
     */
    public function submitContactLead(Request $request)
    {
        $request->validate([
            'Lead_Name'  => 'required|string|max:100',
            'Lead_Email' => 'required|email|max:150',
            'Lead_Phone' => 'required|string|max:30',
        ]);

        try {
            \App\ShipaQuery::create([
                'oname'      => $request->Lead_Name,
                'oemail'     => $request->Lead_Email,
                'ophone'     => $request->Lead_Phone,
                'add_info'   => ($request->Lead_Subject ? $request->Lead_Subject . ': ' : '') . ($request->Lead_Message ?? ''),
                'paneltype'  => 4, // Batch 6: Hello website quotes land on Website Quote panel (4)
                'pstatus'    => 0,
                'source'     => 'Website',
            ]);
        } catch (\Throwable $e) {
            Log::error('FrontendController submitContactLead DB: ' . $e->getMessage());
        }

        try {
            $to      = env('CONTACT_LEAD_EMAIL', 'info@hellotransport.com');
            $name    = $request->Lead_Name;
            $email   = $request->Lead_Email;
            $phone   = $request->Lead_Phone;
            $subject = $request->Lead_Subject ?? 'Website Inquiry';
            $message = $request->Lead_Message ?? '';

            Mail::raw(
                "New contact/inquiry from hellotransport.com\n\n"
                . "Name:    {$name}\n"
                . "Email:   {$email}\n"
                . "Phone:   {$phone}\n"
                . "Subject: {$subject}\n\n"
                . "Message:\n{$message}",
                function ($msg) use ($to, $name, $email, $subject) {
                    $msg->to($to, 'Hello Transport')
                        ->replyTo($email, $name)
                        ->subject('Website Inquiry: ' . $subject);
                }
            );
        } catch (\Throwable $e) {
            Log::error('FrontendController submitContactLead mail: ' . $e->getMessage());
        }

        return back()->with('success', 'Your message has been sent! We will get back to you shortly.');
    }

    /**
     * Autocomplete endpoint for zip/city typeahead on the quote form.
     */
    public function autocomplete(Request $request)
    {
        $query = $request->query('query', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        try {
            $results = \App\zipcodes::where('city', 'like', $query . '%')
                ->orWhere('zipcode', 'like', $query . '%')
                ->limit(10)
                ->get(['city', 'state', 'zipcode'])
                ->map(fn($z) => "{$z->city}, {$z->state}, {$z->zipcode}")
                ->values()
                ->toArray();
        } catch (\Throwable $e) {
            $results = [];
        }

        return response()->json($results);
    }

    public function quoteConfirmation()
    {
        return view('main.frontend.quote-confirmation');
    }

    public function isItForMe()
    {
        return view('main.frontend.about-us');
    }

    public function carriers()
    {
        return view('main.frontend.about-us');
    }

    public function brokers()
    {
        return view('main.frontend.about-us');
    }

    public function shippers()
    {
        return view('main.frontend.about-us');
    }

    public function dispatchPage()
    {
        return view('main.frontend.about-us');
    }

    public function loadboard()
    {
        return view('main.frontend.about-us');
    }

    public function packages()
    {
        return view('main.frontend.about-us');
    }

    public function services()
    {
        return view('main.frontend.services');
    }

    /** W-02 — "Earn With Hello Transport", the agent recruitment page. */
    public function earn()
    {
        return view('main.frontend.earn');
    }

    public function serviceShow($slug)
    {
        $cats = config('hello_services.categories', []);
        $service = null;
        $categoryTitle = '';
        foreach ($cats as $cat) {
            if (isset($cat['services'][$slug])) {
                $service = $cat['services'][$slug];
                $categoryTitle = $cat['title'];
                break;
            }
        }
        if (!$service) {
            abort(404);
        }
        return view('main.frontend.service-show', compact('service', 'categoryTitle'));
    }
}
