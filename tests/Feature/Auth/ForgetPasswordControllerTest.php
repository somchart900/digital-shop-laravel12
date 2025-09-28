<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Models\User;
use App\Models\Otp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ForgetPasswordControllerTest extends TestCase
{
    use DatabaseTransactions; // rollback อัตโนมัติ

    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new ForgetPasswordController();
    }


    public function test_user_info_by_email_returns_user_or_false()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);
        // สำเร็จ
        $found = $this->controller->user_info_by_email('test@example.com');
        $this->assertEquals($user->id, $found->id);

        // ล้มเหลว
        $notFound = $this->controller->user_info_by_email('noone@example.com');
        $this->assertNull($notFound);
    }


    public function test_make_otp_returns_true_or_false()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);

        // สำเร็จ
        $result = $this->controller->make_otp('test@example.com', '123456');
        $this->assertTrue($result);
        $this->assertDatabaseHas('otps', ['user_id' => $user->id]);

        // ล้มเหลว
        $resultFail = $this->controller->make_otp('noone@example.com', '123456');
        $this->assertFalse($resultFail);
    }

    public function test_send_otp_returns_false()
    {
        $user = User::factory()->create(['email' => 'xxx@3bbmail.com']);


        //  request ไปยัง route ที่ต้องการ
        $response = $this->withSession([])->post('/auth/forget-password-process', [
            'email' => $user->email,
            '_token' => csrf_token(),
            'mok' => 'false'   // สําหรับ test fail
        ]);
        $response->assertSessionHas('error');
    }

    public function test_send_otp_returns_true()
    {
        $user = User::factory()->create(['email' => 'xxx@3bbmail.com']);

        // request ไปยัง route ที่ต้องการ
        $response = $this->withSession([])->post('/auth/forget-password-process', [
            'email' => $user->email,
            '_token' => csrf_token(),
            'mok' => 'true' // สําหรับ test success
        ]);
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('otps', [
            'user_id' => $user->id,  // ใช้ id
        ]);
    }
}
