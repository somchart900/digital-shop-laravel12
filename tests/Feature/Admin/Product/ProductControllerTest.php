<?php

namespace Tests\Feature\Admin\Product;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\File;


class ProductControllerTest extends TestCase
{
    use DatabaseTransactions;


    public function test_product_create_success(): void
    {
        $user = User::factory()->create(['level' => 99]);
        $this->actingAs($user);
        $category = Category::create([
            'name' => 'Test Category',
            'is_featured' => 1
        ]);
        Storage::fake('public');

        $file = UploadedFile::fake()->image('test.jpg');

        $response = $this->withSession([])->post('/admin/setting/product/create', [
            'name' => 'test2',
            'category_id' => $category->id,
            'price' => '1000',
            'description' => 'test2',
            'is_featured' => 1,
            'img_link' => $file,
            '_token' => csrf_token(),
        ]);

        $response->assertSessionHas('success');
        $response->assertSessionHas('message', 'เพิ่มชนิดสินค้าสำเร็จ');

        // ตรวจสอบว่ามีไฟล์อยู่ใน storage
        $files = Storage::disk('public')->files('uploads/products');

        // assert ว่า files มีชื่อไฟล์ที่ลงท้ายด้วย 'test.jpg'
        $this->assertTrue(collect($files)->contains(fn($f) => str_ends_with($f, 'test.jpg')));

        $this->assertDatabaseHas('products', [
            'name' => 'test2',
            'category_id' => $category->id,
            'description' => 'test2',
            'is_featured' => 1,
            'price' => '1000'
        ]);
        // ลบไฟล์ test.jpg ที่อยู่ใน public ด้วยเพราะเราก้อปปี้ไปใช้งาน  (ไม่ได้ใช้ php artisan storage:link)
        $files = File::files(public_path('uploads/products'));
        foreach ($files as $file) {
            if (str_ends_with($file->getFilename(), 'test.jpg')) {
                File::delete($file->getPathname());
            }
        }
    }

    public function test_product_delete_success(): void
    {
        $user = User::factory()->create(['level' => 99]);
        $this->actingAs($user);

        $category = Category::create(
            [
                'name' => 'test',
                'description' => 'test',
                'is_featured' => 1
            ]
        );
        $product = Product::create(
            [
                'name' => 'test',
                'category_id' => $category->id,
                'price' => '1000',
                'description' => 'test',
                'is_featured' => 1
            ]
        );
        $response = $this->withSession([])->post(
            '/admin/setting/product/delete',
            [
                'id' => $product->id,
                '_token' => csrf_token(),
            ]
        );
        $response->assertJson(
            [
                'success' => true,
                // 'message' => 'ลบหมวดหมู่สินค้าสำเร็จ'
            ]
        );
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
    public function test_product_delete_fail(): void
    {
        $user = User::factory()->create(['level' => 1]);
        $this->actingAs($user);

        $category = Category::create(
            [
                'name' => 'test',
                'description' => 'test',
                'is_featured' => 1

            ]
        );
        $product = Product::create(
            [
                'name' => 'test',
                'category_id' => $category->id,
                'price' => '1000',
                'description' => 'test',
                'is_featured' => 1
            ]
        );
        $response = $this->withSession([])->post(
            '/admin/setting/product/delete',
            [
                'id' => $product->id,
                '_token' => csrf_token(),
            ]
        );
        $response->assertSessionHas(['error' => true, 'message' => 'เฉพาะผู้ดูแลระบบเท่านั้น']);
    }
}
