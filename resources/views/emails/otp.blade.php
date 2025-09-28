<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>รหัส OTP ของคุณ - {{ $webname->value ?? 'Laravel12' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
        }
        .header {
            background-color: #232f3e; /* Amazon dark blue */
            padding: 20px 30px;
            color: #ffffff;
            font-size: 24px;
            text-align: center;
        }
        .content {
            padding: 30px;
            color: #333333;
            font-size: 16px;
            line-height: 1.6;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #e47911; /* Amazon orange */
            text-align: center;
            margin: 20px 0;
            padding: 15px 0;
            border: 2px dashed #eeeeee;
            border-radius: 5px;
            background-color: #fafafa;
        }
        .footer {
            background-color: #f8f8f8;
            padding: 20px 30px;
            font-size: 12px;
            color: #777777;
            text-align: center;
            border-top: 1px solid #eeeeee;
        }
        .disclaimer {
            font-size: 11px;
            color: #aaaaaa;
            margin-top: 15px;
        }
        a {
            color: #007185; /* Amazon link blue */
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            {{ $webname->value ?? 'Laravel12' }}
        </div>
        <div class="content">
            <p>สวัสดี,</p>
            <p>เราได้รับคำขอสำหรับรหัสยืนยันแบบใช้ครั้งเดียว (OTP) ของคุณ โปรดใช้รหัสต่อไปนี้เพื่อดำเนินการให้เสร็จสมบูรณ์:</p>
            <div class="otp-code">{{ $otp }}</div>
            <p>โปรดทราบว่ารหัสนี้จะ<b>หมดอายุใน 15 นาที</b> เพื่อความปลอดภัยของคุณ โปรดอย่าแชร์รหัสนี้กับผู้อื่น</p>
        </div>
        <div class="footer">
            <p>หากคุณไม่ได้ร้องขอรหัสนี้ หรือมีข้อสงสัยใดๆ โปรด <a href="{{ route('home') }}">ติดต่อฝ่ายสนับสนุนลูกค้า</a>ของเราทันที</p>
            <p class="disclaimer">
                อีเมลนี้ถูกส่งจาก {{ $webname->value ?? 'Laravel12' }} <br>
                โปรดอย่าตอบกลับอีเมลฉบับนี้โดยตรง
            </p>
        </div>
    </div>
</body>
</html>