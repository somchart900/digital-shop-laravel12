<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    use DatabaseTransactions; // rollback อัตโนมัติ

    public function test_request_url_login()
    {
        // request ไปยัง route ที่ต้องการ
        $response = $this->get('/auth/login', []);
        $response->assertStatus(200);
    }

    public function test_event_login_success()
    {
        $user = User::factory()->create([
            'username' => 'test',
            'password' => Hash::make('test'),
        ]);
        // request ไปยัง route ที่ต้องการ
        $response = $this->withSession([])->post('/auth/login-process', [
            'username' => $user->username,
            'password' => 'test',
            '_token' => csrf_token(),
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('loginlogs', [
            'user_id' => $user->id,  // ใช้ id
        ]);

        $this->assertAuthenticatedAs($user);
    }

    public function test_event_login_fail()
    {
        $user = User::factory()->create([
            'username' => 'test',
            'password' => Hash::make('test'),
        ]);
        // request ไปยัง route ที่ต้องการ
        $response = $this->withSession([])->post('/auth/login-process', [
            'username' => $user->username,
            'password' => 'wrong', // รหัสผ่านผิด
            '_token' => csrf_token(),
        ]);
        // ตรวจสอบว่า session มี error key 'username' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง'
        $response->assertSessionHasErrors([
            'username' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง',
        ]);

        $response = $this->withSession([])->post('/auth/login-process', [
            'username' => 'te', // ตรวจสอบ username ต้องมีความยาวอย่างน้อย 4 ตัวอักษร
            'password' => 'test',
            '_token' => csrf_token(),
        ]);
        // ตรวจสอบว่า session มี error key 'username'
        $response->assertSessionHasErrors(['username']); // ตรวจสอบ username ต้องมีความยาวอย่างน้อย 4 ตัวอักษร

    }

}
