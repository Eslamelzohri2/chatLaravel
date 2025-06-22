<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // تسجيل مستخدم جديد
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 404);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

public function login(Request $request)
{
    try {
        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'token' => $token,
            'user' => auth('api')->user(),
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ]);
    } catch (\Exception $e) {
        Log::error('Login error: ' . $e->getMessage());
        return response()->json(['error' => 'Server Error'], 500);
    }
}

    // بيانات المستخدم الحالي
    public function me()
    {
        return response()->json(auth()->user());
    }

    // تسجيل خروج
    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken()); // تعطيل التوكن الحالي
            return response()->json(['message' => 'تم تسجيل الخروج بنجاح']);
        } catch (JWTException $e) {
            return response()->json(['error' => 'فشل تسجيل الخروج'], 500);
        }
    }

    // تحديث التوكن
    public function refresh()
    {
        try {
            $token = JWTAuth::refresh(JWTAuth::getToken());
            return response()->json([
                'token' => $token,
                'user' => auth()->user(),
            ]);
        } catch (JWTException $e) {
            return response()->json(['error' => 'فشل تحديث التوكن'], 401);
        }
    }
}
