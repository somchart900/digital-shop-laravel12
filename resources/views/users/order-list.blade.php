@extends('layouts.app')
@section('title', $title ?? '')
@section('content')
    <div class="hero">
        <h1 class="display-4 fw-bold"> {{ $webname->value ?? '' }}</h1>
        <p class="lead">รายละเอียดสินค้า</p>
    </div>
    <div class="container mt-5">
        <div class="row justify-content-center mt-5 ">
            <div class="col-md-8">
                <!-- Purchase History -->
                <h2 class="my-4"><i class="bi bi-bag-check-fill me-2"></i>ประวัติการสั่งซื้อ</h2>
                <div class="table-responsive">
                    <table class="table table-dark table-borderless align-middle">
                        <thead>
                            <tr style="border-bottom: 2px solid #444;">
                                <th><i class="bi bi-calendar-date me-1"></i>วันที่</th>
                                <th><i class="bi bi-box-seam me-1"></i>สินค้า</th>
                                <th><i class="bi bi-currency-dollar me-1"></i>ราคา</th>
                                <th><i class="bi bi-info-circle  me-1"></i>รายละเอียด</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr style="background-color: #2a2a2a;">
                                    <td>
                                        {{ $order->created_at->format('d/m/Y - H:i') }}
                                    </td>
                                    <td>
                                        <i class="bi bi-box-seam text-info me-1"></i>{{ $order->name }}
                                    </td>
                                    <td><span class="badge bg-primary">
                                            <i class="bi bi-currency-dollar me-1"></i>{{ $order->price }} บาท
                                        </span>
                                    </td>
                                    <td><span class="badge bg-primary ">
                                            <a href="{{ route('user.order.detail', ['id' => $order->id]) }}"
                                                class=" btn btn-sm text-white"> <i class="bi bi-info-circle me-1  "></i>
                                                รายละเอียด</a>
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">ยังไม่มีคำสั่งซื้อ</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $orders->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
