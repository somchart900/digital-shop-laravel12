<?php

namespace App\Http\Controllers\Admin\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    public function __invoke(Request $request)
    {
        if (Auth::user()->level != 99) {
            return response()->json([
                'status' => 'error',
                'message' => 'เฉพาะผู้ดูแลระบบเท่านั้น'
            ]);
        }
        $loggedInUserId = Auth::user()->id; // 
        if ($request->user_id == $loggedInUserId) {
            return response()->json([
                'status' => 'error',
                'message' => 'ไม่สามารถลบบัญชีของตัวเองได้'
            ]);
        }

        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'ไม่พบผู้ใช้งาน'
            ]);
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'ลบผู้ใช้เรียบร้อยแล้ว'
        ]);
    }
}
