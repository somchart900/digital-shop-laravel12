<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\Credit;
use App\Models\Topup;
use App\Models\Activitylog;
use App\Services\MyApi;
use App\Services\MyAngpao;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class WalletController extends Controller
{
    public function index()
    {
        // $truemoney = Setting::where('name', 'truemoney')->first();
        // $bankname = Setting::where('name', 'bankname')->first();
        // $accountname = Setting::where('name', 'accountname')->first();
        // $accountnumber = Setting::where('name', 'accountnumber')->first();
        // $byshop = Setting::where('name', 'byshop')->first();
        // $apikey = $byshop->value ?? 'apikey';

        // แคช settings ทั้งกลุ่ม
        $paymentSettings = Cache::rememberForever('payment_settings', function () {
            $keys = ['truemoney', 'bankname', 'accountname', 'accountnumber', 'byshop'];
            return Setting::whereIn('name', $keys)->get()->keyBy('name');
        });

        // ดึงค่าแต่ละ setting
        $truemoney = $paymentSettings['truemoney']->value ?? '';
        $bankname = $paymentSettings['bankname']->value ?? '';
        $accountname = $paymentSettings['accountname']->value ?? '';
        $accountnumber = $paymentSettings['accountnumber']->value ?? '';
        $apikey = $paymentSettings['byshop']->value ?? 'apikey';

        // $data = [
        //     'truemoney' => $truemoney->value ?? '',
        //     'bankname' => $bankname->value ?? '',
        //     'accountname' => $accountname->value ?? '',
        //     'accountnumber' => $accountnumber->value ?? '',
        //     'apikey' => $apikey,
        // ];

        $data = [
            'truemoney' => $truemoney,
            'bankname' => $bankname,
            'accountname' => $accountname,
            'accountnumber' => $accountnumber,
            'apikey' => $apikey,
        ];
        $user = User::find(Auth::user()->id);
        $credits = $user->credit()->value('amount');
        return view('users.topup', compact('user', 'credits', 'data'));
    }

    public function Redeem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'truemoney' => 'required|url',
        ]);
        if ($validator->fails()) {
            Topup::create([
                'user_id' => Auth::user()->id,
                'channel' => 'truemoney',
                'amount' => 0,
                'link' => $request->truemoney,
                'status' => 'fail',
                'remark' => 'url ไม่ถูกต้อง',
            ]);
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $checkurl = Topup::where('link', $request->truemoney)->first();
        if ($checkurl) {
            Topup::create([
                'user_id' => Auth::user()->id,
                'channel' => 'truemoney',
                'amount' => 0,
                'link' => $request->truemoney,
                'status' => 'fail',
                'remark' => 'url ซ้ํา',
            ]);
            return back()->with('error', 'url ซ้ํา');
        }
        $user = User::find(Auth::user()->id);
        $truemoney = Setting::where('name', 'truemoney')->first();
        $mok = $request->mok ?? 0;
        $result = MyAngpao::getmoney($truemoney->value, $request->truemoney, $mok);
        if (!$result || $result == '0') {
            Topup::create([
                'user_id' => $user->id,
                'channel' => 'truemoney',
                'amount' => $result,
                'link' => $request->truemoney,
                'status' => 'fail',
                'remark' => 'เติมเงินไม่สําเร็จ',
            ]);
            return back()->with('error', 'เติมเงินไม่สําเร็จ');
        }

        $credit = Credit::firstOrNew(['user_id' => $user->id]);
        $credit->amount = ($credit->amount ?? 0) + $result;
        $credit->save();
        Topup::create([
            'user_id' => $user->id,
            'channel' => 'truemoney',
            'amount' => $result,
            'link' => $request->truemoney,
            'status' => 'success',
            'remark' => 'เติมเงินสําเร็จ',
        ]);
        Activitylog::create([
            'user_id' => Auth::user()->id,
            'action' => 'เติมเงิน',
            'description' => 'เติมเงิน ' . $result . ' บาท ผ่าน Truemoney',
        ]);
        $tokken = Setting::where('name', 'bottoken')->first();
        $chatid = Setting::where('name', 'chatid')->first();
        $message = Auth::user()->username . ' เติมเงิน ' . $result . ' บาท ผ่าน Truemoney';
        MyApi::telegram($tokken->value ?? 'tokken', $chatid->value ?? 'chatid', $message);
        return back()->with([
            'success' => true,
            'message' => 'เติมเงินเรียบร้อยแล้ว',
        ]);
    }

    public function checkslip(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|file|mimes:jpg,jpeg,png,jfif,webp|max:2048',
            'qrText' => 'required|string',
        ]);
        if ($validator->fails()) {
            Log::error($validator->errors());
            Topup::create([
                'user_id' => Auth::user()->id,
                'channel' => 'slip',
                'amount' => 0,
                'link' => $request->qrText ?? 'null',
                'status' => 'fail',
                'remark' => 'file หรือ QR Code ไม่ถูกต้อง',
            ]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();

            try {
                // ใช้ Storage
                Storage::disk('public')->putFileAs('uploads/slips', $file, $filename);
                if (!file_exists(public_path('uploads/slips/'))) {
                    mkdir(public_path('uploads/slips/'), 0777, true); // 0777 = สิทธิ์เต็ม, true = สร้างซ้อนกันได้
                }
                copy(Storage::disk('public')->path('uploads/slips/' . $filename), public_path('uploads/slips/' . $filename));
            } catch (\Exception $e) {
                Log::error('ไม่สามารถอัปโหลดไฟล์ได้: ' . $e->getMessage());
                return redirect()->back()->with('error', true)->with('message', 'ไม่สามารถอัปโหลดไฟล์ได้');
            }
        }

        $byshop = Setting::where('name', 'byshop')->first();
        $apikey = $byshop->value ?? 'apikey';
        $bankname = Setting::where('name', 'bankname')->first();
        $bankCodeAcc = substr($bankname->value ?? '099 = promptpay', 0, 3);
        $accountnumber = Setting::where('name', 'accountnumber')->first();
        $OwnerAcc = $accountnumber->value ?? '1234567890';
        $mok = $request->mok ?? 0;
        $result = MyApi::checkslip($apikey, $request->qrText, $bankCodeAcc, $OwnerAcc, $mok);
        if ($result['success'] == false) {
            Topup::create([
                'user_id' => Auth::user()->id,
                'channel' => 'checkslip',
                'amount' => $result['amount'],
                'link' => url('uploads/slips/' . $filename),
                'status' => 'fail',
                'remark' => $result['message'],
            ]);
            return back()->with('error', true)->with('message', $result['message']);
        }

        $credit = Credit::firstOrNew(['user_id' => Auth::user()->id]);
        $credit->amount = ($credit->amount ?? 0) + $result['amount'];
        $credit->save();

        Activitylog::create([
            'user_id' => Auth::user()->id,
            'action' => 'เติมเงิน',
            'description' => 'เติมเงิน ' . $result['amount'] . ' บาท ผ่าน Checkslip',
        ]);

        Topup::create([
            'user_id' => Auth::user()->id,
            'channel' => 'checkslip',
            'amount' => $result['amount'],
            'link' => url('uploads/slips/' . $filename),
            'status' => 'success',
            'remark' => $result['message'],
        ]);
        $tokken = Setting::where('name', 'bottoken')->first();
        $chatid = Setting::where('name', 'chatid')->first();
        $message = Auth::user()->username . ' เติมเงิน ' . $result['amount']. ' บาท ผ่าน Checkslip';
        MyApi::telegram($tokken->value ?? 'tokken', $chatid->value ?? 'chatid', $message);
        return back()->with([
            'success' => true,
            'message' => 'เติมเงินเรียบร้อยแล้ว',
        ]);
    }
}
