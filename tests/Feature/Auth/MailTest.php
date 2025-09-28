<?php

namespace Tests\Feature\Auth;

use Tests\TestCase; // 
use App\Http\Controllers\MailController;


class MailTest extends TestCase
{
    public function test_send_email_returns_true()
    {
        $to_email = 'somchart900@gmail.com';
        $otp = '123654';
        $controller = new MailController();
        $result = $controller->sendEmail($to_email, $otp, 'true');
        $this->assertTrue($result);
        $result = $controller->sendEmail($to_email, $otp, 'false');
        $this->assertFalse($result);
    }
}
