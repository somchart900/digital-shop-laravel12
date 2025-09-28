@extends('layouts.app')

@section('title', $title ?? '')

@section('content')
<!-- Hero Section -->
<section class="hero" style="background: linear-gradient(to right, #111, #222); color: white; padding: 100px 0; text-align: center;">
    <div class="container">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-danger btn-lg mt-4">
            <i class="bi bi-lightning-fill me-2"></i>แดชบอร์ด {{ $webname->value ?? 'ชื่อเว็บไซต์' }} สุดเท่
        </a>
        <p class="lead mt-3">รายละเอียดการจัดการที่จำเป็นทั้งหมด</p>
    </div>
</section>

<!-- Admin Dashboard -->
<section id="admin-dashboard" class="py-5" style="background-color: #1a1a1a; color: #fff;">
    <div class="container-fluid">
        <div class="row">

                <!-- Sidebar -->
                @include('partials.sidebar')

            <!-- Content Area -->
            <div class="col-md-9">
                <div class="card bg-dark text-white shadow mb-4">
                    <div class="card-body">
                        <h4 class="mb-4"><i class="bi bi-graph-up me-2 text-primary"></i>ภาพรวมรายงานระบบ</h4>

                        <div class="row g-4">
                            <!-- ยอดขายวันนี้ -->
                            <div class="col-md-3">
                                <div class="card bg-success text-white shadow-sm">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="bi bi-currency-dollar me-2"></i>ยอดขายวันนี้</h6>
                                        <h3 class="fw-bold">฿{{ number_format($ordersum ?? 0, 2)}}</h3>
                                        <small>อัปเดตล่าสุด: {{ now()->format('H:i:s') }}</small>
                                    </div>
                                </div>
                            </div>

                            <!-- คำสั่งซื้อวันนี้ -->
                            <div class="col-md-3">
                                <div class="card bg-info text-white shadow-sm">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="bi bi-bag-check me-2"></i>คำสั่งซื้อวันนี้</h6>
                                        <h3 class="fw-bold">{{ $orderCount ?? '0' }} รายการ</h3>
                                        <small>อัปเดตล่าสุด: {{ now()->format('H:i:s') }}</small>
                                    </div>
                                </div>
                            </div>

                            <!-- สมาชิกใหม่ -->
                            <div class="col-md-3">
                                <div class="card bg-warning text-dark shadow-sm">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="bi bi-person-plus me-2"></i>สมาชิกใหม่</h6>
                                        <h3 class="fw-bold">{{ $userCount ?? '0' }} คน</h3>
                                        <small>วันนี้</small>
                                    </div>
                                </div>
                            </div>

                            <!-- เครดิตเติมเข้า -->
                            <div class="col-md-3">
                                <div class="card bg-danger text-white shadow-sm">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="bi bi-wallet2 me-2"></i>เครดิตเติมเข้า</h6>
                                        <h3 class="fw-bold">฿{{ number_format($topupsum ?? 0, 2)}}</h3>
                                        <small>วันนี้.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- กราฟยอดขาย -->
                <div class="card bg-dark text-white shadow">
                    <div class="card-body">
                        <h5 class="mb-3"><i class="bi bi-bar-chart-line me-2 text-info"></i>ยอดขายย้อนหลัง 7 วัน</h5>
                        <canvas id="salesChart" height="100"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels),  // ['25/08','26/08','27/08',...]
            datasets: [{
                label: 'ยอดขาย (บาท)',
                data: @json($data),    // [3200, 4100, 2900,...]
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.2)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#0d6efd'
            }]
        },
        options: {
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    ticks: {
                        callback: (value) => '฿' + value
                    },
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection






