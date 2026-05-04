<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\OrderTakerQouteAccess;

class ManagerGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $manager = User::withCount('manager_ot')->whereHas('userRole',function($q){
            $q->where('name','Manager');
        })
        ->where('deleted',0)
        ->where(function($q) use ($request){
            $q->where('slug','LIKE','%'.$request->search.'%')
            ->orWhere('phone','LIKE','%'.$request->search.'%')
            ->orWhere('email','LIKE','%'.$request->search.'%');
        })
        ->orderBy('manager_ot_count','DESC')->paginate(10);
        
        if($request->ajax())
        {
            return view('main.manager-group.search',compact('manager'));
        }
        
        return view('main.manager-group.index',compact('manager'));
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
        if($request->user_id && $request->from_date && $request->to_date)
        {
            $data = OrderTakerQouteAccess::where('ot_ids',$request->user_id)->first();
            $data->calling_status = 1;
            $data->from_date = $request->from_date;
            $data->to_date = $request->to_date;
            $data->save();
            
            return back()->with('msg','Calling Button Active Successfully!');
        }
        else
        {
            return back()->with('err','Something went wrong!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $user = OrderTakerQouteAccess::with('user.userRole')
                ->where('manager_id',$id)
                ->whereHas('user',function($q) use ($request){
                    $q->where('slug','LIKE','%'.$request->search.'%')
                    ->orWhere('phone','LIKE','%'.$request->search.'%')
                    ->orWhere('email','LIKE','%'.$request->search.'%');
                })
                ->orderBy('created_at','DESC')->paginate(10);
        
        $manager = User::find($id);
        
        if($request->ajax())
        {
            return view('main.manager-group.search2',compact('user'));
        }
        
        return view('main.manager-group.show',compact('user','manager'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        if($request->assign_daily_qoute > 0)
        {
            $user = User::find($id);
            $daily_qoute = $user->assign_daily_qoute;
            $user->assign_daily_qoute = $request->assign_daily_qoute;
            $user->save();
            
            $daily = DailyQoute::where('user_id',$id)->whereDate('date',date('Y-m-d'))->first();
            if(isset($daily->id))
            {
                if($daily_qoute > $request->assign_daily_qoute)
                {
                    $daily->total_qoute = ($daily_qoute - $request->assign_daily_qoute) + $daily->total_qoute;
                }
                else
                {
                    $daily->total_qoute = ($request->assign_daily_qoute - $daily_qoute) + $daily->total_qoute;
                }
                $daily->save();
            }
        }
        
        return back()->with('msg',$user->slug.' Daily Qoute has been changed.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
