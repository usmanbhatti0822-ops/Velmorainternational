<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStaff
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(
            $request->user()?->is_active && in_array($request->user()->role, ['super_admin', 'sales', 'content_manager', 'chat_agent'], true),
            403,
        );

        return $next($request);
    }
}
