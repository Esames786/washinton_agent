<?php

namespace App\Http\Controllers\phone_quote\Ip;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Ip;

class IpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Ip::orderBy('created_at','DESC')->paginate(20);
        return view('main.ip.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = new Ip;
        $data->ip_address = $request->ip_address;
        $data->save();
        
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $ip = Ip::find($request->id);
        return response()->json([
            'data'=>$ip,
            'status'=>true,
            'status_code'=>200
        ]);
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
        $data = Ip::find($id);
        $data->ip_address = $request->ip_address;
        $data->save();
        
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Ip::find($id);
        if($data->status == 'Active')
        {
            $data->status = 'Disable';
        }
        else
        {
            $data->status = 'Active';
        }
        $data->save();
        
        return back();
    }
}
