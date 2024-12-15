<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        if (Auth::check()) {
            $requestMethod = $request->method();
            if ($requestMethod === 'POST' || $requestMethod === 'PUT' || $requestMethod === 'PATCH') {
                if ($request->has('start_date')) {
                    $request->merge(['start_date' => convertFromUserTimezone($request->input('start_date'))]);
                }
                if ($request->has('end_date')) {
                    $request->merge(['end_date' => convertFromUserTimezone($request->input('end_date'))]);
                }
            }
        }
        return $next($request);
    }
}
