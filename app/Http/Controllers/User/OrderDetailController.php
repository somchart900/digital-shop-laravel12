<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
class OrderDetailController extends Controller
{
    public function index(Request $request)
    {
        $order =  Order::where('id', $request->id)
              ->where('user_id', Auth::user()->id)
              ->first();
        return view('users.order', compact('order'));
    }
}
