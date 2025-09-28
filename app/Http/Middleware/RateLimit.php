<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\RateLimiter;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RateLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {        // ใช้ email + ip เป็น key
        $key = 'attempts:' . $request->ip();

        $maxAttempts = 2;
        $decaySeconds = 60;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            // ถ้าเกิน limit → set session และไม่อยู่ใน unit test
            if (app()->runningUnitTests()) {
                return $next($request);
            } else {
                session(['show_recaptcha' => true]);
            }
        } else {
            // ยังไม่เกิน → hit count ไป    
            RateLimiter::hit($key, $decaySeconds);
            session()->forget('show_recaptcha');
        }
        return $next($request);
    }
}
