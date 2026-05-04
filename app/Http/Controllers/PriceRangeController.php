<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PriceRange;
use Auth;
use App\AutoOrder;
use App\OfferPrice;
use App\Mail\GivenAddonPriceMail;
use Mail;

class PriceRangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = PriceRange::paginate(25);
        return view('main.price_range.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('main.price_range.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = new PriceRange;
        $data->min_price = $request->min_price;
        $data->max_price = $request->max_price;
        $data->addon_price = $request->addon_price;
        $data->save();

        return redirect()->route('get.all.priceRange')->with('flash_message', 'Price Range has been added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = PriceRange::find($id);
        return view('main.price_range.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = PriceRange::find($id);
        return view('main.price_range.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $data = PriceRange::find($id);
        $data->min_price = $request->min_price;
        $data->max_price = $request->max_price;
        $data->addon_price = $request->addon_price;
        $data->save();

        return redirect()->route('get.all.priceRange')->with('flash_message', 'Price Range has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PriceRange::destroy($id);

        return redirect()->route('get.all.priceRange')->with('flash_message', 'Price Range has been deleted successfully');
    }

    public function givenPrice(Request $request)
    {
        $order = AutoOrder::with('freight')->find($request->order_id);

        if (!$order) {
            return back()->with('error', 'Order not found');
        }

        // $data = PriceRange::where('min_price', '<=', $request->given_price)
        //     ->where('max_price', '>=', $request->given_price)
        //     ->first();
        $data = OfferPrice::where('from_price', '<=', $request->given_price)
            ->where('to_price', '>=', $request->given_price)
            ->first();

        if (!$data) {
            return back()->with('flash_message', 'Price range not found');
        }

        // $price = $data->addon_price + $request->given_price;
        $price = $data->commission_price + $request->given_price;

        $order->given_price = $request->given_price;
        // $order->given_price2 = $request->given_price2;
        $order->driver_price = $request->given_price;
        $order->start_price = $price;
        $order->payment = $price;
        $order->save();

        $userId = Auth::user()->id;
        $orderId = $order->id;

        $encryptvuserid = $this->encodeData($userId);
        $encryptvoderid = $this->encodeData($orderId);
        $linkv = url('/email_order/' . $encryptvoderid . '/' . $encryptvuserid);

        //     Mail::to('abst99856@gmail.com')->send(new GivenAddonPriceMail($price, $order, $linkv));
        // Mail::to('autonewexport@gmail.com')->send(new GivenAddonPriceMail($price, $order, $linkv));

        // dd($order->toArray());

        try {
            Mail::to(['abst99856@gmail.com', $order->oemail, 'allenmanager@shipa1.com'])->send(new GivenAddonPriceMail($price, $order, $linkv));
            // Mail::to($order->oemail)->send(new GivenAddonPriceMail($price, $order, $linkv));
        } catch (\Exception $e) {
            return back()->with('error', 'Error sending email');
        }

        return back()->with('flash_message', 'Price has been added successfully');
    }

    private function encodeData($data)
    {
        if (is_array($data)) {
            $str = '';
            foreach ($data as $char) {
                $str .= chr($char);
            }
        } else {
            $str = (string) $data;
        }

        return base64_encode($str);
    }
}
