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
use App\Models\Item;
use Illuminate\Support\Facades\File;


class ItemControllerTest extends TestCase
{
    use DatabaseTransactions;


    public function test_item_create_success(): void
    {
        $user = User::factory()->create(['level' => 99]);
        $this->actingAs($user);

        $cat_id = Category::create(
            [
                'name' => 'test',
                'description' => 'test',
                'is_featured' => 1
            ]
        );
        $this->assertDatabaseHas('categories', [
            'name' => 'test',
        ]);
        $pro_id = Product::create(
            [
                'name' => 'test',
                'category_id' => $cat_id->id,
                'description' => 'test',
                'is_featured' => 1,
                'price' => '1000',
            ]
            );
  
        $response = $this->withSession([])->post('/admin/setting/item/create', [
            'name' => 'test2',
            'category_id' => $cat_id->id,
            'product_id' => $pro_id->id,
            'price' => '1000',
            'description' => 'test2',
            'is_featured' => 1,
            'img_link' => 'test.jpg',
            'total' => 1,
            'code' => 'test',
            '_token' => csrf_token(),
        ]);

        $response->assertSessionHas('success');
        $response->assertSessionHas('message', 'สร้างสินค้าสําเร็จ');


        $this->assertDatabaseHas('items', [
            'name' => 'test2',
            'category_id' => $cat_id->id,
            'product_id' => $pro_id->id,
            'price' => '1000',
            'description' => 'test2',
            'img_link' => 'test.jpg',
            'code' => 'test',
            
        ]);

        
       
    }

    public function test_item_delete_success(): void
    {
        $user = User::factory()->create(['level' => 99]);
        $this->actingAs($user);
        $cat_id = Category::create(
            [
                'name' => 'test',
                'description' => 'test',
                'is_featured' => 1
            ]
        );
        
        $pro_id = Product::create(
            [
                'name' => 'test',
                'category_id' => $cat_id->id,
                'description' => 'test',
                'price' => '1000',
                'is_featured' => 1
            ]
            );
       $this->withSession([])->post('/admin/setting/item/create', [
            'name' => 'test2',
            'category_id' => $cat_id->id,
            'product_id' => $pro_id->id,
            'price' => '1000',
            'description' => 'test2',
            'is_featured' => 1,
            'img_link' => 'test.jpg',
            'total' => 1,
            'code' => 'test',
            '_token' => csrf_token(),
        ]);
  
        $item = Item::where('category_id', $cat_id->id)->first();
        $response = $this->withSession([])->post(
            '/admin/setting/item/delete',
            [
                'id' => $item->id,
                '_token' => csrf_token(),
            ]
        );
        $response->assertJson(
            [
                'success' => true,
                // 'message' => 'ลบหมวดหมู่สินค้าสำเร็จ'
            ]
        );
        $this->assertDatabaseMissing('items', ['id' => $item->id]);
    }
   
}
