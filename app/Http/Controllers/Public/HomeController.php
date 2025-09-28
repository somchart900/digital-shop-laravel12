<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    // public function index()
    // {
    //     $recentOrders =  Order::latest()->take(6)->get();
    //     $categories = Category::withCount('product')->where('is_featured', 1)->limit(6)->get();
    //     $products = Product::withCount(['item', 'order'])
    //         ->where('is_featured', 1)
    //         ->orderBy('order_count', 'desc')
    //         ->limit(8)
    //         ->get();
    //     $title = 'หน้าหลัก';
    //     return view('publics.home', compact(
    //         'categories',
    //         'products',
    //         'title',
    //         'recentOrders'
    //     ));
    // }
    public function index()
    {
        // แคช recent orders 6 อันล่าสุด
        $recentOrders = Cache::rememberForever('recent_orders', function () {
            return Order::latest()->take(6)->get();
        });

        // แคช featured categories
        $categories = Cache::rememberForever('featured_categories', function () {
            return Category::withCount('product')
                ->where('is_featured', 1)
                ->limit(6)
                ->get();
        });

        // แคช featured products
        $products = Cache::rememberForever('featured_products', function () {
            return Product::withCount(['item', 'order'])
                ->where('is_featured', 1)
                ->orderBy('order_count', 'desc')
                ->limit(8)
                ->get();
        });

        $title = 'หน้าหลัก';

        return view('publics.home', compact(
            'categories',
            'products',
            'title',
            'recentOrders'
        ));
    }
}
