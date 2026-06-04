<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.force_https')) {
            URL::forceScheme('https');
        }

        try {
            $settings = Setting::pluck('value', 'key');
            config(['settings' => $settings->toArray()]);
        } catch (\Exception $e) {
            // Table mungkin belum ada saat fresh migration
            config(['settings' => []]);
        }
    }
}
