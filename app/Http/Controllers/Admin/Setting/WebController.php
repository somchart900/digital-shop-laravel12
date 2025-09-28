<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Models\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class WebController extends Controller
{
    public function index()
    {    
        $title = 'ตั้งค่าเว็บไซต์';
        return view('admins.setting-wep', compact('title'));
    }
    
}
