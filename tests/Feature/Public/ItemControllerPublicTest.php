<?php

namespace Tests\Feature\Public;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Item;
use App\Models\Credit;
use App\Models\Order;

class ItemControllerPublicTest extends TestCase
{
    use DatabaseTransactions;

    public function test_item_order(): void
    {
        $user = User::factory()->create([
            'level' => 1,
            'username' => 'test',
        ]);
        $this->actingAs($user);
        Credit::create([
            'user_id' => $user->id,
            'amount' => 10000
        ]);
        $this->assertDatabaseHas('credits', ['user_id' => $user->id, 'amount' => 10000]);
        $category = Category::create(
            [
                'name' => 'test',
                'description' => 'test',
                'is_featured' => 1
            ]
        );
        $this->assertDatabaseHas('categories', [
            'name' => 'test',
            'description' => 'test',
            'is_featured' => 1,
        ]);
        // ทดสอบ route product;
        $this->get(route('product', ['category_name' => 'test']))->assertOk();

        $product = Product::create(
            [
                'name' => 'test',
                'category_id' => $category->id,
                'description' => 'test',
                'is_featured' => 1,
                'price' => '1000',
                'img_link' => 'test.jpg',
            ]
        );
        $this->assertDatabaseHas('products', [
            'name' => 'test',
            'category_id' => $category->id,
            'description' => 'test',
            'is_featured' => 1,
            'price' => '1000',
            'img_link' => 'test.jpg',
        ]);
        for ($i = 0; $i < 10; $i++) {
            $item = Item::create(
                [
                    'name' => 'test',
                    'category_id' => $category->id,
                    'product_id' => $product->id,
                    'price' => '1000',
                    'code' => 'test',
                    'description' => 'test',
                    'img_link' => 'test.jpg',
                    'youtube' => 'test',
                    'article' => 'test',

                ]
            );
        }
        // ทดสอบ route item
        $this->get(route('item', ['category_name' => 'test', 'product_name' => 'test']))->assertOk();

        $this->assertDatabaseHas('items', [
            'name' => 'test',
            'category_id' => $category->id,
            'product_id' => $product->id,
            'price' => '1000',
            'code' => 'test',
            'description' => 'test',
            'img_link' => 'test.jpg',
            'youtube' => 'test',
            'article' => 'test',
        ]);
        // ทดสอบ add order
        $response = $this->withSession([])->post('/orderadd', [
            'id' => $product->id,
            'total' => 1,
            '_token' => csrf_token(),
        ]);

        $response->assertJson([
            'success' => true,
        ]);
        $this->assertDatabaseHas('orders', [
            'name' => 'test',
            'category_id' => $category->id,
            'product_id' => $product->id,
            'price' => '1000',
            'code' => 'test',
            'description' => 'test',
            'img_link' => 'test.jpg',
            'youtube' => 'test',
            'article' => 'test',
        ]);
    }
}
