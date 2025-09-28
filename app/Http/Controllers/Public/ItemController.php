<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Credit;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $category_name = $request->category_name;
        $product_name = $request->product_name;

        $count = Item::where('name', $product_name)->count();
        $items = Item::where('name', $product_name)->first();
        
     
        return view('publics.item', compact('items', 'category_name', 'product_name', 'count'));
    }
    public function add(Request $request)
    {
        if (Auth::guest()) {
            return json_encode(
                [
                    'success' => false,
                    'message' => 'กรุณาเข้าสู่ระบบ',
                ]
            );
        }
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'total' => 'required|numeric|min:1|max:500',
        ]);
        if ($validator->fails()) {
            Log::error('itemcontroller method add', $validator->errors());
            return json_encode([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }
        $credit = Credit::where('user_id', Auth::user()->id)->first();
        $amount = $credit->amount ?? 0;
        if ($amount < 1) {
            return json_encode(
                [
                    'success' => false,
                    'message' => 'เครดิตไม่เพียงพอ',
                ]
            );
        }

        $item = Item::Where('product_id', $request->id)->first();
        if (!$item) {
            Log::error('itemcontroller method add', 'ไม่พบสินค้า');
            return json_encode([
                'success' => false,
                'message' => 'ไม่พบสินค้า',
            ]);
        }
        $totalprice = $request->total * $item->price;
        if ($totalprice > $amount) {
            return json_encode(
                [
                    'success' => false,
                    'message' => 'เครดิตไม่เพียงพอ',
                ]
            );
        }
        for ($i = 0; $i < $request->total; $i++) {
            $item = Item::Where('product_id', $request->id)->first();
            Order::create([
                'user_id' => Auth::user()->id,
                'username' => Auth::user()->username,
                'name' => $item->name,
                'category_id' => $item->category_id,
                'product_id' => $item->product_id,
                'price' => $item->price,
                'code' => $item->code,
                'img_link' => $item->img_link,
                'article' => $item->article,
                'youtube' => $item->youtube,
                'description' => $item->description,
                'article' => $item->article,
            ]);
            $item->delete();
        }
        $credit->amount = $amount - $totalprice;
        $credit->save();

        Cache::forget('recent_orders');
        Cache::forget('featured_categories');
        Cache::forget('featured_products');

        return json_encode(
            [
                'success' => true,
                'message' => 'สินค้าถูกเพิ่มเข้าระบบแล้ว',
            ]
        );
    }
}
