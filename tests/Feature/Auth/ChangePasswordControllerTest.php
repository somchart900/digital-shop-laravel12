<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ChangePasswordControllerTest extends TestCase
{
    use DatabaseTransactions; // rollback ทุกครั้ง
    /**
     * A basic feature test example.
     */

    public function test_change_password_success(): void
    {
        $user = User::factory()->create([
            'username' => 'testww',
            'password' => bcrypt('123456'),
        ]);

        // login ให้ user ก่อน
        $this->actingAs($user);

        $response = $this->withSession([])->post('/auth/change-password', [
            'current_password' => '123456',
            'new_password' => '234567',
            'new_password_confirmation' => '234567',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect('/auth/login'); // หลังเปลี่ยนรหัสผ่านเสร็จมัน logout ออก
        $response->assertSessionHas('success', true);
        $response->assertSessionHas('message', 'เปลี่ยนรหัสผ่านเรียบร้อย');

        // ตรวจสอบว่า password เปลี่ยนจริง
        $this->assertTrue(Hash::check('234567', $user->fresh()->password));
    }

    public function test_change_password_error(): void
    {
        $user = User::factory()->create([
            'username' => 'testww',
            'password' => bcrypt('123456'),
        ]);

        // login ให้ user ก่อน
        $this->actingAs($user);

        $response = $this->withSession([])->post('/auth/change-password', [
            'current_password' => '1234564', // รหัสผ่านผิด
            'new_password' => '234567',
            'new_password_confirmation' => '234567',
            '_token' => csrf_token(),
        ]);


        $response->assertSessionHas('error', true);
        $response->assertSessionHas('message', 'รหัสผ่านเดิมไม่ถูกต้อง');

      
    }
}
