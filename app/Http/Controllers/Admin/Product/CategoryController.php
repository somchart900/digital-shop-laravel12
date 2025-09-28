<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        // รับ keyword สำหรับค้นหา
        $search = $request->input('search');

        $categories = Category::withCount('product') // นับจำนวน products
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10); // แบ่งหน้า 10 per page

        return view('admins.category', compact('categories', 'search'));
    }

    public function create(Request $request)
    {
        // ตรวจสอบสิทธิ์
        if (Auth::user()->level != 99) {
            return redirect()->back()->with('error', true)->with('message', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255|unique:categories,name',
            'description' => 'required|string',
            'is_featured' => 'required|boolean',
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
                Storage::disk('public')->putFileAs('uploads/categories', $file, $filename);
                if (!file_exists(public_path('uploads/categories/'))) {
                    mkdir(public_path('uploads/categories/'), 0777, true); // 0777 = สิทธิ์เต็ม, true = สร้างซ้อนกันได้
                }
                copy(Storage::disk('public')->path('uploads/categories/' . $filename), public_path('uploads/categories/' . $filename));
            } catch (\Exception $e) {
                Log::error('ไม่สามารถอัปโหลดไฟล์ได้: ' . $e->getMessage());
                return redirect()->back()->with('error', true)->with('message', 'ไม่สามารถอัปโหลดไฟล์ได้');
            }
        }

        // สร้าง Category
        $name = preg_replace('/\s+/', '-', $request->name); // แทนที่ช่องว่างด้วย -
        $category = Category::create([
            'name'        => $name,
            'description' => $request->description,
            'is_featured' => $request->is_featured,
            'img_link'    => $filename,
        ]);

        if ($category) {
            
            Cache::forget('featured_categories');
            Cache::forget('featured_products');
            return redirect()->back()->with('success', true)->with('message', 'เพิ่มหมวดหมู่สินค้าสำเร็จ');
        } else {
            Log::error('เพิ่มหมวดหมู่สินค้าไม่สำเร็จ');
            return redirect()->back()->with('error', true)->with('message', 'ไม่สามารถเพิ่มหมวดหมู่สินค้าได้');
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

        $category = Category::find($request->id);

        if ($category) {
            // ลบไฟล์รูปภาพ
            if ($category->img_link) {
                Storage::disk('public')->delete('uploads/categories/' . $category->img_link);
                unlink(public_path('uploads/categories/' . $category->img_link));
            }
            $category->delete();
            // ลบ cache
            Cache::forget('featured_categories');
            Cache::forget('featured_products');

            return json_encode([
                'success' => true,
                'message' => 'ลบหมวดหมู่สินค้าสำเร็จ'
            ]);
        } else {
            Log::error('ลบหมวดหมู่สินค้าไม่สำเร็จ');
            return json_encode([
                'success' => false,
                'message' => 'ไม่สามารถลบหมวดหมู่สินค้าได้'
            ]);
        }
    }
}
