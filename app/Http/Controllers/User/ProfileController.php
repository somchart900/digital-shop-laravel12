<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
      public function index()
    {
        $user = User::find(Auth::user()->id);
        // ดึง login logs ล่าสุด 5 รายการ
        $logs = $user->loginLog()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        $credits = $user->credit()->value('amount');
        $orders = $user->order()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        $topups = $user->topup()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('users.profile', compact('user', 'logs', 'credits', 'orders', 'topups'));
    }
}
