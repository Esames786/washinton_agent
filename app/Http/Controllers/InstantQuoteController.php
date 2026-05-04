<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InstantQuoteController extends Controller
{
    public function submitInstantQuote(Request $request)
    {
        dump($request->all());
        // Process the form data and save it in the database or perform other actions
        // You can access form fields using $request->input('field_name')

        // Example: Store in the database
        // Quote::create($request->all());

        return response()->json(['message' => 'Form submitted successfully in the other project'], 200);
    }
}