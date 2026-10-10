<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        RateLimiter::for('chat-start', fn (Request $request) => Limit::perMinute(8)->by('chat-start:'.$request->ip()));
        RateLimiter::for('chat-message', fn (Request $request) => Limit::perMinute(20)->by('chat-message:'.$request->route('uuid').':'.$request->ip()));
        RateLimiter::for('chat-poll', fn (Request $request) => Limit::perMinute(90)->by('chat-poll:'.$request->route('uuid').':'.$request->ip()));
    }
}
