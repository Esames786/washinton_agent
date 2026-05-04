<?php

namespace App\Http\Controllers\phone_quote\global_chat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Group;
use App\GroupChat;
use App\chat;
use App\SiteSetting;
use App\role;
use App\zipcodes;
use Session;
use Redirect;
use Hash;
use Mail;
use Auth;
use DB;
use Carbon\Carbon;
use Vinkla\Hashids\Facades\Hashids;

class ChatController extends Controller
{
    public function index()
    {
        $chatID = '';
        $chatText = 'Chat';
        $id = auth()->id();
        // $data = User::where('id', '!=', auth()->id());
        $chatUsers = Chat::select('fromuserId', 'touserId', 'created_at')
            ->where(function ($query) {
                $query->where('fromuserId', auth()->id())
                    ->orWhere('touserId', auth()->id());
            })
            ->orderBy('created_at', 'DESC')
            ->get();

        $userIds = $chatUsers->flatMap(function ($chat) {
            return [$chat->fromuserId, $chat->touserId];
        })->unique()->filter(function ($userId) {
            return $userId != auth()->id();
        });

        $userIdsArray = $userIds->values()->toArray();

        $users = User::whereIn('id', $userIdsArray)->where('deleted', 0)->get()->keyBy('id');

        $orderedUsers = collect($userIdsArray)->map(function ($userId) use ($users) {
            return $users->has($userId) ? $users[$userId] : null;
        })->filter(function ($user) {
            return $user !== null;
        });

        $data1 = $orderedUsers;

        $data2 = User::whereNotIn('id', $userIdsArray)->where('deleted', 0)->get();

        $data = $data1->merge($data2);

        // dd('sad', $data->toArray(), $userIds);


        // if (Auth::user()->role != 1) {
        //     $data = $data->where('role', Auth::user()->role)->orWhere('role', 1);
        // }

        $group = Group::select(['id', 'name', 'logo'])
            ->with(['chatOne:message,group_id,user_id,created_at,type', 'chatOne.user:id,slug,name,last_name'])
            ->whereHas('users', function ($q) use ($id) {
                $q->where('user_id', $id)->where('status', 0);
            })->where('status', 1)->get();

        $carbon = Carbon::today()->subDays(31);
        if ($group) {
            foreach ($group as $key => $value) {
                $group[$key]->count = 0;
                $count = GroupChat::where('group_id', $value->id)->whereDate('created_at', '>=', $carbon)
                    ->where(function ($q) use ($id) {
                        $q->whereRaw('NOT FIND_IN_SET(?, chat_view_users_id)', [$id])
                            ->orWhere('chat_view_users_id', NULL);
                    })
                    ->count();

                $group[$key]->count = $count;
            }
        }

        return view('main.phone_quote.global_chat.index', compact('data', 'group', 'chatText', 'chatID'));

    }

    public function index3($chatID)
    {
        $chatText = 'Chat';
        $id = auth()->id();
        // $data = User::where('id', '!=', auth()->id());

        // if (Auth::user()->role != 1) {
        //     $data = $data->where('role', Auth::user()->role)->orWhere('role', 1);
        // }

        // $data = $data->orderBy('is_login', 'DESC')->orderBy('updated_at', 'DESC')->get();

        $chatUsers = Chat::select('fromuserId', 'touserId', 'created_at')
            ->where(function ($query) {
                $query->where('fromuserId', auth()->id())
                    ->orWhere('touserId', auth()->id());
            })
            ->orderBy('created_at', 'DESC')
            ->get();

        $userIds = $chatUsers->flatMap(function ($chat) {
            return [$chat->fromuserId, $chat->touserId];
        })->unique()->filter(function ($userId) {
            return $userId != auth()->id();
        });

        $userIdsArray = $userIds->values()->toArray();

        $users = User::whereIn('id', $userIdsArray)->where('deleted', 0)->get()->keyBy('id');

        $orderedUsers = collect($userIdsArray)->map(function ($userId) use ($users) {
            return $users->has($userId) ? $users[$userId] : null;
        })->filter(function ($user) {
            return $user !== null;
        });

        $data1 = $orderedUsers;

        $data2 = User::whereNotIn('id', $userIdsArray)->where('deleted', 0)->get();

        $data = $data1->merge($data2);

        $group = Group::select(['id', 'name', 'logo'])
            ->with(['chatOne:message,group_id,user_id,created_at,type', 'chatOne.user:id,slug,name,last_name'])
            ->whereHas('users', function ($q) use ($id) {
                $q->where('user_id', $id)->where('status', 0);
            })->where('status', 1)->get();

        $carbon = Carbon::today()->subDays(31);
        if ($group) {
            foreach ($group as $key => $value) {
                $group[$key]->count = 0;
                $count = GroupChat::where('group_id', $value->id)->whereDate('created_at', '>=', $carbon)
                    ->where(function ($q) use ($id) {
                        $q->whereRaw('NOT FIND_IN_SET(?, chat_view_users_id)', [$id])
                            ->orWhere('chat_view_users_id', NULL);
                    })
                    ->count();

                $group[$key]->count = $count;
            }
        }

        return view('main.phone_quote.global_chat.index', compact('data', 'group', 'chatText', 'chatID'));

    }

