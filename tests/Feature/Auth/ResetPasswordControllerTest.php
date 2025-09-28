<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Models\User;
use App\Models\Otp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ResetPasswordControllerTest extends TestCase
{
    use DatabaseTransactions; // rollback อัตโนมัติ

    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new ResetPasswordController();
    }


    public function test_otp_verify_checks_correctly()
    {
        $user = User::factory()->create();
        $otp = Otp::create([
            'user_id' => $user->id,
            'otp' => '123456',
            'otp_expired' => now()->addMinutes(15),
        ]);

        $this->assertTrue($this->controller->otp_verify('123456'));
        $this->assertFalse($this->controller->otp_verify('000000'));

        // หมดอายุ
        $otp->otp_expired = now()->subMinutes(1);
        $otp->save();
        $this->assertFalse($this->controller->otp_verify('123456'));
    }


    public function test_reset_password_updates_password_or_fails()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);

        $result = $this->controller->reset_password('test@example.com', 'newpassword');
        $this->assertTrue($result);

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword', $user->password));

        // ล้มเหลว
        $resultFail = $this->controller->reset_password('noone@example.com', 'pass');
        $this->assertFalse($resultFail);
    }
}
