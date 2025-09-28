<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setting;
use App\Models\Otp;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\MailController;

class ForgetPasswordController extends Controller
{
    protected $mailController;

    public function index()
    {
        $apppassword = Setting::Where('name', 'apppassword')->first();
        $title = 'Forget Password';
        return view('auths.forget-password', compact(
            'title',
            'apppassword'
        ));
    }
    // สร้างรหัส OTP
    public function otp_generate()
    {
        $otp = rand(100000, 999999);
        return $otp;
    }
    // ค้นหาผู้ใช้งานด้วยอีเมล
    public function user_info_by_email($email)
    {
        $user = User::Where('email', $email)->first();
        return $user;
    }

    // บันทึกรหัส OTP ลงในฐานข้อมูล
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
    // รับ request และส่งรหัส OTP ไปยังอีเมล
    function forgetPasswordProcess(Request $request)
    {
        if (app()->runningUnitTests()) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'captcha' => 'required|captcha',
            ]);
        }

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
            session(['reset_email' => $email]);
            return redirect('auth/reset-password')->with(
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
        session(['reset_email' => $email]);
        return redirect('auth/reset-password')->with(
            [
                'success' => true,
                'message' => 'รหัส OTP ได้ถูกส่งไปยังอีเมล ' . $email . ' กรุณาตรวจสอบอีเมล'
            ]
        );
    }
}
