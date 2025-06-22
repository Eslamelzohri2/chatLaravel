<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;

// Broadcast routes مع حماية JWT
Broadcast::routes(['middleware' => ['auth:api']]);

// تسجيل وتسجيل دخول
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// بيانات المستخدم الحالي
Route::middleware('auth:api')->get('/me', [AuthController::class, 'me']);

// تحديث التوكن
Route::middleware('auth:api')->post('/auth/refresh', [AuthController::class, 'refresh']);

// المحمية بالتوثيق
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // محادثات
    Route::get('/chats', [ChatController::class, 'index']);
    Route::post('/chats/start', [ChatController::class, 'startChat']);
    Route::delete('/chats/{id}', [ChatController::class, 'destroy']);

    // رسائل
    Route::get('/chats/{chat_id}/messages', [MessageController::class, 'getMessages']);
    Route::post('/messages/send', [MessageController::class, 'sendMessage']);
    Route::get('/my-chats', [MessageController::class, 'myChats']);

    // المستخدمين
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::get('/user', [UserController::class, 'show']);
    Route::post('/user', [UserController::class, 'update']);
    Route::delete('/user', [UserController::class, 'destroy']);
});

// رد فعل على رسالة
Route::post('/messages/{id}/react', [MessageController::class, 'addReaction']);
