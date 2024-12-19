<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConvertEventTimezone
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestMethod = $request->method();
        if ($requestMethod === 'POST' || $requestMethod === 'PUT' || $requestMethod === 'PATCH') {
            if ($request->has('start_date')) {
                $request->merge(['start_date' => \dateFromSessionTime($request->input('start_date'), $request->user())]);
            }
            if ($request->has('end_date')) {
                $request->merge(['end_date' => \dateFromSessionTime($request->input('end_date'), $request->user())]);
            }
        }
        return $next($request);
    }
}
