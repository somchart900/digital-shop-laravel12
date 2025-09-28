@extends('layouts.app')

@section('title', $title ?? '')

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-danger btn-lg mt-4">
                <i class="bi bi-lightning-fill me-2"></i>แดชบอร์ด {{ $webname->value ?? 'ชื่อเว็บไซต์' }} สุดเท่
            </a>
            <p></p>
            <p class="lead">รายละเอียดการจัดการที่จำเป็นทั้งหมด</p>
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
                    <div class="card bg-dark text-white shadow">
                        <div class="card-body">
                            <h4 class="mb-3"><i class="bi bi-house-door-fill me-2 text-primary"></i>ยินดีต้อนรับ
                                {{ auth()->user()->username ?? '' }}</h4>
                            <!-- Summary Boxes -->
                            <div class="row g-3">
                                <div class="col-md-6 col-xl-3">
                                    <div class="p-3 bg-black border border-secondary rounded text-center shadow-sm">
                                        <i class="bi bi-people-fill fs-2 text-success"></i>
                                        <h5 class="mt-2">สมาชิกทั้งหมด</h5>
                                        <p class="fs-5 fw-bold">{{ $userCount }} รายการ</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-3">
                                    <div class="p-3 bg-black border border-secondary rounded text-center shadow-sm">
                                        <i class="bi bi-box-seam fs-2 text-danger"></i>
                                        <h5 class="mt-2">สินค้าทั้งหมด</h5>
                                        <p class="fs-5 fw-bold">{{ $itemCount ?? '0' }} รายการ</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-3">
                                    <div class="p-3 bg-black border border-secondary rounded text-center shadow-sm">
                                        <i class="bi bi-wallet2 fs-2 text-warning"></i>
                                        <h5 class="mt-2">ยอดเติมเครดิต</h5>
                                        <p class="fs-5 fw-bold">฿{{number_format($sumTopup ?? 0, 2)}} </p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-3">
                                    <div class="p-3 bg-black border border-secondary rounded text-center shadow-sm">
                                        <i class="bi bi-graph-up-arrow fs-2 text-info"></i>
                                        <h5 class="mt-2">ยอดขายเดือนนี้</h5>
                                        <p class="fs-5 fw-bold">฿{{ number_format($orderSumMonth ?? 0, 2) }} </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Notice or Quick Info -->
                            <div class="alert alert-info mt-4 border-info text-dark">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                ข้อมูลสถิติทั้งหมดอัปเดตล่าสุด: {{ now()->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
