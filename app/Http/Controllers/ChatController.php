<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Show the chat interface.
     */
    public function index()
    {
        return view('chat.index');
    }

    /**
     * Get list of contacts (all other users) with last message preview and unread count.
     */
    public function getContacts()
    {
        $authId = Auth::id();
        $users = User::where('id', '!=', $authId)->get();

        $contacts = $users->map(function ($user) use ($authId) {
            // Get last message between auth user and this contact
            $lastMessage = Message::where(function ($q) use ($authId, $user) {
                $q->where('sender_id', $authId)->where('receiver_id', $user->id);
            })->orWhere(function ($q) use ($authId, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $authId);
            })->orderBy('created_at', 'desc')->first();

            // Get unread count from this contact
            $unreadCount = Message::where('sender_id', $user->id)
                ->where('receiver_id', $authId)
                ->where('is_read', false)
                ->count();

            return [
                'id'            => $user->id,
                'name'          => $user->name,
                'email'         => $user->email,
                'level'         => $user->level,
                'role'          => $user->level == 1 ? 'Administrator' : ($user->level == 2 ? 'Manager' : 'Cashier'),
                'foto'          => $user->foto ?? null,
                'last_message'  => $lastMessage ? $lastMessage->message : null,
                'last_time'     => $lastMessage ? $lastMessage->created_at->diffForHumans() : null,
                'unread_count'  => $unreadCount,
            ];
        });

        // Sort: users with messages first (most recent), then the rest
        $contacts = $contacts->sortByDesc(function ($c) {
            return $c['last_message'] ? 1 : 0;
        })->values();

        return response()->json($contacts);
    }

    /**
     * Fetch messages between auth user and a specific user.
     */
    public function fetchMessages($userId)
    {
        $authId = Auth::id();

        // Mark messages from this user as read
        Message::where('sender_id', $userId)
            ->where('receiver_id', $authId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Get conversation messages
        $messages = Message::where(function ($q) use ($authId, $userId) {
                $q->where('sender_id', $authId)->where('receiver_id', $userId);
            })
            ->orWhere(function ($q) use ($authId, $userId) {
                $q->where('sender_id', $userId)->where('receiver_id', $authId);
            })
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) use ($authId) {
                return [
                    'id'         => $msg->id,
                    'message'    => $msg->message,
                    'sender_id'  => $msg->sender_id,
                    'is_mine'    => $msg->sender_id == $authId,
                    'time'       => $msg->created_at->format('h:i A'),
                    'full_time'  => $msg->created_at->format('M d, Y — h:i A'),
                    'date'       => $msg->created_at->format('M d, Y'),
                    'created_at' => $msg->created_at->toISOString(),
                ];
            });

        return response()->json($messages);
    }

    /**
     * Send a new message.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message'     => 'required|string|max:5000',
        ]);

        $message = Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message'     => $request->message,
        ]);

        return response()->json([
            'id'         => $message->id,
            'message'    => $message->message,
            'sender_id'  => $message->sender_id,
            'is_mine'    => true,
            'time'       => $message->created_at->format('h:i A'),
            'date'       => $message->created_at->format('M d, Y'),
            'created_at' => $message->created_at->toISOString(),
        ]);
    }
}
