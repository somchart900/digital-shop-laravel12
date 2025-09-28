<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Otp;
use Illuminate\Support\Facades\Validator;
class ResetPasswordController extends Controller
{
    public function index()
    {
        $title = 'Reset Password';
        return view('auths.reset-password', compact('title'));
    }
    public function resetPasswordProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with([
                'error' => true,
                'message' => $validator->errors()->first()
            ]);
        }
        $email = $request->email;
        $user = User::Where('email', $email)->first();
        if (!$user) {
            return redirect()->back()->with(
                [
                    'error' => true,
                    'message' => 'ไม่พบผู้ใช้งาน'
                ]
            );
        }
        $otp = $this->otp_verify($request->otp);
        if (!$otp) {
            return redirect()->back()->with([
                'error' => true,
                'message' => 'รหัส OTP ไม่ถูกต้อง'
            ]);
        }
        $update_password = $this->reset_password($email, $request->password);
        if (!$update_password) {
            return redirect()->back()->with([
                'error' => true,
                'message' => 'ไม่สามารถเปลี่ยนรหัสผ่านได้'
            ]);
        }
        return redirect('/auth/login')->with([
            'success' => true,
            'message' => 'เปลี่ยนรหัสผ่านเรียบร้อย'
        ]);
    }


    public function otp_verify($otp)
    {
        $otp = Otp::Where('otp', $otp)->first();
        if (!$otp) {
            return false;
        }
        if ($otp->otp_expired < now()) {
            return false;
        }
        return true;
    }

    public function reset_password($email, $password)
    {
        $user = User::Where('email', $email)->first();
        if (!$user) {
            return false;
        }
        $user->password = bcrypt($password);
        $user->save();
        return true;
    }
    
}
