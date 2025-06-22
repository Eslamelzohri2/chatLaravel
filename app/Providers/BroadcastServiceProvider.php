<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // ✅ نحدد هنا ميدل وير المصادقة
         Broadcast::routes([
        'middleware' => ['auth:api'], // ← مهم جدًا مع JWT
    ]);

        // تحميل ملف تعريف القنوات
        require base_path('routes/channels.php');
    }
}
