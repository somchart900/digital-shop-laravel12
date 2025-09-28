<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $category_id = $request->category_id;
        $product_id = $request->product_id;

        $search = $request->input('search');
        $product = Product::find($product_id);

        $items = Item::when($product_id, function ($query, $product_id) {
            return $query->where('product_id', $product_id);
        })
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admins.item', compact('category_id', 'product', 'items',));
    }

    public function create(Request $request)
    {
        if (Auth::user()->level != 99) {
            Log::error(Auth::user()->username . 'คุณไม่มีสิทธิ์เพิ่มข้อมูลสินค้า');
            return redirect()->back()->with('error', true)->with('message', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|numeric',
            'product_id' => 'required|numeric',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'code' => 'required|string',
            'img_link' => 'required',
            'total' => 'required|numeric|min:1|max:500',
        ]);

        if ($validator->fails()) {
            Log::error($validator->errors());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        for ($i = 0; $i < $request->total; $i++) {
            $item = Item::create([
                'category_id' => $request->category_id,
                'product_id' => $request->product_id,
                'name' => $request->name,
                'price' => $request->price,
                'code' => $request->code,
                'img_link' => $request->img_link,
                'description' => $request->description,
                'article' => $request->article,
                'youtube' => $request->youtube,
                'external_link' => $request->external_link
            ]);
        }
        // ลบ cache
        Cache::forget('featured_categories');
        Cache::forget('featured_products');
        return redirect()->back()->with('success', true)->with('message', 'สร้างสินค้าสําเร็จ');
    }

    public function delete(Request $request)
    {
        // ตรวจสอบสิทธิ์
        if (Auth::user()->level != 99) {
            Log::error(Auth::user()->username . 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
            return  json_encode([
                'success' => false,
                'message' => 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้'
            ]);
        }
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            Log::error($validator->errors());
            return json_encode([
                'success' => false,
                'message' => 'ไม่สามารถลบชนิดสินค้าได้'
            ]);
        }

        $item = Item::find($request->id)->delete();
        if ($item) {
            // ลบ cache
            Cache::forget('featured_categories');
            Cache::forget('featured_products');
            return json_encode([
                'success' => true,
                'message' => 'ลบชนิดสินค้าสำเร็จ'
            ]);
        } else {
            Log::error('ลบชนิดสินค้าไม่สำเร็จ');
            return json_encode([
                'success' => false,
                'message' => 'ไม่สามารถลบชนิดสินค้าได้'
            ]);
        }
    }
}
