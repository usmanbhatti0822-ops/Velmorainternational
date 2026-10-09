<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = (string) $request->route('locale', 'en');

        if (! in_array($locale, ['en', 'ar'], true)) {
            abort(404);
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
