<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class MyApi
{
    //เช็คเงิน
    public static function byshopCheckMoney($apikey)
    {
        // URL ของ API
        $apiUrl = "https://byshop.me/api/money";
        // ข้อมูลที่ต้องส่ง
        $data = array(
            'keyapi' => $apikey,
        );
        // กำหนดตัวเลือกของ cURL
        $options = array(
            CURLOPT_URL => $apiUrl,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15, // รอทั้งหมดไม่เกิน 30 วินาที (การรับส่งข้อมูลทั้งหมด)
            CURLOPT_CONNECTTIMEOUT => 10, // รอเชื่อมต่อไม่เกิน 10 วินาที
        );
        // สร้าง cURL handle
        $ch = curl_init();
        curl_setopt_array($ch, $options);
        // ทำ HTTP POST request
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            Log::error("cURL Error: " . curl_error($ch));
           // error_log("cURL Error: " . curl_error($ch));  // บันทึก error
            curl_close($ch); // ปิดการเชื่อมต่อ cURL
            return null;
        }
        curl_close($ch);  // ปิดการเชื่อมต่อ cURL
        $responseData = json_decode($response, true); // true = แปลงเป็น array


        // ตรวจสอบว่า JSON decode สำเร็จและมี key 'money' หรือไม่

        if ($responseData === null) {

            return null;
        } else {

            return $responseData;
        }
    }
    //----------------------------
    //เช็คสลิป
    public static function checkslip($apikey, $qrcode_text, $bankCodeAcc, $OwnerAcc, $mok)
    {
        if ($mok >= 1 && $mok <= 10000000) {
            return [
                'success' => true,         // สถานะการทํารายการ
                'amount' => $mok,             // จำนวนเงิน 
                'message' => 'สําเร็จ' // อธิบายว่าเกิดอะไรขึ้น
            ];
        }
        $qrcode_text = trim($qrcode_text); // ตัดช่องว่างหน้า-หลังเผื่อมี
        $apikey = trim($apikey); // ตัดช่องว่างหน้า-หลังเผื่อมี
        $url = 'https://byshop.me/api/check_slip';
        //------- config -------
        $data = [
            'qrcode_text' => $qrcode_text,
            'keyapi' => $apikey, // ใส่ KeyApi ของท่าน
        ];
        // cURL ตั้งค่า
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // ใช้ http_build_query() เพื่อความปลอดภัย
        $response = curl_exec($ch);
        if ($response === false) {
            Log::error("ทำรายการไม่สำเร็จ: cURL Error - " . curl_error($ch));
           // error_log("ทำรายการไม่สำเร็จ: cURL Error - " . curl_error($ch));
            curl_close($ch);
            return [
                'success' => false,
                'amount' => 0,
                'message' => 'cURL Error'
            ];
        }
        curl_close($ch);
        $data = json_decode($response, true);
        // ตรวจสอบ JSON
        if (!isset($data['status'])) {
            Log::error('ทำรายการไม่สำเร็จ: ไม่สามารถอ่านข้อมูล JSON ได้');
           // error_log('เกิดข้อผิดพลาด: ไม่สามารถอ่านข้อมูล JSON ได้');
            return [
                'success' => false,
                'amount' => 0,
                'message' => 'ไม่สามารถอ่านข้อมูล JSON ได้'
            ];
        }
        if ($data['status'] == 0) {
            Log::error('ทำรายการไม่สำเร็จ: data ไม่ถูกต้อง');
           // error_log('ทำรายการไม่สำเร็จ: data ไม่ถูกต้อง');
            return [
                'success' => false,
                'amount' => 0,
                'message' => 'data ไม่ถูกต้อง'
            ];
        }
        // ตรวจสอบข้อมูลจาก API
        $amount = $data['amount'] ?? 0;
        $check_slip = $data['check_slip'] ?? 0;
        $slipTimestamp = $data['slip_timestamp'] ?? '';
        $bankCodeAcc = trim(strval($bankCodeAcc)); // แปลงเป็น string และตัดช่องว่าง
        $OwnerAcc = trim(strval($OwnerAcc)); // แปลงเป็น string และตัดช่องว่าง
        $receiverBankCode = trim(strval($data['receiver']['bank_code'] ?? ''));
        $receiverAccNo = trim(strval($data['receiver']['acc_no'] ?? ''));
        // ===== ตรวจสอบเวลาสลิปไม่เกิน 15 นาที =====
        // $slipTimeStr = $data['slip_time'] ?? '';
        // $slipTimestamp = strtotime($slipTimeStr);
        $currentTimestamp = time();
        $timeDiffMinutes = ($currentTimestamp - $slipTimestamp) / 60;

        if ($timeDiffMinutes > 20) {
            return [
                'success' => false,
                'amount' => 0,
                'message' => 'สลิปเกิน 20 นาทีแล้ว ไม่สามารถตรวจสอบได้'
            ];
        }

        // เช็คว่าสลิปเคยใช้งานหรือไม่
        if ($check_slip == 1) {
            return [
                'success' => false,
                'amount' => 0,
                'message' => 'สลิปนี้ถูกใช้งานแล้ว !!'
            ];
        }
        // ตรวจสอบธนาคารรับเงิน
        if ($bankCodeAcc != $receiverBankCode) {
            return [
                'success' => false,
                'amount' => 0,
                'message' => 'ธนาคารรับโอนเงินไม่ถูกต้อง !!'
            ];
        }

        // ตรวจสอบบัญชีรับเงิน
        $matchLength = similar_text($OwnerAcc, $receiverAccNo);
        $checkAcc = ($matchLength >= 4);
        if ($checkAcc == false) {
            return [
                'success' => false,
                'amount' => 0,
                'message' => 'บัญชีรับเงินไม่ถูกต้อง !!'
            ];
        }
        // หากทุกอย่างถูกต้อง

        return [
            'success' => true,         // สถานะการทํารายการ
            'amount' => $amount,             // จำนวนเงิน 
            'message' => 'สําเร็จ' // อธิบายว่าเกิดอะไรขึ้น
        ];
    }
    // ----------------------------
    //ส่งข้อความไปยัง Telegram
    public static function telegram($token, $chatId, $message)
    {
        $url = "https://telegram.thewiner.win/bot{$token}/sendMessage"; //แก้แชร์โฮสติ้ง บล็อคโดเมน api.telegram.org
        // $url = "https://api.telegram.org/bot{$token}/sendMessage";
        $postData = [
            'chat_id'    => $chatId,
            'text'       => $message,
            'parse_mode' => 'HTML'  // สามารถใช้ HTML ในข้อความได้
        ];


        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Host: api.telegram.org",
            "Content-Type: application/x-www-form-urlencoded"
        ]);

        // ปิด SSL ตรวจสอบ (เพราะ cert จะไม่แมตช์ IP อยู่แล้ว)
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            Log::error("Telegram Error: " . curl_error($ch));
           // error_log("Telegram Error: " . curl_error($ch));
            return false;
        }

        curl_close($ch);

        $result = json_decode($response, true);

        if (!isset($result['ok']) || !$result['ok']) {
            Log::error("Telegram Error: " . json_encode($result));
          //  error_log("Telegram Error: " . json_encode($result));
            return false;
        }

        return true;
    }
    //----------------------------
    // ส่งข้อความไปยัง LINE
    public static function linebot($token, $user_id, $message)
    {

        $data = [
            'to' => $user_id,
            'messages' => [
                [
                    'type' => 'text',
                    'text' => $message
                ]
            ]
        ];
        // ส่งข้อความไปยัง LINE
        $ch = curl_init('https://api.line.me/v2/bot/message/push');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        // ปิดการตรวจสอบ SSL (ไม่แนะนำใน production)
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        // ตรวจสอบการส่งข้อความ
        $response = curl_exec($ch);
        if ($response === false) {
            curl_close($ch);
            Log::error('Error linebot: ' . curl_error($ch));
           // error_log('Error linebot: ' . curl_error($ch));
            return false;
        } else {
            curl_errno($ch);
            return true;
        }
        curl_close($ch);
    }
    //----------------------------
}

     // การส่งข้อความ