    public function index22(Request $request)
    {
        $chatText = $request->chatText;
        $id = auth()->id();
        // $data = User::where('id', '!=', auth()->id());
        $chatUsers = Chat::select('fromuserId', 'touserId', 'created_at')
            ->where(function ($query) {
                $query->where('fromuserId', auth()->id())
                    ->orWhere('touserId', auth()->id());
            })
            ->orderBy('created_at', 'DESC')
            ->get();

        $userIds = $chatUsers->flatMap(function ($chat) {
            return [$chat->fromuserId, $chat->touserId];
        })->unique()->filter(function ($userId) {
            return $userId != auth()->id();
        });

        $userIdsArray = $userIds->values()->toArray();

        $users = User::whereIn('id', $userIdsArray)->where('deleted', 0)->get()->keyBy('id');

        $orderedUsers = collect($userIdsArray)->map(function ($userId) use ($users) {
            return $users->has($userId) ? $users[$userId] : null;
        })->filter(function ($user) {
            return $user !== null;
        });

        $data1 = $orderedUsers;

        $data2 = User::whereNotIn('id', $userIdsArray)->where('deleted', 0)->get();

        $data = $data1->merge($data2);

        // if (Auth::user()->role != 1) {
        //     $data = $data->where('role', Auth::user()->role)->orWhere('role', 1);
        // }

        // $data = $data->orderBy('is_login', 'DESC')->orderBy('updated_at', 'DESC')->get();

        $group = Group::select(['id', 'name', 'logo'])
            ->with(['chatOne:message,group_id,user_id,created_at,type', 'chatOne.user:id,slug,name,last_name'])
            ->whereHas('users', function ($q) use ($id) {
                $q->where('user_id', $id)->where('status', 0);
            })->where('status', 1)->get();

        $carbon = Carbon::today()->subDays(31);
        if ($group) {
            foreach ($group as $key => $value) {
                $group[$key]->count = 0;
                $count = GroupChat::where('group_id', $value->id)->whereDate('created_at', '>=', $carbon)
                    ->where(function ($q) use ($id) {
                        $q->whereRaw('NOT FIND_IN_SET(?, chat_view_users_id)', [$id])
                            ->orWhere('chat_view_users_id', NULL);
                    })
                    ->count();

                $group[$key]->count = $count;
            }
        }

        return view('main.phone_quote.global_chat.chat-list', compact('data', 'group', 'chatText'));

    }

    public function readMsgs(Request $request)
    {
        Chat::where(function ($q) use ($request) {
            $q->where('fromuserId', $request->touserId)
                ->where('touserId', Auth::id());
        })->update(['chat_view2' => 1]);

        return "Read";
    }

    public function get_chat(Request $request)
    {
        //        $data = chat::where([
//            ['fromuserId', '=', $request->touserId]])->orwhere([
//            ['touserId', '=', $request->touserId]])->join('users', 'chats.fromuserId', '=', 'users.id')
//            ->select('chats.*', 'users.name as name')
//            ->orderby('chats.created_at')
//            ->get();

        // $data = chat::where([
        //     ['fromuserId', '=', $request->touserId]])->orwhere([
        //     ['touserId', '=', $request->touserId]])
        //     ->where('chats.created_at', '>=', $carbon)
        //     ->join('user', 'chats.fromuserId', '=', 'user.id')
        //     ->select('chats.*', 'user.name as name')
        //     ->orderby('chats.created_at')
        //     ->get();

        $carbon = Carbon::today()->subDays(2);


        $data = chat::where(function ($query) {
            $query->where('status', 1)
                ->orWhere(function ($subQuery) {
                    $subQuery->where('fromuserId', Auth::id())
                        ->whereIn('status', [0, 1]);
                });
        })->where(function ($q) use ($request) {
            $q->where(function ($q1) use ($request) {
                $q1->where('touserId', $request->touserId)
                    ->where('fromuserId', Auth::id());
            })
                ->orWhere(function ($q1) use ($request) {
                    $q1->where('fromuserId', $request->touserId)
                        ->where('touserId', Auth::id());
                });
        })->whereDate('created_at', '>=', $carbon)
            ->orderBy('created_at', 'ASC')
            ->get();

        $user = User::find($request->touserId);

        // echo "<pre>";
        // print_r($data);
        // exit();


        return view('main.phone_quote.global_chat.show_chat', compact('data', 'user'));
    }


