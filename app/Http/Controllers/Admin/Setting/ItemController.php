<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(){
        $title = 'ตั้งค่าสินค้า';
        return view('admins.item', compact('title'));
    }
}
