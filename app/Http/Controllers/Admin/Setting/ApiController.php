<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
class ApiController extends Controller
{
    public function index()
    {    
        $title = 'ตั้งค่า API';
        $byshop = Setting::where('name', 'byshop')->first();
        $email = Setting::where('name', 'email')->first();
        $apppassword = Setting::where('name', 'apppassword')->first();
        $secretkey = Setting::where('name', 'secretkey')->first();
        $sitekey = Setting::where('name', 'sitekey')->first();
        $bottoken = Setting::where('name', 'bottoken')->first();
        $chatid = Setting::where('name', 'chatid')->first();
        return view('admins.setting-api', compact(
            'title',
            'byshop',
            'email',
            'apppassword',
            'secretkey',
            'sitekey',
            'bottoken',
            'chatid'
        ));
    }
}
