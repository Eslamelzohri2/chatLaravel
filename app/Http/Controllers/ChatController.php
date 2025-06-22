<?php
namespace App\Http\Controllers;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // جلب كل المحادثات الخاصة بالمستخدم
    public function index()
    {
        $userId = Auth::id();

        $chats = Chat::with(['user1', 'user2'])
            ->where('user1_id', $userId)
            ->orWhere('user2_id', $userId)
            ->get();

        return response()->json($chats);
    }

    // إنشاء أو جلب محادثة بين مستخدمين
 public function startChat(Request $request)
{
    $request->validate([
        'receiver_id' => 'required|exists:users,id',
    ]);

    $user1 = Auth::id();
    $user2 = $request->receiver_id;

    // البحث عن محادثة قديمة
    $chat = Chat::where(function ($q) use ($user1, $user2) {
            $q->where('user1_id', $user1)->where('user2_id', $user2);
        })
        ->orWhere(function ($q) use ($user1, $user2) {
            $q->where('user1_id', $user2)->where('user2_id', $user1);
        })
        ->first();

    if (!$chat) {
        $chat = Chat::create([
            'user1_id' => $user1,
            'user2_id' => $user2,
        ]);
    }

    // تحميل العلاقات
    $chat->load(['user1:id,name', 'user2:id,name']);

    // تحديد من هو المستقبل
    $receiver = $chat->user1_id == $user1 ? $chat->user2 : $chat->user1;

    return response()->json([
        'id' => $chat->id,
        'chat_id' => $chat->id,
        'receiver' => [
            'id' => $receiver->id,
            'name' => $receiver->name,
        ],
    ]);
}


    // (اختياري) حذف محادثة
    public function destroy($id)
    {
        $chat = Chat::findOrFail($id);

        // تأكد إن المستخدم من طرفي المحادثة
        if ($chat->user1_id != Auth::id() && $chat->user2_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $chat->delete();

        return response()->json(['message' => 'Chat deleted']);
    }
}
