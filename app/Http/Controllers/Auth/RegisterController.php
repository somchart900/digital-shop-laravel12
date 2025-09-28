<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Activitylog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Setting;
use App\Models\Credit;
use App\Models\Topup;

class RegisterController extends Controller
{
    public function index()
    {
        $title = 'สมัครสมาชิก';
        $sitekey = Setting::where('name', 'sitekey')->first();
        return view('auths.register', compact('title', 'sitekey'));
    }
    public function registerProcess(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'username' => 'required|string|max:50|unique:users,username|min:4|max:15',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:4|confirmed',
        ]);

        if ($validated->fails()) {
            if ($request->wantsJson()) {
                return response()->json($validated->errors(), 422);
            } else {
                return back()->withErrors($validated)->withInput();
            }
        }

        if (session()->has('show_recaptcha')) {
            $secret = Setting::where('name', 'secretkey')->first();
            $secret = $secret->value ?? 'secretkey';
            $recaptcha_response = $request->input('g-recaptcha-response');
            $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$recaptcha_response");
            $response_data = json_decode($verify);

            if ($response_data->success) {
                // ผ่าน reCAPTCHA
            } else {
                // ไม่ผ่าน reCAPTCHA
                return back()->withErrors([
                    'message' => 'กรุณาตรวจสอบ reCAPTCHA',
                ])->withInput();
            }
        }
        
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        Activitylog::create([
            'user_id' => $user->id,
            'action' => $user->username,
            'description' => 'สมัครสมาชิกเรียบร้อย',
        ]);
        $bonus = Setting::where('name', 'bonus')->first();
        $bonus = $bonus->value ?? 0;
        if ($bonus > 0) {
            Credit::create([
                'user_id' => $user->id,
                'amount' => $bonus
            ]);
            Topup::create([
                'user_id' => $user->id,
                'amount' => $bonus,
                'channel' => 'system',
                'status' => 'success',
                'link' => url('user/profile'),
                'remark' => 'bonus สมัครสมาชิก',
            ]);
        }
        return redirect('/')
            ->with('success', true)
            ->with('message', "สมัครสมาชิกเรียบร้อย {$user->username}");
    }
}
