<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
  public function index(Request $request)
{
    $users = User::where('id', '!=', $request->user()->id)->get();

    if ($users->count()) {
        return response()->json([
            'state' => 200,
            'users' => $users
        ]);
    }

    return response()->json([
        'state' => 404,
        'message' => 'error get users',
    ]);
}

    // عرض بيانات المستخدم الحالي
   public function show($id = null)
{
    if ($id) {
        $user = User::find($id);
    } else {
        $user = auth()->user();
    }

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    return response()->json($user);
}


    // تعديل بيانات المستخدم الحالي
    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|min:6|confirmed',
        ]);

        if ($request->name) $user->name = $request->name;
        if ($request->email) $user->email = $request->email;
        if ($request->password) $user->password = Hash::make($request->password);

        $user->save();

        return response()->json(['message' => 'تم تحديث البيانات بنجاح', 'user' => $user]);
    }

    // حذف المستخدم الحالي
    public function destroy(Request $request)
    {
        $user = $request->user();
        $user->delete();

        return response()->json(['message' => 'تم حذف الحساب بنجاح']);
    }
}
