<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = request('search');
        $category = Category::where('name', $request->category_name)->firstOrFail();
        $products = Product::withCount('item', 'order')
            ->where('category_id', $category->id)
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('order_count', 'desc')
            ->paginate(12);

        $title = 'หมวดหมู่สินค้า';
        return view('publics.product', compact(
            'category',
            'products',
            'title'
        ));
    }
}
