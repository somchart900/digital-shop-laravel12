<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
class OrderListController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::user()->id)->paginate(10);
        return view('users.order-list', compact('orders'));
    }
}
