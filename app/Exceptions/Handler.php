<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * أنواع الاستثناءات مع مستوى التسجيل المخصص لكل نوع.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * الاستثناءات التي لا يتم تسجيلها.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * الحقول التي لا تُخزن في الجلسة في حالة فشل التحقق.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * تسجيل الاستثناءات أو تنفيذ تعليمات خاصة بها.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
   protected function unauthenticated($request, AuthenticationException $exception)
{
    return response()->json(['message' => 'Unauthorized'], 401);
}
    
}
