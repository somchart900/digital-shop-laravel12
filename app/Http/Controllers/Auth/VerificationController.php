<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\MailController;
use App\Models\Otp;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    public function index()
    {
        if (Auth::user()->email_verified_at != null) {
            return redirect('user/profile');
        }
        $title = 'Verification';
        $apppassword = Setting::Where('name', 'apppassword')->first();
        return view('auths.verification', compact(
            'title',
            'apppassword'
        ));
    }

    public function verificationRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with([
                'error' => true,
                'message' => $validator->errors()->first()
            ]);
        }
        $email = $request->email;
        // ตรวจสอบอีเมล
        $user = $this->user_info_by_email($email);
        if (!$user) {
            return redirect()->back()->with(
                [
                    'error' => true,
                    'message' => 'ไม่พบผู้ใช้งาน'
                ]
            );
        }
        $checkotp = $this->check_otp($user->id);
        if ($checkotp) {
            return redirect('auth/verification-otp')->with(
                [
                    'success' => true,
                    'message' => 'รหัส OTP ได้ถูกส่งไปยังอีเมล ' . $email . ' กรุณาตรวจสอบอีเมล'
                ]
            );
        }
        // สร้างรหัส OTP
        $otp = $this->otp_generate();
        if (!$otp) {
            return redirect()->back()->with(
                [
                    'error' => true,
                    'message' => 'ไม่สามารถสร้างรหัส OTP ได้'
                ]
            );
        }
        // ส่งรหัส OTP
        $mok = $request->mok ?? '';
        $mailcontroller = new MailController();
        $sendotp = $mailcontroller->sendEmail($email, $otp, $mok);

        if (!$sendotp) {
            return redirect()->back()->with(
                [
                    'error' => true,
                    'message' => 'ไม่สามารถส่งรหัส OTP ได้'
                ]
            );
        }
        // บันทึก OTP ในฐานข้อมูล
        $makeotp = $this->make_otp($email, $otp);
        if (!$makeotp) {
            return redirect()->back()->with(
                [
                    'error' => true,
                    'message' => 'ไม่สามารถบันทึก OTP ในฐานข้อมูล ได้'
                ]
            );
        }
        // success
        return redirect('auth/verification-otp')->with(
            [
                'success' => true,
                'message' => 'รหัส OTP ได้ถูกส่งไปยังอีเมล ' . $email . ' กรุณาตรวจสอบอีเมล'
            ]
        );
    }
    public function verificationOtp()
    {
        if (Auth::user()->email_verified_at != null) {
            return redirect('user/profile');
        }
       $title = 'Verification';
        return view('auths.verification-otp', compact('title'));
    }

    public function verificationProcess(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'otp' => 'required',
        ]);
        if ($validator->fails()) {
            Log::info('$validator = Validator::make(request()->all(),');
            return redirect()->back()->with([
                'error' => true,
                'message' => $validator->errors()->first()
            ]);
        }
        $otp = $request->otp;
        $verifyotp = Otp::Where('otp', $otp)->Where('otp_expired', '>', now())->first();
        if (!$verifyotp) {
            Log::info('$verifyotp = $this->check_otp($otp)');
            return redirect()->back()->with(
                [
                    'error' => true,
                    'message' => 'รหัส OTP ไม่ถูกต้อง หรือหมดอายุ'
                ]
            );
        }
        $user = User::where('id', $verifyotp->user_id)
            ->update([
                'email_verified_at' => now()
            ]);
        if (!$user) {
            Log::info('$user = User::update' . $verifyotp->user_id);
            return redirect()->back()->with(
                [
                    'error' => true,
                    'message' => 'ไม่สามารถยืนยันอีเมล ได้'
                ]
            );
        }
        return redirect()->back()->with(
            [
                'success' => true,
                'message' => 'ยืนยันอีเมลสําเร็จ'
            ]
        );
    }

    public function otp_generate()
    {
        $otp = rand(100000, 999999);
        return $otp;
    }
    public function make_otp($email, $otp)
    {
        $user = $this->user_info_by_email($email);
        if (!$user) {
            return false;
        }
        $make_otp = Otp::updateOrCreate(
            ['user_id' => $user->id], // เงื่อนไขหา record เดิม
            [
                'otp' => $otp,
                'otp_expired' => now()->addMinutes(15) // ค่าที่จะอัปเดต/สร้าง
            ]
        );
        if (!$make_otp) {
            return false;
        }
        return true;
    }
    public function check_otp($user_id)
    {
        $status = Otp::Where('user_id', $user_id)->first();
        if (!$status) {
            return false;
        }
        if ($status->otp_expired < now()) {
            return false;
        }
        return true;
    }

    public function user_info_by_email($email)
    {
        $user = User::Where('email', $email)->first();
        return $user;
    }
}
