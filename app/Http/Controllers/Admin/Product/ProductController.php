<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $category_id = $request->category_id;
        $search = $request->input('search');

        $products = Product::withCount('item') // นับจำนวน items ของแต่ละ product
            ->when($category_id, function ($query, $category_id) {
                return $query->where('category_id', $category_id);
            })
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admins.product', compact('category_id', 'products'));
    }

    public function create(Request $request)
    {
        // ตรวจสอบสิทธิ์
        if (Auth::user()->level != 99) {
            return redirect()->back()->with('error', true)->with('message', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255|unique:products,name',
            'price'       => 'required|numeric',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'is_featured' => 'required',
            'img_link'    => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($validator->fails()) {
            Log::error($validator->errors());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $filename = null;

        if ($request->hasFile('img_link')) {
            $file = $request->file('img_link');
            $filename = time() . '_' . $file->getClientOriginalName();

            try {
                // ใช้ Storage
                Storage::disk('public')->putFileAs('uploads/products', $file, $filename);
                if (!file_exists(public_path('uploads/products/'))) {
                    mkdir(public_path('uploads/products/'), 0777, true); // 0777 = สิทธิ์เต็ม, true = สร้างซ้อนกันได้
                }
                copy(Storage::disk('public')->path('uploads/products/' . $filename), public_path('uploads/products/' . $filename));
            } catch (\Exception $e) {
                Log::error('ไม่สามารถอัปโหลดไฟล์ได้: ' . $e->getMessage());
                return redirect()->back()->with('error', true)->with('message', 'ไม่สามารถอัปโหลดไฟล์ได้');
            }
        }

        // สร้าง 
        $name = preg_replace('/\s+/', '-', $request->name); // แทนที่ช่องว่างด้วย -
        $product = Product::create([
            'name'        => $name,
            'category_id' => $request->category_id,
            'price'       => $request->price,
            'description' => $request->description,
            'is_featured' => $request->is_featured,
            'img_link'    => $filename,
        ]);

        if ($product) {
            // ลบ cache
            Cache::forget('featured_categories');
            Cache::forget('featured_products');
            return redirect()->back()->with('success', true)->with('message', 'เพิ่มชนิดสินค้าสำเร็จ');
        } else {
            Log::error('เพิ่มชนิดสินค้าไม่สำเร็จ');
            return redirect()->back()->with('error', true)->with('message', 'ไม่สามารถเพิ่มชนิดสินค้าได้');
        }
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

        $product = Product::find($request->id);

        if ($product) {
            // ลบ cache
            Cache::forget('featured_categories');
            Cache::forget('featured_products');
            // ลบไฟล์รูปภาพ
            if ($product->img_link) {
                Storage::disk('public')->delete('uploads/products/' . $product->img_link);
                unlink(public_path('uploads/products/' . $product->img_link));
            }
            $product->delete();
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
