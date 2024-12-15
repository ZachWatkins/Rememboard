<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class EnsureTimezoneOffset
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (App::environment('testing')) {
            return $next($request);
        }

        $timezoneOffset = session('timezoneOffset') ?? $request->header('X-Timezone-Offset');

        if ($timezoneOffset !== session('timezoneOffset')) {
            session(['timezoneOffset' => $timezoneOffset]);
        }

        return $next($request);
    }
}
