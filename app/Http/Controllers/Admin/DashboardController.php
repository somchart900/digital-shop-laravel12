<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Topup;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $title = 'Dashboard';
        $userCount = User::count();
        $itemCount = Item::count();
        $sumTopup = Topup::sum('amount');
        $orderSumMonth = Order::whereYear('created_at', date('Y'))
                      ->whereMonth('created_at', date('m'))
                      ->sum('price');
        return view('admins.dashboard', compact(
            'userCount',
            'title',
            'itemCount',
            'sumTopup',
            'orderSumMonth'
        ));
    }
}
