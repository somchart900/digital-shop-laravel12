<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('auth/login')->with('error', true)->with('message', 'กรุณาเข้าสู่ระบบ');
        }
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // เผื่อ Auth::user() คืน null (เช่น session หมดอายุ)
        if (!$user || !$user->can('admin')) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'เฉพาะผู้ดูแลระบบเท่านั้น',
                ]);
            } else {
                // ถ้า request เป็น web ปกติ
                return back()
                    ->with('error', true)
                    ->with('message', 'เฉพาะผู้ดูแลระบบเท่านั้น');
            }
        }
        return $next($request);
    }
}
