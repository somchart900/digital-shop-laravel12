<?php

namespace Tests\Feature\Auth;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisterTest extends TestCase
{
    use DatabaseTransactions; // rollback อัตโนมัติ

    public function test_request_url_register()
    {
        // request ไปยัง route ที่ต้องการ
        $response = $this->get('/auth/register', []);
        $response->assertStatus(200);
    }

    public function test_event_register_success()
    {

        $email = '2g6V4@example.com';
        $user = 'test';
        // request ไปยัง route ที่ต้องการ
        $response = $this->withSession([])->post('/auth/register-process', [
            'username' => $user,
            'email' => $email,
            'password' => '123456',
            'password_confirmation' => '123456',
            '_token' => csrf_token(),
        ]);
        $response->assertSessionHas('success'); // ตรวจสอบความถูกต้อง
        $this->assertDatabaseHas('users', [
            'username' => $user,
            'email' => $email,
        ]); // ตรวจสอบความถูกต้อง
        $id = User::where('email', $email)->first()->id;
        $this->assertDatabaseHas('activitylogs', [
            'user_id' =>  $id,
            'description' => 'สมัครสมาชิกเรียบร้อย',
        ]); // ตรวจสอบความถูกต้อง

        $this->assertAuthenticatedAs(User::where('email', $email)->first()); // ตรวจสอบความถูกต้อง
    }

    public function test_event_register_fail()
    {
        User::factory()->create([
            'username' => 'test',
            'password' => Hash::make('test'),
        ]);
        $email = '2g6V4@example.com';
        $user = 'test'; // username ซ้ํา
        // request ไปยัง route ที่ต้องการ
        $response = $this->withSession([])->post('/auth/register-process', [
            'username' => $user,
            'email' => $email,
            'password' => '123456',
            'password_confirmation' => '123456',
            '_token' => csrf_token(),
        ]);
        $response->assertSessionHasErrors([]); // ตรวจสอบความผิดพลาด username already exists

        $response = $this->withSession([])->post('/auth/register-process', [
            'username' => '12', // username ต้องมีความยาวมากกว่า 4 ตัวอักษร
            'email' => 'test@test.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            '_token' => csrf_token(),
        ]);
        $response->assertSessionHasErrors([]); // ตรวจสอบความผิดพลาด validation error username must be at least 4 characters
    }
}
