<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MessageChat;
use App\Group;
use App\User;
use App\AutoOrder;
use Illuminate\Support\Facades\Auth;

class MessageChatController extends Controller
{
    public function index()
    {
        $users = MessageChat::with('user', 'order')->groupBy('user_id')->get();
        $orders = MessageChat::with('user', 'order')->groupBy('order_id')->get();
        $messageChats = MessageChat::with('user', 'order')->get();
        // dd($messageChats->toArray(), $users->toArray());
        return view('messagechats.index', compact('messageChats', 'users', 'orders'));
    }

    public function create()
    {
        $orderIds = AutoOrder::take(50)->get();
        return view('messagechats.create', compact('orderIds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'message' => 'required',
        ]);
    
        $data = $request->all();
        $data['user_id'] = Auth::id();
    
        // dd($data);
        MessageChat::create($data);
    
        return redirect()->route('messagechats.index')->with('success', 'MessageChat created successfully');
    }

    public function show($id)
    {
        $messageChat = MessageChat::findOrFail($id);
        return view('messagechats.show', compact('messageChat'));
    }

    public function edit($id)
    {
        $orderIds = AutoOrder::take(50)->get();
        $messageChat = MessageChat::findOrFail($id);
        return view('messagechats.edit', compact('messageChat', 'orderIds'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'order_id' => 'required',
            'message' => 'required',
            'status' => 'required',
        ]);

        $messageChat = MessageChat::findOrFail($id);
        $messageChat->update($request->all());

        return redirect()->route('messagechats.index')->with('success', 'MessageChat updated successfully');
    }

    public function destroy($id)
    {
        $messageChat = MessageChat::findOrFail($id);
        $messageChat->delete();
        return redirect()->route('messagechats.index')->with('success', 'MessageChat deleted successfully');
    }

    public function getOrderId($id)
    {
        $order = AutoOrder::findOrFail($id);
        $name = $order->oname;
        return response()->json(['name' => $name]);
    }

    public function filterMessageChat(Request $request)
    {
        $messageChats = MessageChat::with('user', 'order');
        if($request->has('user'))
        {
            $messageChats = $messageChats->where('user_id', 'like', '%'.$request->user.'%');
        }
        if($request->has('order'))
        {
            $messageChats = $messageChats->where('order_id', 'like', '%'.$request->order.'%');
        }
        if ($request->has('startDate') && $request->has('endDate')) {
            $from = date('Y-m-d 00:00:00', strtotime($request->startDate));
            $to = date('Y-m-d 23:59:59', strtotime($request->endDate));

            if (!empty($from) && !empty($to)) {
                $messageChats = $messageChats->whereBetween('created_at', [$from, $to]);
            }
        }
        
        $messageChats = $messageChats->get();
        return view('messagechats.table', compact('messageChats'));
    }

    public function getAllMsgChats(Request $request)
    {
        $order = AutoOrder::find($request->order_id);
        $phone = $order->ophone;
        $messageChats = MessageChat::with('user', 'order')->whereHas('order',function ($q) use ($phone) {
               $q->where('ophone', $phone);
            })
            ->take(10)->get();
            
        if ($messageChats) 
        {
            return $messageChats;
        }
    }
}
