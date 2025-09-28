<?php

namespace App\Http\Controllers\Admin\User;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Topup;

class CreditController extends Controller
{
    public function __invoke(Request $request)
    {
        if (Auth::user()->level != 99) {
            return response()->json([
                'status' => 'error',
                'message' => 'เฉพาะผู้ดูแลระบบเท่านั้น'
            ]);
        }
        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'ไม่พบผู้ใช้'
            ]);
        }
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'credit'  => 'required|numeric|min:0',
        ]);
        $user->credit()->updateOrCreate(
            ['user_id' => $request->user_id],         
            ['amount' => $request->credit]
        );
        $user->topup()->create([
            'user_id' => $request->user_id,
            'amount' => $request->credit,
            'status' => 'success',
            'channel' => 'admin',
            'link' => url('user/profile'),
            'remark' => 'admin อัปเดตเครดิต',
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'อัปเดตเครดิตเรียบร้อยแล้ว'
        ]);
    }
}
