<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AutoOrder;
use App\PortTrackHistory;
use Auth;

class PortTrackingController extends Controller
{
    public function index()
    {
        $data = AutoOrder::select('id','ophone','dshipment_no', 'vin_num', 'port_line', 'pstatus')->where('dterminal', 7)
                ->with('portTrackHistory')
                ->whereIn('pstatus', [13, 12])
                ->orderBy('id', 'DESC')
                // ->take(100)
                ->get();
        // dd($data->toArray());
        return view('main.port_tracking.index',compact('data'));
    }
    
    public function add_history(Request $request)
    {
        if ($request->has('comment'))
        {
            $history = new PortTrackHistory;
            $history->user_id = Auth::user()->id;
            $history->order_id = $request->order_id;
            $history->history = $request->comment;
            $history->status = $request->status;
            $history->save();
        }
        
        $all = PortTrackHistory::with('user')->where('order_id', $request->order_id)->get();
        
        return $all;
        
    }
    
    public function getStatusPort(Request $request)
    {
        $status = $request->status;
        $dock = $request->dock;
        // $data = AutoOrder::select('id','ophone','dshipment_no', 'vin_num', 'port_line', 'pstatus')->where('dterminal', 7)
        //         ->where('dockRec_createdBy', $dockRec_createdBy)
        //         ->with('portTrackHistory')
        //         ->whereIn('pstatus', [13, 12])
        //         ->orderBy('id', 'DESC')
        //         ->when($status, function ($query, $status) {
        //             $query->whereHas('portTrackHistory', function ($q) use ($status) {
        //                 $q->where('status', $status);
        //             });
        //         })->get();
        $data = AutoOrder::select('id', 'ophone', 'dshipment_no', 'vin_num', 'port_line', 'pstatus')
            ->where('dterminal', 7)
            ->with('portTrackHistory')
            ->whereIn('pstatus', [13, 12])
            ->orderBy('id', 'DESC')
            ->when($status, function ($query, $status) {
                $query->whereHas('portTrackHistory', function ($q) use ($status) {
                    $q->where('status', $status);
                });
            })
            ->when($dock, function ($query, $dock) {
                $query->where('port_line', $dock);
            })
            ->get();
        
        $all = PortTrackHistory::with('user')->where('order_id', $request->order_id)->get();
        
        // dd($request->toArray(), $data->toArray());
        
        return view('main.port_tracking.load',compact('data'));
        
    }
}
