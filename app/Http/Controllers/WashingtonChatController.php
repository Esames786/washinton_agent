<?php

namespace App\Http\Controllers;

use App\ChatMain;
use App\ThreadTable;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Washington Chat Controller
 *
 * Handles the customer-facing iframe chat (public widget) and
 * the admin chat management panel (role=1 only).
 *
 * Adapted from daydispatchagent ChatController — uses Washington's
 * default auth guard instead of 'Authorized'.
 */
class WashingtonChatController extends Controller
{
    // -------------------------------------------------------------------------
    // Customer-facing iframe endpoints (no auth required)
    // -------------------------------------------------------------------------

    /**
     * Serve the chat iframe page.
     * Called by the frontend widget: /chat-widget?user_id=0
     * When user_id > 0 it's the admin view.
     */
    public function indexIframe(Request $request)
    {
        $user_id   = (int) ($request->user_id ?? 0);
        $user_name = $request->user_name ?? null;
        $admin     = $user_id > 0;

        $domain = $request->headers->get('referer') ?? $request->getHost();
        if (empty($domain)) {
            $domain = $admin
                ? config('app.url')
                : config('app.url');
        }
        $domain     = parse_url($domain, PHP_URL_SCHEME) . '://' . parse_url($domain, PHP_URL_HOST);
        $cookieName = 'device_id_chat_' . str_replace('.', '_', $domain);

        if (!$request->hasCookie($cookieName)) {
            $deviceId = (string) Str::uuid();
            cookie()->queue(cookie($cookieName, $deviceId, 525600, '/', null, false, true, false, 'None'));
        } else {
            $deviceId = $request->cookie($cookieName);
        }

        return view('iframe.dashboard', [
            'user_id'   => $user_id,
            'admin'     => $admin,
            'deviceId'  => $deviceId,
            'domain'    => $domain,
            'user_name' => $user_name,
        ]);
    }

    /**
     * Send a chat message (customer or admin reply).
     */
    public function chat(Request $request)
    {
        try {
            $domain   = $request->reference_domain;
            $deviceId = $request->cookie('device_id_chat_' . str_replace('.', '_', $domain));

            if (!$deviceId) {
                $deviceId = (string) Str::uuid();
            }

            $user_name = $request->user_name ?? null;
            $today     = Carbon::today()->subWeek()->format('Y-m-d');

            if ((int) $request->admin === 1) {
                $thread = ThreadTable::find($request->thread_id);
            } else {
                $thread = ThreadTable::where('ip_address', $deviceId)
                    ->whereDate('created_at', $today);

                if ($thread->exists()) {
                    $thread = $thread->first();
                } else {
                    $thread        = new ThreadTable();
                    $thread->name  = $request->name;
                    $thread->email = $request->email;
                }
            }

            $thread->user_id    = $request->user_id;
            $thread->ip_address = ((int) $request->admin === 1) ? $thread->ip_address : $deviceId;
            $thread->replied    = ($request->user_id == 0) ? 0 : 1;
            $thread->save();

            // Geo lookup
            try {
                $client       = new Client(['timeout' => 3]);
                $response     = $client->get("http://ipinfo.io/{$request->ip()}/json");
                $locationData = json_decode($response->getBody(), true);
                $country      = $locationData['country'] ?? '-';
                $city         = $locationData['city'] ?? '-';
                $region       = $locationData['region'] ?? '-';
            } catch (\Exception $e) {
                $country = $city = $region = '-';
            }

            $chat                  = new ChatMain();
            $chat->thread_id       = $thread->id;
            $chat->send_message    = ((int) $request->admin === 0) ? $request->input('content') : null;
            $chat->receive_message = ((int) $request->admin === 1) ? $request->input('content') : null;
            $chat->date_created    = date('Y-m-d');
            $chat->ip_address      = $deviceId;
            $chat->read_it         = ((int) $request->admin === 1) ? 1 : 0;
            $chat->read_it_c       = ((int) $request->admin === 0) ? 1 : 0;
            $chat->info_data       = "{$country},{$city},{$region},{$request->ip()}";
            $chat->admin_name      = !empty($user_name) ? $user_name : null;
            $chat->save();

            return response()->json(['data' => $chat, 'status' => 0]);

        } catch (\Throwable $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    /**
     * Get chat history for a thread.
     */
    public function chatHistory(Request $request)
    {
        $thread = ThreadTable::where(function ($query) use ($request) {
            if (!empty($request->thread_id)) {
                $query->where('id', $request->thread_id);
            } else {
                $query->where('ip_address', $request->ip_address)
                      ->whereDate('created_at', $request->date_created);
            }
        })->latest()->first();

        if (!empty($thread)) {
            $chats = ChatMain::where('thread_id', $thread->id)
                ->when(!empty($request->keyword), function ($q) use ($request) {
                    $q->where('send_message', $request->keyword)
                      ->orWhere('receive_message', $request->keyword);
                })
                ->orderBy('id', 'asc')
                ->get();

            return response()->json([
                'data'   => $chats,
                'status' => count($chats) > 0 ? 1 : 0,
            ]);
        }

        return response()->json(['data' => [], 'status' => 0]);
    }

    // -------------------------------------------------------------------------
    // Admin panel endpoints (role=1 only)
    // -------------------------------------------------------------------------

    /**
     * Admin chat history list — shows all customer threads.
     * Only accessible to role=1 (Admin).
     */
    public function showHistory(Request $request)
    {
        $date  = Carbon::parse($request->date_created ?? now()->format('Y-m-d'));
        $chats = ChatMain::select(
            'chat_mains.date_created',
            'chat_mains.thread_id',
            'chat_mains.ip_address',
            'chat_mains.admin_name',
            'chat_mains.id',
            'thread_tables.replied',
            'thread_tables.name',
            DB::raw('(SELECT COUNT(c2.read_it) FROM chat_mains AS c2
                      WHERE c2.thread_id = chat_mains.thread_id AND c2.read_it = 0) AS tc'),
            DB::raw('(SELECT COUNT(c2.read_it_c) FROM chat_mains AS c2
                      WHERE c2.thread_id = chat_mains.thread_id AND c2.read_it_c = 0) AS tc_c')
        )
            ->leftJoin('thread_tables', 'thread_tables.id', '=', 'chat_mains.thread_id')
            ->whereBetween('chat_mains.date_created', [
                $date->copy()->subDay()->startOfDay(),
                $date->copy()->endOfDay(),
            ])
            ->groupBy('chat_mains.thread_id')
            ->orderBy('chat_mains.id', 'desc')
            ->get();

        return response()->json($chats);
    }

    /**
     * Mark messages as read.
     */
    public function chatUpdateRead(Request $request)
    {
        if ((int) $request->type === 1) {
            ChatMain::where('thread_id', $request->thread_id)->update(['read_it' => 1]);
        } else {
            ChatMain::where('thread_id', $request->thread_id)->update(['read_it_c' => 1]);
        }
        return response()->json(['status' => true]);
    }

    /**
     * Count unread messages for the admin badge.
     */
    public function unreadCount()
    {
        $count = ChatMain::where('read_it', 0)->count();
        return response()->json(['count' => $count, 'status' => true]);
    }
}
