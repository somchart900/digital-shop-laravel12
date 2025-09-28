<?php

namespace Tests\Feature\Admin\Setting;

use App\Models\User;
use App\Models\Setting;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SettingControllerTest extends TestCase
{
    use DatabaseTransactions; // rollback อัตโนมัติ
    /**
     * A basic feature test example.
     */
    public function test_event_delete_success(): void
    {
        $admin = User::factory()->create(['level' => 99]); // ผู้ดูแล
        $name = 'test';
        $id = Setting::create([
            'name' => $name,
            'value' => '1'
        ]);
        $response = $this->actingAs($admin)->withSession([])->post('/admin/setting/delete', [
            'id' => $id->id,
            '_token' => csrf_token(),
        ]);

        $response->assertJson([
            'status' => 'success'
        ]);
    }
    public function test_event_delete_fail(): void
    {
        $admin = User::factory()->create(['level' => 1]); // ไม่ใช่ผู้ดูแล
        $name = 'test';
        $id = Setting::create([
            'name' => $name,
            'value' => '1'
        ]);
        $response = $this->actingAs($admin)->withSession([])->postJson('/admin/setting/delete', [
            'name' => $id->id,
            '_token' => csrf_token(),
        ]);

        $response->assertjson(['success' => false, 'message' => 'เฉพาะผู้ดูแลระบบเท่านั้น']);  // ไม่ใช่ผู้ดูแล


    }
    public function test_event_update_success(): void
    {
        $admin = User::factory()->create(['level' => 99]); // ผู้ดูแล
        $name = 'wabname';
        $value = 'test';
        $response = $this->actingAs($admin)->withSession([])->post('/admin/setting/update', [
            'name' => $name,
            'value' => $value,
            '_token' => csrf_token(),
        ]);

        $response->assertJson([
            'status' => 'success'
        ]); // ผู้ดูแล สําเร็จ
    }

    public function test_event_update_fail(): void
    {
        $admin = User::factory()->create(['level' => 1]); // ไม่ใช่ผู้ดูแล
        $name = 'wabname';
        $value = 'test';
        $response = $this->actingAs($admin)->withSession([])->postJson('/admin/setting/update', [
            'name' => $name,
            'value' => $value,
            '_token' => csrf_token(),
        ]);

        $response->assertjson(['success' => false, 'message' => 'เฉพาะผู้ดูแลระบบเท่านั้น']); // ไม่ใช่ผู้ดูแล


    }
}