    public function get_chat_runtime(Request $request)
    {
        $carbon = Carbon::today()->subDays(2);


        $data22 = chat::where(function ($query) {
            $query->where('status', 1)
                ->orWhere(function ($subQuery) {
                    $subQuery->where('fromuserId', Auth::id())
                        ->whereIn('status', [0, 1]);
                });
        })->where(function ($q) use ($request) {
            $q->where(function ($q1) use ($request) {
                $q1->where('touserId', $request->touserId)
                    ->where('fromuserId', Auth::id());
            })
                ->orWhere(function ($q1) use ($request) {
                    $q1->where('fromuserId', $request->touserId)
                        ->where('touserId', Auth::id());
                });
        })->whereDate('created_at', '>=', $carbon)
            ->where('chat_view2', '=', 1)
            ->orderBy('created_at', 'ASC')
            ->get();

        if (count($data22) > 0) {
            foreach ($data22 as $key => $val) {
                $chat = chat::find($val->id);
                $chat->chat_view2 = 1;
                $chat->save();
            }
            $data = chat::where(function ($query) {
                $query->where('status', 1)
                    ->orWhere(function ($subQuery) {
                        $subQuery->where('fromuserId', Auth::id())
                            ->whereIn('status', [0, 1]);
                    });
            })->where(function ($q) use ($request) {
                $q->where(function ($q1) use ($request) {
                    $q1->where('touserId', $request->touserId)
                        ->where('fromuserId', Auth::id());
                })
                    ->orWhere(function ($q1) use ($request) {
                        $q1->where('fromuserId', $request->touserId)
                            ->where('touserId', Auth::id());
                    });
            })->whereDate('created_at', '>=', $carbon)
                ->orderBy('created_at', 'ASC')
                ->get();

            $user = User::find($request->touserId);

            // echo "<pre>";
            // print_r($data);
            // exit();


            return view('main.phone_quote.global_chat.show_chat', compact('data', 'user'));
        }

    }

    public function get_chat2(Request $request)
    {
        $uid = auth()->id();
        $data = chat::where('touserId', '=', auth()->id())->where('chat_view2', '=', 0)->orderby('created_at', 'desc')->get();
        return view('main.phone_quote.global_chat.show_chat', compact('data'));

    }


    public function view_chat(Request $request)
    {
        $uid = auth()->id();
        $data = chat::where('touserId', '=', auth()->id())->where('chat_view2', '=', 0)->orderby('created_at', 'desc')->get();
        if (!empty($data)) {
            $data2 = DB::update("update chats set chat_view2 = 1 where touserId = $uid");

        }
    }


    public function view_chat_timer(Request $request)
    {
        $uid = auth()->id();
        $data = chat::where('touserId', '=', auth()->id())->where('chat_view2', '=', 0)->orderby('created_at', 'desc')->count();
        return $data;

    }

    public function save_chat(Request $request)
    {
        $status = 0;
        $settings = SiteSetting::find(1);
        if ($settings->groupChatCheck == 0) {
            $status = 1;
        }
        if ($request->hasFile('img')) {
            $destination = 'public/images/chat';
            $img = $request->file('img');
            $image_name = 'img-' . time() . '-' . $img->getClientOriginalName();

            $path = $img->storeAs($destination, $image_name);

            $chat = new chat();
            $chat->touserId = $request->to_user_id;
            $chat->fromuserId = Auth::user()->id;
            $chat->status = $status;
            $chat->description = $image_name;
            $chat->type = 'image';
            $chat->chat_view = 1;
            $chat->save();
        }
        if ($request->hasFile('attach')) {
            $destination = 'public/file/chat';
            $img = $request->file('attach');
            $image_name = 'file-' . time() . '-' . $img->getClientOriginalName();

            $path = $img->storeAs($destination, $image_name);

            $chat = new chat();
            $chat->touserId = $request->to_user_id;
            $chat->fromuserId = Auth::user()->id;
            $chat->status = $status;
            $chat->description = $image_name;
            $chat->type = 'file';
            $chat->chat_view = 1;
            $chat->save();
        }
        if ($request->description) {
            $chat = new chat();
            $chat->touserId = $request->to_user_id;
            $chat->fromuserId = Auth::user()->id;
            $chat->status = $status;
            $chat->description = $request->description;
            $chat->type = 'text';
            $chat->chat_view = 1;
            $chat->save();
        }
        redirect('/chats');
        return "SUCCESS";
    }

