<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class OtherController extends Controller
{
    public function index()
    {
        $title = 'ตั้งค่าอื่นๆ';     
        $announce = Setting::where('name', 'announce')->first();
        $announce2 = Setting::where('name', 'announce2')->first();
        $enablebackend = Setting::where('name', 'enablebackend')->first();
        $bonus = Setting::where('name', 'bonus')->first();

        return view('admins.setting-other', compact(
            'title',
            'announce',
            'announce2',
            'enablebackend',
            'bonus'
        ));
    }
}
