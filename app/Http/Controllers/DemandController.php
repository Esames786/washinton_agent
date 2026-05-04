<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DemardVehicle;
use Auth;
use App\Mail\SendDemandMail;
use Mail;

class DemandController extends Controller
{
    public function index(Request $request)
    {
        $data = DemardVehicle::where('status','>',0)
        ->where(function ($q) use ($request){
            $q->where('order_id','LIKE','%'.$request->search.'%')
            ->orWhere('email','LIKE','%'.$request->search.'%');
        })
        ->orderBy('created_at','DESC')->paginate(20);
        
        if($request->ajax())
        {
            return view('main.demand.search',compact('data'));
        }
        
        return view('main.demand.index',compact('data'));
    }
    
    public function add(Request $request)
    {
        $data = new DemardVehicle;
        $data->user_id = Auth::user()->id;
        $data->email = $request->email;
        $data->order_id = $request->order_id;
        $data->save();
        
        return $data;
    }
    
    public function store(Request $request,$id)
    {
        $data = DemardVehicle::find($id);
        $data->status = 1;
        $data->save();
        
        Mail::to($request->email)->send(new SendDemandMail($request->link));
        
        return back()->with('msg','Link has been sent to the customer.');
    }
    
    public function update(Request $request,$id)
    {
        $data = DemardVehicle::find($id);
        $data->from_year = $request->from_year;
        $data->to_year = $request->to_year;
        $data->make = $request->make;
        $data->model = $request->model;
        $data->trim_level = $request->trim_level;
        $data->mileage = $request->mileage;
        $data->car_color = $request->car_color;
        $data->interior_color = $request->interior_color;
        $data->condition = $request->condition;
        $data->title = $request->title;
        $data->body_condition = $request->body_condition;
        $data->from_budget = $request->from_budget;
        $data->to_budget = $request->to_budget;
        $data->how_much_days = $request->how_much_days;
        $data->requirement = $request->requirement;
        $data->payment_method = $request->payment_method;
        $data->status = 2;
        $data->save();
        
        return back()->with('msg','Your demand of vehicle form has been submitted.');
    }
    
    public function demand_order($id,$user_id)
    {
        $iid = base64_decode($id);
        $data = DemardVehicle::find($iid);
        return view('main.demand.form',compact('data'));
    }
    
}
