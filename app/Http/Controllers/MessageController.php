<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class MessageController extends Controller
{
    public function myChats()
    {
        $userId = Auth::id();

        $chats = Chat::where('user1_id', $userId)
                    ->orWhere('user2_id', $userId)
                    ->with(['user1', 'user2'])
                    ->get();

        return response()->json($chats);
    }

    public function getMessages($chat_id)
    {
        $chat = Chat::with('messages.sender')->findOrFail($chat_id);

        return response()->json([
            'messages' => $chat->messages
        ]);
    }

    public function addReaction(Request $request, $id)
{
    $request->validate([
        'reaction' => 'required|string|max:10', // زي: 👍 أو ❤️ أو 😆 وهكذا
    ]);

    $message = Message::findOrFail($id);
    $message->reaction = $request->reaction;
    $message->save();

    // لو عايز تبث الريأكشن للمستقبل لحظيًا:
    // broadcast(new MessageReactionUpdated($message))->toOthers();

    return response()->json([
        'message' => 'Reaction added successfully',
        'data' => $message
    ]);
}

    public function sendMessage(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string'
        ]);

        $message = Message::create([
            'chat_id' => $request->chat_id,
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        // ✅ البث اللحظي
        broadcast(new MessageSent($message, $request->chat_id))->toOthers();

        return response()->json($message);
    }
}
