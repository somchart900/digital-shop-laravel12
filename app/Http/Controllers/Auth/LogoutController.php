<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function __invoke(Request $request)
    {
        Auth::logout();
        $request->session()->flush();
        // ล้าง session ทั้งหมด
        $request->session()->invalidate();
        // ทำให้ session ปัจจุบันไม่ใช้ได้แล้ว
        $request->session()->regenerateToken();
        // สร้าง CSRF token ใหม่

        return redirect('/')->with('success', true)->with('message', 'ออกจากระบบเรียบร้อย');
    }
}