    public function get_name($id)
    {
        $user = User::find($id);
        $name = ($user->slug) ? $user->slug : $user->name . ' ' . $user->last_name;
        return $name;
    }

    public function get_chat_unread(Request $request)
    {

        $uid = auth()->id();

        $data['alldata'] = DB::SELECT("SELECT COUNT(fromuserId) as total_count,fromuserId,touserId,description,created_at FROM chats WHERE chat_view2 = 0 and touserId = '$uid' GROUP by fromuserId");


        foreach ($data['alldata'] as $item) {
            $item->fromuserId = $this->get_name($item->fromuserId);
        }

        return json_encode($data);
    }

    // public function chatNoti()
    // {
    //     $carbon = Carbon::today()->subDays(31);
    //     $id = Auth::user()->id;

    //     $getchat = DB::table('chats')
    //         ->where('touserId', Auth::user()->id)
    //         ->where('chat_view2', 0)
    //         ->whereDate('created_at', '>=', Carbon::today()->subDays(2))
    //         ->orderby('created_at', 'desc')
    //         ->limit(100)
    //         ->get();

    //     $getGroupChat = GroupChat::with(['user', 'group'])
    //         ->whereHas('group.users', function ($q) use ($id) {
    //             $q->where('user_id', $id)->where('status', 0);
    //         })
    //         ->whereDate('created_at', '>=', $carbon)
    //         ->where(function ($q) use ($id) {
    //             $q->whereRaw('NOT FIND_IN_SET(?, chat_view_users_id)', [$id])
    //                 ->orWhere('chat_view_users_id', NULL);
    //         })
    //         ->orderby('created_at', 'desc')
    //         ->limit(100)
    //         ->get();

    //     return view('main.phone_quote.global_chat.chat-noti', compact('getchat', 'getGroupChat'));
    // }
    public function chatNoti()
    {
        $carbon = Carbon::today()->subDays(31);
        $id = Auth::user()->id;

        $getchat = DB::table('chats')
            ->where('touserId', Auth::user()->id)
            ->where('chat_view2', 0)
            ->whereDate('created_at', '>=', Carbon::today()->subDays(2))
            ->orderby('created_at', 'desc')
            ->limit(100)
            ->get();

        $getGroupChat = GroupChat::with(['user', 'group'])
            ->whereHas('group.users', function ($q) use ($id) {
                $q->where('user_id', $id)->where('status', 0);
            })
            ->whereDate('created_at', '>=', $carbon)
            ->where(function ($q) use ($id) {
                $q->whereRaw('NOT FIND_IN_SET(?, chat_view_users_id)', [$id])
                    ->orWhere('chat_view_users_id', NULL);
            })
            ->orderby('created_at', 'desc')
            ->limit(100)
            ->get();

        return view('main.phone_quote.global_chat.chat-noti', compact('getchat', 'getGroupChat'));
    }

    public function chatNotiCount()
    {
        $carbon = Carbon::today()->subDays(31);
        $id = Auth::user()->id;

        $getchat = DB::table('chats')
            ->where('touserId', Auth::user()->id)
            ->where('chat_view2', 0)
            ->orderby('created_at', 'desc')
            ->whereDate('created_at', '>=', Carbon::today()->subDays(2))
            ->count();

        $getGroupChat = GroupChat::whereHas('group.users', function ($q) use ($id) {
            $q->where('user_id', $id)->where('status', 0);
        })
            ->whereDate('created_at', '>=', $carbon)
            ->where(function ($q) use ($id) {
                $q->whereRaw('NOT FIND_IN_SET(?, chat_view_users_id)', [$id])
                    ->orWhere('chat_view_users_id', NULL);
            })
            ->orderby('created_at', 'desc')
            ->count();

        $count = $getchat + $getGroupChat;

        return response()->json([
            'count' => $count,
            'status' => true,
            'status_code' => 200
        ], 200);

    }

    public function shipA1Chats()
    {
        return view('main.phone_quote.shipA1Chats');
    }
}
