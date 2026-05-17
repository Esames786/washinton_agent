<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FrontendController extends Controller
{
    public function home()
    {
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
        $allowed = ['Car', 'Heavy Equipment', 'Dryvan', 'Motorcycle', 'ATV/UTV', 'Golf Cart'];
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
        $request->validate([
            'Custo_Name'   => 'required|string|max:100',
            'Custo_Phone'  => 'required|string|max:30',
            'Custo_Email'  => 'required|email|max:150',
            'From_ZipCode' => 'required|string|max:100',
            'To_ZipCode'   => 'required|string|max:100',
        ]);

        $type = $request->Select_Vehicle ?? 'Car';

        // Parse origin / destination — "City, State, Zip" or bare zip
        $originParts      = array_map('trim', explode(',', $request->From_ZipCode));
        $destinationParts = array_map('trim', explode(',', $request->To_ZipCode));

        $origincity       = $originParts[0] ?? '';
        $originstate      = $originParts[1] ?? '';
        $originzip        = $originParts[2] ?? $originParts[0] ?? '';
        $originzsc        = trim("{$origincity},{$originstate},{$originzip}", ',');

        $destinationcity  = $destinationParts[0] ?? '';
        $destinationstate = $destinationParts[1] ?? '';
        $destinationzip   = $destinationParts[2] ?? $destinationParts[0] ?? '';
        $destinationzsc   = trim("{$destinationcity},{$destinationstate},{$destinationzip}", ',');

        // Vehicle identity — varies by type
        $year  = null;
        $make  = null;
        $model = null;

        if ($type === 'Heavy Equipment') {
            // Single "Year Make Model" field
            $make = $request->Year_Make_Model ?? null;
        } elseif (in_array($type, ['Car', 'Motorcycle', 'ATV/UTV', 'Golf Cart'])) {
            $year  = $request->Car_Year  ?? null;
            $make  = $request->Car_Make  ?? null;
            $model = $request->Car_Model ?? null;
        }
        $ymk = trim("{$year} {$make} {$model}");

        // Transport / shipping mode
        // Cars/Motorcycles/ATV/Golf Cart → Carrier_Type (Open/Enclosed/Drive Away)
        // Heavy Equipment & Dryvan      → Shipping_Mode (FTL/LTL)
        $transport = in_array($type, ['Heavy Equipment', 'Dryvan'])
            ? ($request->Shipping_Mode ?? null)
            : ($request->Carrier_Type  ?? null);

        // Weight — dedicated field per type
        $weight = null;
        if ($type === 'Heavy Equipment') {
            $weight = $request->Vehicle_Weight ?? null;
        } elseif ($type === 'Dryvan') {
            $weight = $request->Freight_Weight ?? null;
        }

        // Dimensions — Heavy Equipment only
        $lengthFt = $type === 'Heavy Equipment' ? ($request->Vehicle_Length ?? null) : null;
        $widthFt  = $type === 'Heavy Equipment' ? ($request->Vehicle_Width  ?? null) : null;
        $heightFt = $type === 'Heavy Equipment' ? ($request->Vehicle_Height ?? null) : null;

        // car_type drives which table the order taker moves the lead into:
        //   1 = Car / Motorcycle / ATV / Golf Cart  → order table (standard)
        //   2 = Heavy Equipment                     → order table (heavy fields)
        //   3 = Dryvan / Freight                    → order table + order_freight table
        $carType = match($type) {
            'Heavy Equipment' => 2,
            'Dryvan'          => 3,
            default           => 1,
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

        try {
            \App\ShipaQuery::create([
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
                'type'             => $type,
                'condition'        => $request->Carrier_Condition ?? null,
                'transport'        => $transport,
                'vehicle_opt'      => 'make',

                // Dimensions — Heavy Equipment
                'length_ft'        => $lengthFt,
                'width_ft'         => $widthFt,
                'height_ft'        => $heightFt,
                'weight'           => $weight,

                // Freight details encoded in add_info (order taker reads on conversion)
                'add_info'         => $addInfo,

                // Meta — car_type tells order taker which pipeline to use
                'paneltype'        => 2,
                'pstatus'          => 0,
                'source'           => 'Website',
                'car_type'         => $carType,
            ]);
        } catch (\Throwable $e) {
            Log::error('FrontendController submitQuoteRequest: ' . $e->getMessage());
        }

        return redirect()->route('Frontend.qoute.confirmation');
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
                'paneltype'  => 2,
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
