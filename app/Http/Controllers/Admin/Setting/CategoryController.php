<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
   public function index()
   {    
       $title = 'ตั้งค่าหมวดหมู่';
       return view('admins.category', compact('title'));
   }
}
