<?php

namespace App\Http\Controllers\Admin\User;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function __invoke(Request $request)
    {
        if (Auth::user()->level != 99) {
            return response()->json([
                'status' => 'error',
                'message' => 'เฉพาะผู้ดูแลระบบเท่านั้น'
            ]);
        }
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'level' => 'required|integer|min:0|max:99',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->level = $request->level;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'อัปเดตระดับผู้ใช้เรียบร้อยแล้ว',
        ]);
    }
}
