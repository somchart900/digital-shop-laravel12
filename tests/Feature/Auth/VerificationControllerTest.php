<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Http\Controllers\Auth\VerificationController;
use App\Models\User;
use App\Models\Otp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class VerificationControllerTest extends TestCase
{
    use DatabaseTransactions; // rollback อัตโนมัติ

    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new VerificationController();
    }

    public function test_verification_request_returns_true()
    {
        $user = User::factory()->create(['email' => 'xxx@3bbmail.com']);
        // login ให้ user ก่อน
        $this->actingAs($user);
        // request ไปยัง route ที่ต้องการ
        $response = $this->withSession([])->post('/auth/verification-request', [
            'email' => $user->email,
            '_token' => csrf_token(),
            'mok' => 'true' // สําหรับ test success
        ]);
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('otps', [
            'user_id' => $user->id,  // ใช้ id
        ]);
    }
    public function test_verification_request_returns_false()
    {
        $user = User::factory()->create(['email' => 'xxx@3bbmail.com']);
        // login ให้ user ก่อน
        $this->actingAs($user);
        // request ไปยัง route ที่ต้องการ
        $response = $this->withSession([])->post('/auth/verification-request', [
            'email' => $user->email,
            '_token' => csrf_token(),
            'mok' => 'false' // สําหรับ test fail
        ]);
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('otps', [
            'user_id' => $user->id,  // ใช้ id
        ]);
    }

    public function test_verification_otp_process()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'email_verified_at' => null,
        ]);
        // login ให้ user ก่อน
        $this->actingAs($user);
        $otp = Otp::create([
            'user_id' => $user->id,
            'otp' => '123456',
            'otp_expired' => now()->addMinutes(15),
        ]);
        $this->assertDatabaseHas('otps', [
            'user_id' => $user->id,
        ]);
        // สำเร็จ
        $response = $this->withSession([])->post('/auth/verification-process', [
            '_token' => csrf_token(),
            'otp' => '123456',
        ]);
        $response->assertSessionHas('success');
    }
}
