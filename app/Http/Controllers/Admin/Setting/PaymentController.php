<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $truemoney = Setting::where('name', 'truemoney')->first();
        $bankname = Setting::where('name', 'bankname')->first();
        $accountname = Setting::where('name', 'accountname')->first();
        $accountnumber = Setting::where('name', 'accountnumber')->first();
        $title = 'ตั้งค่าการชําระเงิน';
        return view('admins.setting-payment', compact(
            'title',
            'truemoney',
            'bankname',
            'accountname',
            'accountnumber'
        ));
            
    }
}
