<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
class CategoryController extends Controller
{
    public function index()
    {    
        $serch = request('search');
        $categories = Category::WithCount('product')->When($serch, function ($query, $serch) {
            return $query->where('name', 'like', "%{$serch}%");
        })->paginate(12);
        $title = 'หมวดหมู่';
        return view('publics.category', compact('title', 'categories', 'serch'));
    }
}
