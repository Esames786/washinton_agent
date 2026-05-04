<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AuthorizationForm;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Mail\AuthorizationFormMail;
use App\AutoOrder;
use App\AuthorizationFormImages;
use Mail;
use PDF;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Spatie\Browsershot\Browsershot;

class AuthorizationFormController extends Controller
{
    public function index(Request $request)
    {
        $cID = $request->input('cID', null);
        
        $cname = '';
        $email = '';
        $cphone = '';
        $invoiceNo = '';
        $invoiceAmount = '';
        $origin = '';
        $destination = '';
        $vehicle = '';
        
        if ($request->has('cID') && $request->cID != null)
        {
            $order = AutoOrder::find($request->cID);
            $cname = $order->oname;
            $email = $order->oemail;
            $cphone = $order->ophone;
            $invoiceNo = $order->invoiceNo;
            $invoiceAmount = $order->invoiceAmount;
            $origin = $order->originzsc;
            $destination = $order->destinationzsc;
            $vehicle = $order->ymk;
        }

        return view('main.authorization.form', compact('cID', 'email', 'cname', 'cphone', 'invoiceNo', 'invoiceAmount', 'origin', 'destination', 'vehicle'));
    }
    
    public function email(Request $request)
    {
        $cID = $request->input('id', null);
        $cname = $request->input('cname', null);
        $cphone = $request->input('cphone', null);
        $invoiceAmount = $request->input('invoiceAmount', null);
        $origin = $request->input('origin', null);
        $destination = $request->input('destination', null);
        $vehicle = $request->input('vehicle', null);
    
        $email = $request->input('email');
        if (!$email) {
            return response()->json(['error' => 'Email field is required'], 400);
        }
        
        $invoiceNo = $this->generateUniqueInvoice($cID);
        // dd($invoiceAmount, $invoiceNo);

        $order = AutoOrder::find($cID);
        $order->invoiceNo = $invoiceNo;
        $order->invoiceAmount = $invoiceAmount;
        $order->save();
        
    
        Mail::to($email)->send(new AuthorizationFormMail($cID, $cname, $email, $cphone, $invoiceNo, $invoiceAmount, $origin, $destination, $vehicle));
    
        return back();
    }
    
    private function generateUniqueInvoice($cID)
    {
        $letters = $this->generateRandomLetters(3);
        $invoiceNumber = $letters . $cID;
    
        return $invoiceNumber;
    }
    
    private function generateRandomLetters($length)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
    
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
    
        return $randomString;
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => ['required', 'date'],
            'company_name' => ['required', 'string'],
            'email' => ['nullable', 'string'],
            'card_holders' => ['required', 'string'],
            'billing_address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'state' => ['required', 'string'],
            'zip_code' => ['required', 'string'],
            'country' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'card' => ['nullable', 'string'],
            'card_number' => ['nullable', 'string'],
            'expdate' => ['nullable', 'date_format:Y-m'],
            'Code' => ['nullable', 'string'],
            'issuing_bank' => ['nullable', 'string'],
            'bank_approval' => ['nullable', 'string'],
            'card_issuer' => ['nullable', 'string'],
            'invoice' => ['nullable', 'string'],
            'invoice_amount' => ['nullable', 'string'],
            'signatureData' => ['nullable', 'string'],
        ]);
    
    
        try {
            $authorizationForm = new AuthorizationForm;
    
            $authorizationForm->fill($validatedData);
    
            $authorizationForm->save();
    
            if ($request->hasFile('multiImage') && $request->file('multiImage') != null) {
                $images = $request->file('multiImage');
    
                foreach ($images as $image) {
                    $imageName = time() . '_' . $image->getClientOriginalName();
    
                    $image->storeAs('authorization_form_images', $imageName, 'public');
    
                    $formImage = new AuthorizationFormImages;
    
                    $formImage->form_id = $authorizationForm->id;
                    $formImage->image = $imageName;
                    $formImage->status = 1;
    
                    $formImage->save();
                }
            }
    
            return redirect()->back()->with('success', 'Form submitted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to store form data. ' . $e->getMessage());
        }
    }
    
    public function allForms()
    {
        if(Auth::check())
        {
            $form = AuthorizationForm::orderBy('id', 'DESC')->get();
            return view('main.authorization.allForms',compact('form'));
        }
        else
        {
            return redirect('/login');
        }
    }
    
    public function show($id)
    {
        if (Auth::check()) {
            $form = AuthorizationForm::with('images')->find($id);
    
            if (!$form) {
                return response()->json(['error' => 'Authorization Form not found'], 404);
            }
    
            $orderId = preg_replace('/[^0-9]/', '', $form->invoice);
            $order = AutoOrder::find($orderId);
    
            if (!$order) {
                return response()->json(['error' => 'AutoOrder not found'], 404);
            }
    
    
            $vehicle = $order->ymk ?? 'N/A';
            $origin = "{$order->origincity}-{$order->originstate}-{$order->originzip}";
            $destination = "{$order->destinationcity}-{$order->destinationstate}-{$order->destinationzip}";
    
            return view('main.authorization.show', compact('form', 'vehicle', 'origin', 'destination'));
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
    
    public function downloadPDF()
    {
        dd($id);
        $html = view('your.blade.view')->render();
        $tempHtmlFile = tempnam(storage_path('app/public'), 'temp-html-');

        File::put($tempHtmlFile, $html);

        $imagePath = storage_path('app/public/temp-image.png');
        Browsershot::htmlFile($tempHtmlFile)->save($imagePath);

        unlink($tempHtmlFile);

        return Response::download($imagePath, 'downloaded-image.png')->deleteFileAfterSend(true);
    }

}