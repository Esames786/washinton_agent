<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PortPrice;
use Auth;

class PortPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = PortPrice::orderBy('id','DESC')->paginate(25);
        return view('main.port_price.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('main.port_price.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = new PortPrice;
        $data->user_id = Auth::user()->id;
        $data->long_data = $request->long_data;
        $data->save();
        
        return redirect('/port_price')->with('flash_message','Port Price has been added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = PortPrice::find($id);
        return view('main.port_price.show',compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = PortPrice::find($id);
        return view('main.port_price.edit',compact('data'));
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
        $data = PortPrice::find($id);
        $data->user_id = Auth::user()->id;
        $data->long_data = $request->long_data;
        $data->save();
        
        return redirect('/port_price')->with('flash_message','Port Price has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PortPrice::destroy($id);
        
        return redirect('/port_price')->with('flash_message','Port Price has been deleted successfully');
    }
}
