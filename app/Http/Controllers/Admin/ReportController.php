<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Topup;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class ReportController extends Controller
{
    public function __invoke(Request $request)
    {
        // -------- ยอดขายย้อนหลัง 7 วัน --------
        $salesLast7Days = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(price) as total')
        )
            ->where('created_at', '>=', Carbon::today()->subDays(6)) // ย้อนหลังรวมวันนี้ = 7 วัน
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date'); // ได้เป็น key => value

        // เตรียม label + data สำหรับ Chart.js
        $labels = [];
        $data   = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $labels[] = $day->format('d/m'); // หรือใช้ชื่อวันก็ได้
            $data[]   = $salesLast7Days[$day->toDateString()] ?? 0;
        }
        $orderCount = Order::whereDate('created_at', Carbon::today())->count();
        $ordersum = Order::whereDate('created_at', Carbon::today())->sum('price');
        $topupsum = Topup::whereDate('created_at', Carbon::today())->sum('amount');
        $userCount = User::whereDate('created_at', Carbon::today())->count();
        return view('admins.reports', compact(
            'userCount',
            'ordersum',
            'topupsum',
            'orderCount',
            'labels',
            'data'
        ));
    }
}
