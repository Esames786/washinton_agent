<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SellInvoice;
use Carbon\Carbon;

class SellInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $data = SellInvoice::where('invoice_number','LIKE','%'.$request->search.'%')
        ->orderBy('created_at','DESC')->paginate(20);
        
        if($request->ajax())
        {
            return view('main.sell_invoice.show',compact('data'));
        }
        
        return view('main.sell_invoice.index',compact('data'));
    }
    
    public function create()
    {
        return view('main.sell_invoice.create');
    }
    
    public function store(Request $request)
    {
        $ymkArr = [];
        if(isset($request->year))
        {
            array_push($ymkArr,$request->year);
        }
        if(isset($request->make))
        {
            array_push($ymkArr,$request->make);
        }
        if(isset($request->model))
        {
            array_push($ymkArr,$request->model);
        }
        $ymk = implode(' ',$ymkArr);
        $count = SellInvoice::count();
        $data = new SellInvoice;
        $data->invoice_number = 'A1-'.$count.rand(1000,9999);
        $data->date = $request->date;
        $data->inventory_id = $request->inventory_id;
        $data->sale_person = $request->sale_person;
        $data->cname = $request->cname;
        $data->cemail = $request->cemail;
        $data->cphone = $request->cphone;
        $data->year = $request->year;
        $data->make = $request->make;
        $data->model = $request->model;
        $data->ymk = $ymk;
        $data->vin_number = $request->vin_number;
        $data->sale_price = $request->sale_price;
        $data->total_amount = $request->total_amount;
        $data->balance = $request->balance;
        $data->additional = $request->additional;
        $data->save();
        
        return redirect('/sell_invoice')->with('msg','Sell Invoice has been created');
    }
    
    public function edit($id)
    {
        $data = SellInvoice::where('invoice_number',$id)->first();
        return view('main.sell_invoice.edit',compact('data'));
    }
    
    public function update(Request $request,$id)
    {
        $data = SellInvoice::where('invoice_number',$id)->first();
        $ymkArr = [];
        if(isset($request->year))
        {
            array_push($ymkArr,$request->year);
        }
        else
        {
            array_push($ymkArr,$data->year);
        }
        if(isset($request->make))
        {
            array_push($ymkArr,$request->make);
        }
        else
        {
            array_push($ymkArr,$data->make);
        }
        if(isset($request->model))
        {
            array_push($ymkArr,$request->model);
        }
        else
        {
            array_push($ymkArr,$data->model);
        }
        $ymk = implode(' ',$ymkArr);
        $data->date = $request->date;
        $data->inventory_id = $request->inventory_id;
        $data->sale_person = $request->sale_person;
        $data->cname = $request->cname;
        $data->cemail = $request->cemail;
        $data->cphone = $request->cphone;
        $data->year = $request->year;
        $data->make = $request->make;
        $data->model = $request->model;
        $data->ymk = $ymk;
        $data->vin_number = $request->vin_number;
        $data->sale_price = $request->sale_price;
        $data->total_amount = $request->total_amount;
        $data->balance = $request->balance;
        $data->additional = $request->additional;
        $data->save();
        
        return redirect('/sell_invoice')->with('msg','Sell Invoice has been updated');
    }
    
    public function invoice($id)
    {
        $data = SellInvoice::where('invoice_number',$id)->first();
        return view('main.sell_invoice.invoice',compact('data'));
    }
}
