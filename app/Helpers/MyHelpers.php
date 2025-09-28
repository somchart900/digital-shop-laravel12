<?php

// ฟังชั่นสําหรับแปล URL เป็น URL ฝัง YouTubeแส
function getYouTubeEmbedURL($youtubeURL)
{
    // ดึง Video ID จาก URL
    preg_match("/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([^\&\?\/]+)/", $youtubeURL, $matches);
    $videoID = isset($matches[1]) ? $matches[1] : '';

    // คืนค่า URL สำหรับฝัง
    return "https://www.youtube.com/embed/" . $videoID . "?autoplay=1&mute=1";
}

// ฟังก์ชันสําหรับตรวจสอบ URL ใน string ที่กําหนด(code ส่งมอบ)
function isUrl($url)
{
    return (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0);
}

// ฟังชั่นตรวจสอบรูปแบบ Google reCAPTCHA v2 key เบื้องต้น
function isValidGooglekey($siteKey)
{
    // ตรวจสอบว่าเป็น string และไม่ใช่ null หรือ empty
    if (!is_string($siteKey) || empty(trim($siteKey))) {
        return false;
    }

    // ลบ whitespace
    $siteKey = trim($siteKey);

    // ตรวจสอบความยาว (ต้องเป็น 40 ตัวอักษรพอดี)
    if (strlen($siteKey) !== 40) {
        return false;
    }

    // ตรวจสอบว่าขึ้นต้นด้วย "6L"
    if (substr($siteKey, 0, 2) !== '6L') {
        return false;
    }

    return true;
}


// ฟังชั่นตรวจสอบรูปแบบ Telegram Bot Token เบื้องต้น
function isValidTelegramBotToken($token)
{
    // ตรวจสอบว่า token เป็น string หรือไม่
    if (!is_string($token)) {
        return false;
    }

    // ตรวจสอบว่ามีเครื่องหมาย : หรือไม่
    if (strpos($token, ':') === false) {
        return false;
    }

    // แยก token ออกเป็น 2 ส่วน
    $parts = explode(':', $token, 2);

    // ตรวจสอบว่าแยกได้ 2 ส่วนหรือไม่
    if (count($parts) !== 2) {
        return false;
    }

    $botId = $parts[0];
    $secretToken = $parts[1];

    // ตรวจสอบ Bot ID (8-10 หลัก, เป็นตัวเลขเท่านั้น)
    if (!preg_match('/^[0-9]{8,10}$/', $botId)) {
        return false;
    }

    // ตรวจสอบ Secret Token (35 ตัวอักษร, A-Z, a-z, 0-9, _, -)
    if (!preg_match('/^[A-Za-z0-9_-]{35}$/', $secretToken)) {
        return false;
    }

    // ตรวจสอบความยาวรวม (ประมาณ 44-46 ตัวอักษร)
    $totalLength = strlen($token);
    if ($totalLength < 44 || $totalLength > 46) {
        return false;
    }

    return true;
}


function maskUsername($name) {
    // ตัดช่องว่างหน้า-หลัง
    $name = trim($name);
    // ใช้ mb_ เพื่อรองรับ multi-byte (ไทย)
    $len = mb_strlen($name, 'UTF-8');
    if ($len === 0) {
        return '**';
    }
    // ถ้ามีความยาวมากกว่า 2 ให้เอาส่วนหน้าทั้งหมด ยกเว้น 2 ตัวท้าย แล้วต่อด้วย **
    if ($len > 2) {
        $front = mb_substr($name, 0, $len - 2, 'UTF-8');
        return $front . '**';
    }
    // ถ้าความยาว <= 2 ก็แสดงเป็น **
    return '**';
}

function maskEmail($email) {
    // แยกชื่อกับโดเมน
    $parts = explode('@', $email, 2);
    if (count($parts) !== 2) return $email; // ถ้าไม่ใช่อีเมลปกติ

    $name = $parts[0];
    $domain = $parts[1];

    $len = mb_strlen($name, 'UTF-8');
    if ($len <= 3) {
        // ถ้าสั้นเกินไป ให้โชว์ตัวแรก แล้วตามด้วย ***
        $visible = mb_substr($name, 0, 1, 'UTF-8');
    } else {
        // โชว์ทุกตัว ยกเว้น 3 ตัวท้าย
        $visible = mb_substr($name, 0, $len - 3, 'UTF-8');
    }

    return $visible . '***@' . $domain;
}