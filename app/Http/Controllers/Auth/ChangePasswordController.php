<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChangePasswordController extends Controller
{
   public function __invoke(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:4|confirmed',
        ]);
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->with('error', true)->with('message', 'รหัสผ่านเดิมไม่ถูกต้อง');
        }
        $user->password = Hash::make($request->new_password);

        $user->save();
        Auth::logout();
        $request->session()->invalidate();
        // ล้าง session เก่า
        $request->session()->regenerateToken();

        // สร้าง CSRF token ใหม่
        return redirect('/auth/login')->with('success', true)->with('message', 'เปลี่ยนรหัสผ่านเรียบร้อย');
    }
}
