<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class WalletControllerTest extends TestCase
{
    use DatabaseTransactions;
    public function test_redeem_frist_topup(): void
    {
        $user = User::factory()->create(
            [
                'username' => 'test',
            ]
        );
        $this->actingAs($user);
        $this->get(route('user.topup'))->assertOk();
        $reponse = $this->withSession([])->post(route('user.topup.redeem'), [
            'mobile' => '0812345678',
            'truemoney' => 'https://gift.truemoney.com/campaign/?v=1234567890abcdef',
            'mok' => '100',
            '_token' => csrf_token(),
        ]);
        $reponse->assertRedirect(route('user.topup'));
        $reponse->assertSessionHas([
            'success' => true,
            'message' => 'เติมเงินเรียบร้อยแล้ว',
        ]);
        $this->assertDatabaseHas('topups', [
            'user_id' => $user->id,
            'channel' => 'truemoney',
            'amount' => '100',
        ]);
        $this->assertDatabaseHas('credits', [
            'user_id' => $user->id,
            'amount' => '100',
        ]);
        $this->assertDatabaseHas('activitylogs', [
            'user_id' => $user->id,
            'action' => 'เติมเงิน',
            'description' => 'เติมเงิน 100 บาท ผ่าน Truemoney',
        ]);
    }

    public function test_redeem_second_topup(): void
    {
        $user = User::factory()->create(
            [
                'username' => 'test',
            ]
        );
        $this->actingAs($user);
        $user->credit()->create(['amount' => 100]);
        $this->get(route('user.topup'))->assertOk();
        $reponse = $this->withSession([])->post(route('user.topup.redeem'), [
            'mobile' => '0812345678',
            'truemoney' => 'https://gift.truemoney.com/campaign/?v=1234567890abcdef',
            'mok' => '100',
            '_token' => csrf_token(),
        ]);
        $reponse->assertRedirect(route('user.topup'));
        $reponse->assertSessionHas([
            'success' => true,
            'message' => 'เติมเงินเรียบร้อยแล้ว',
        ]);
        $this->assertDatabaseHas('topups', [
            'user_id' => $user->id,
            'channel' => 'truemoney',
            'amount' => '100',
        ]);
        $this->assertDatabaseHas('credits', [
            'user_id' => $user->id,
            'amount' => (100 + 100),
        ]);

        $this->assertDatabaseHas('activitylogs', [
            'user_id' => $user->id,
            'action' => 'เติมเงิน',
            'description' => 'เติมเงิน 100 บาท ผ่าน Truemoney',
        ]);
    }

    public function test_checkslip_success(): void
    {
        $user = User::factory()->create(
            [
                'username' => 'test',
            ]
        );
        $this->actingAs($user);
        $this->get(route('user.topup'))->assertOk();

        Storage::fake('public');

        $file = UploadedFile::fake()->image('test.jpg');


        $reponse = $this->withSession([])->post(route('user.topup.checkslip'), [
            'image' => $file,
            'qrText' => 'saaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
            'mok' => '100',
            '_token' => csrf_token(),
        ]);
        $files = Storage::disk('public')->files('uploads/slips');
        // assert ว่า files มีชื่อไฟล์ที่ลงท้ายด้วย 'test.jpg'
        $this->assertTrue(collect($files)->contains(fn($f) => str_ends_with($f, 'test.jpg')));


        $reponse->assertRedirect(route('user.topup'));
        $reponse->assertSessionHas([
            'success' => true,
            'message' => 'เติมเงินเรียบร้อยแล้ว',
        ]);
        $this->assertDatabaseHas('topups', [
            'user_id' => $user->id,
            'channel' => 'checkslip',
            'amount' => '100',
        ]);
        $this->assertDatabaseHas('credits', [
            'user_id' => $user->id,
            'amount' => '100',
        ]);
        $this->assertDatabaseHas('activitylogs', [
            'user_id' => $user->id,
            'action' => 'เติมเงิน',
        ]);

        // ลบไฟล์ test.jpg ที่อยู่ใน public ด้วยเพราะเราก้อปปี้ไปใช้งาน  (ไม่ได้ใช้ php artisan storage:link)
        $files = File::files(public_path('uploads/slips'));
        foreach ($files as $file) {
            if (str_ends_with($file->getFilename(), 'test.jpg')) {
                File::delete($file->getPathname());
            }
        }
    }

      public function test_checkslip_fail(): void
    {
        $user = User::factory()->create(
            [
                'username' => 'test',
            ]
        );
        $this->actingAs($user);
        $this->get(route('user.topup'))->assertOk();

        Storage::fake('public');

        $file = UploadedFile::fake()->image('test.jpg');


        $reponse = $this->withSession([])->post(route('user.topup.checkslip'), [
            'image' => $file,
            'qrText' => '',
            '_token' => csrf_token(),
        ]);
  
        $reponse->assertRedirect(route('user.topup'));
        $reponse->assertSessionHas([
            'success' => false,
        ]);
         // ลบไฟล์ test.jpg ที่อยู่ใน public ด้วยเพราะเราก้อปปี้ไปใช้งาน  (ไม่ได้ใช้ php artisan storage:link)
        $files = File::files(public_path('uploads/slips'));
        foreach ($files as $file) {
            if (str_ends_with($file->getFilename(), 'test.jpg')) {
                File::delete($file->getPathname());
            }
        }       
    }
}
