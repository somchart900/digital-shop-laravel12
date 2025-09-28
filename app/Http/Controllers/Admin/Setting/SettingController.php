<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Http\Controllers\MailController;
use App\Services\MyApi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function updateOrCreate(Request $request)
    {
        if (Auth::user()->level != 99) {
            return json_encode([
                'status' => 'error',
                'message' => 'เฉพาะผู้ดูแลระบบเท่านั้น'
            ]);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            return json_encode([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }
        //ทดสอบการส่งอีเมล ป้องกัน อีเมล์ หรือ รหัส ไม่ถูกต้อง
        if ($request->name ==  'apppassword') {
            $send_test = new MailController();
            $test = $send_test->send_test($request->value);
            if (!$test) {
                return json_encode([
                    'status' => 'error',
                    'message' => 'อีเมล์ หรือ รหัส ไม่ถูกต้อง'
                ]);
            }
        }
        // ทดสอบการส่งข้อความ ป้องกัน โทเคน หรือ แชทไอดี ไม่ถูกต้อง
        if ($request->name ==  'chatid') {
            $bottoken = Setting::where('name', 'bottoken')->first();
            $bottoken = $bottoken->value ?? '';
            $send_test = MyApi::telegram($bottoken, $request->value, 'test');
            if (!$send_test) {
                return json_encode([
                    'status' => 'error',
                    'message' => 'โทเคน หรือ แชทไอดี ไม่ถูกต้อง'
                ]);
            }
        }
        // ตรวจสอบรุปแบบ secretkey sitekey
        if ($request->name ==  'secretkey' || $request->name ==  'sitekey') {
            $result = isValidGooglekey($request->value);
            if (!$result) {
                return json_encode([
                    'status' => 'error',
                    'message' => 'รุปแบบ key ไม่ถูกต้อง'
                ]);
            }
        }
        // ตรวจสอบรุปแบบ bottoken
        if ($request->name ==  'bottoken') {
            $result = isValidTelegramBotToken($request->value);
            if (!$result) {
                return json_encode([
                    'status' => 'error',
                    'message' => 'รุปแบบ tokken ไม่ถูกต้อง'
                ]);
            }
        }
        // byshop apikey
        if ($request->name ==  'byshop') {
            $result = MyApi::byshopCheckMoney($request->value);
            if ($result['status'] == 'error') {
                return json_encode([
                    'status' => 'error',
                    'message' => 'รุปแบบ apikey ไม่ถูกต้อง'
                ]);
            }
        }

        $update = Setting::updateOrCreate([
            'name' => $request->name
        ], [
            'value' => $request->value
        ]);        
        if ($update) {
            Cache::forget('settings');
            Cache::forget('settings_all');
            Cache::forget('payment_settings');
            return json_encode([
                'status' => 'success',
                'message' => 'อัพเดทข้อมูลสําเร็จ'
            ]);
        } else {
            return json_encode([
                'status' => 'error',
                'message' => 'อัพเดทข้อมูลไม่สําเร็จ'
            ]);
        }
    }

    public function delete(Request $request)
    {
        if (Auth::user()->level != 99) {
            return json_encode([
                'status' => 'error',
                'message' => 'เฉพาะผู้ดูแลระบบเท่านั้น'
            ]);
        }
        $delete = Setting::where('id', $request->id)->delete();
        if ($delete) {
            Cache::forget('settings');
            Cache::forget('settings_all');
            Cache::forget('payment_settings');
            return json_encode([
                'status' => 'success',
                'message' => 'ลบข้อมูลสําเร็จ'
            ]);
        } else {
            return json_encode([
                'status' => 'error',
                'message' => 'ลบข้อมูลไม่สําเร็จ'
            ]);
        }
    }

    public function check_credit_byshop()
    {
        $api = Setting::where('name', 'byshop')->first();
        $result = MyApi::byshopCheckMoney($api->value ?? 'apikey');
        return json_encode([
            'status' => $result['status'],
            'message' => $result['money']
        ]);
        
    }
}
