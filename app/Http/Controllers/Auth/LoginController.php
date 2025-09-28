<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginLog;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Validator;


class LoginController extends Controller
{
    public function index()
    {
        $title = 'เข้าสู่ระบบ';
        $sitekey = Setting::where('name', 'sitekey')->first();
        return view('auths.login', compact('title', 'sitekey'));
    }

    public function loginProcess(Request $request)
    {
        // Validate input ก่อน
        $validator = Validator::make($request->all(), [
            'username' => 'required|min:4|max:20',
            'password' => 'required|min:4|max:50',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {

                return response()->json($validator->errors(), 422);
            } else {
                return back()->withErrors($validator)->withInput();
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

        $login = $request->input('username');
        $password = $request->input('password');

        // ตรวจว่า login เป็น email หรือ username
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $field => $login,
            'password' => $password,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $agent = new Agent;
            LoginLog::create([
                'user_id' => Auth::user()->id,
                'ip' => $request->ip(),
                'browser' => $agent->browser(),
                'os' => $agent->platform(),
            ]);

            return redirect('/')->with([
                'success' => true,
                'message' => 'ยินดีต้อนรับ ' . Auth::user()->username,
            ]);
        }

        return back()->withErrors([
            'username' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง',
        ]);
    }
}
