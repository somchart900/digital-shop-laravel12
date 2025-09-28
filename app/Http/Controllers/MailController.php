<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use App\Models\Setting;

class MailController extends Controller
{
    public function sendEmail($to_email, $otp, $mok)
    {    // สําหรับ test การส่งอีเมล 
        if ($mok == 'true') {  
            Log::info('สําหรับ test การส่งอีเมล return '.$mok);
            return true;
        }else if ($mok == 'false') {
            Log::info('สําหรับ test การส่งอีเมล return '.$mok);
            return false;
        }
        $get_email = Setting::where('name', 'email')->first();
        $get_apppassword = Setting::where('name', 'apppassword')->first();
        // ถ้าไม่มี email หรือ password
        if (empty($get_email) || empty($get_apppassword)) {
            Log::error('ไม่พบ email หรือ password');
            return false;
        }
        $from_email = $get_email->value;
        $app_password = $get_apppassword->value;
     
        // ลบช่องว่างก่อนใช้งาน
        $app_password = str_replace(' ', '', $app_password);
        
        $data = ['otp' => $otp]; // ส่งรหัส OTP ไปยัง view

        try {
            // Render Blade เป็น HTML
            $htmlContent = View::make('emails.otp', $data)->render();

            // สร้าง SMTP transport สำหรับ Gmail
            $transport = Transport::fromDsn("smtp://$from_email:$app_password@smtp.gmail.com:587?encryption=tls");

            $mailer = new Mailer($transport);

            // สร้าง email
            $email = (new Email())
                ->from($from_email)
                ->to($to_email)
                ->subject('รหัส OTP')
                ->html($htmlContent);

            // ส่งอีเมล์
            $mailer->send($email);

            // เขียน log สำเร็จ
            // Log::info("ส่งอีเมล์สำเร็จไปยัง: {$to_email}");
            return true;
        } catch (\Throwable $e) {

            // เขียน log error
            Log::error("ส่งอีเมล์ล้มเหลว: " . $e->getMessage());
            return false;
        }
    }

    public function send_test($password)
    {    // สําหรับ test การส่งอีเมล 
        $to_email = 'anything@guerrillamail.com';
         $otp = '123456';
        $get_email = Setting::where('name', 'email')->first();
 
        $from_email = $get_email->value;
        $app_password = $password;
     
        // ลบช่องว่างก่อนใช้งาน
        $app_password = str_replace(' ', '', $app_password);
        
        $data = ['otp' => $otp]; // ส่งรหัส OTP ไปยัง view

        try {
            // Render Blade เป็น HTML
            $htmlContent = View::make('emails.otp', $data)->render();

            // สร้าง SMTP transport สำหรับ Gmail
            $transport = Transport::fromDsn("smtp://$from_email:$app_password@smtp.gmail.com:587?encryption=tls");

            $mailer = new Mailer($transport);

            // สร้าง email
            $email = (new Email())
                ->from($from_email)
                ->to($to_email)
                ->subject('รหัส OTP')
                ->html($htmlContent);

            // ส่งอีเมล์
            $mailer->send($email);

            // เขียน log สำเร็จ
            // Log::info("ส่งอีเมล์สำเร็จไปยัง: {$to_email}");
            return true;
        } catch (\Throwable $e) {

            // เขียน log error
            Log::error("userna " . $from_email." password " . $app_password);
            Log::error("ส่งอีเมล์ล้มเหลว: " . $e->getMessage());
            return false;
        }
    }

}
