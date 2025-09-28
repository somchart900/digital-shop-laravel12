@extends('layouts.app')

@section('title', $title ?? '')

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <a href="{{ route('user.profile') }}" class="btn btn-danger btn-lg mt-4">
                <i class="bi bi-lightning-fill me-2"></i>โปรไฟล์ {{ $webname->value ?? 'ชื่อเว็บไซต์' }} ดิจิทัลสุดเท่
            </a>
            <p></p>
            <p class="lead">รายละเอียดการใช้งานส่วนตัว</p>
        </div>
    </section>

    <!-- Profile Section -->
    <section id="profile" class="py-5" style="background-color: #222; color: #fff;">
        <div class="container">
            <h2 class="mb-4 text-center">
                <i class="bi bi-person-circle me-2 text-warning"></i>โปรไฟล์ผู้ใช้
            </h2>
            <!-- Profile Card -->
            <div class="card bg-dark text-white shadow-lg border-0 rounded-4">
                <div class="card-body">
                    <!-- Profile Info -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <p><i class="bi bi-person-fill text-info me-2"></i><strong>ชื่อผู้ใช้:
                                </strong>{{ auth()->user()->username }}</p>
                            <p><i class="bi bi-envelope-fill text-warning me-2"></i><strong>อีเมล:
                                </strong>{{ auth()->user()->email }}</p>
                            <p>
                                <i class="bi bi-shield-check text-success me-2"></i>
                                <strong>ยืนยันอีเมล:</strong>
                                @if (auth()->user()->email_verified_at)
                                    <span class="badge bg-success">ยืนยันแล้ว (เมื่อ:
                                        {{ auth()->user()->email_verified_at->format('d/m/Y') }})</span>
                                @else
                                    <a href="{{ route('auth.verification') }}" class="badge bg-danger text-white text-decoration-none">
                                        ยังไม่ยืนยัน (คลิกเพื่อยืนยัน)
                                    </a>
                                @endif

                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><i class="bi bi-cash-coin text-primary me-2"></i><strong>เครดิต: </strong> <span
                                    class="text-success">{{ $credits ?? 0 }} บาท</span></p>
                            <p><i class="bi bi-calendar-check text-info me-2"></i><strong>วันที่สมัคร: </strong>
                                {{ auth()->user()->created_at->format('d/m/Y') }}</p>
                            <p>
                                <i class="bi bi-bar-chart-steps text-warning me-2"></i>
                                <strong>เลเวล: </strong>
                                <span class="badge bg-primary">
                                    {{ auth()->user()->level }}
                                </span>
                            </p>
                        </div>
                        <a href="{{ route('user.order.list') }}" class="btn btn-success btn-lg mt-4">
                            <i class="bi bi-cart me-2"></i>รายการสั่งซื้อ
                        </a>
                    </div>
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs border-secondary mb-3" id="profileTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active text-white bg-transparent border border-secondary rounded-top"
                                id="login-tab" data-bs-toggle="tab" data-bs-target="#login-history" type="button"
                                role="tab">
                                <i class="bi bi-clock-history me-1"></i>เข้าสู่ระบบ
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-white bg-transparent border border-secondary rounded-top"
                                id="topup-tab" data-bs-toggle="tab" data-bs-target="#topup-history" type="button"
                                role="tab">
                                <i class="bi bi-wallet2 me-1"></i>เติมเครดิต
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-white bg-transparent border border-secondary rounded-top"
                                id="purchase-tab" data-bs-toggle="tab" data-bs-target="#purchase-history" type="button"
                                role="tab">
                                <i class="bi bi-cart-check me-1"></i>ซื้อสินค้า
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-white bg-transparent border border-secondary rounded-top"
                                id="password-tab" data-bs-toggle="tab" data-bs-target="#change-password" type="button"
                                role="tab">
                                <i class="bi bi-key me-1"></i>เปลี่ยนรหัสผ่าน
                            </button>
                        </li>
                    </ul>

                    <!-- Tabs Content -->
                    <div class="tab-content pt-2" id="profileTabsContent">

                        <!-- Login History -->
                        <div class="tab-pane fade show active" id="login-history" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-dark table-borderless align-middle">
                                    <thead>
                                        <tr style="border-bottom: 2px solid #444;">
                                            <th><i class="bi bi-calendar-date me-1"></i>วันที่</th>
                                            <th><i class="bi bi-browser-chrome me-1"></i>เบราว์เซอร์</th>
                                            <th><i class="bi bi-geo-alt me-1"></i>IP</th>
                                            <th><i class="bi bi-device-ssd me-1"></i>OS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($logs as $log)
                                            <tr style="background-color: #2a2a2a;">
                                                <td>{{ $log->created_at->format('d/m/Y - H:i') }}</td>
                                                <td><i class="bi bi-browser-chrome text-info me-1"></i>{{ $log->browser }}
                                                </td>
                                                <td>{{ $log->ip }}</td>
                                                <td><span class="badge bg-primary"><i
                                                            class="bi bi-laptop me-1"></i>{{ $log->os }}</span></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Topup History -->
                        <div class="tab-pane fade" id="topup-history" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-dark table-borderless align-middle">
                                    <thead>
                                        <tr style="border-bottom: 2px solid #444;">
                                            <th><i class="bi bi-calendar-date me-1"></i>วันที่</th>
                                            <th><i class="bi bi-bank me-1"></i>ช่องทาง</th>
                                            <th><i class="bi bi-activity me-1"></i>สถานะ</th>
                                            <th><i class="bi bi-cash-coin me-1"></i>จํานวน</th>
                                            <th><i class="bi bi-info-circle me-1"></i>รายละเอียด</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topups as $topup)
                                            <tr style="background-color: #2a2a2a;">
                                                <td>
                                                    {{ $topup->created_at->format('d/m/Y - H:i') }}
                                                </td>
                                                <td>
                                                    {{ $topup->channel }}
                                                </td>
                                                <td>
                                                    @if ($topup->status == 'success')
                                                        <span class="badge bg-success">
                                                            <i class="bi bi-check-circle me-1"></i>สําเร็จ
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger">
                                                            <i class="bi bi-x-circle me-1"></i>ไม่สําเร็จ
                                                        </span>
                                                    @endif
                                                </td>
                                                <td><span class="badge bg-primary">
                                                        <i class="bi bi-currency-dollar me-1"></i>{{ $topup->amount }} 
                                                    </span>
                                                </td>
                                                <td>
                                                   {{ $topup->remark }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>

                        <!-- Purchase History -->
                        <div class="tab-pane fade" id="purchase-history" role="tabpanel">
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
                                        @foreach ($orders as $order)
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
                                                        <a href="{{ route('user.order.detail', ['id' => $order->id]) }}" class=" btn btn-sm text-white"> <i
                                                                class="bi bi-info-circle me-1  "></i> รายละเอียด</a>
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Change Password -->
                        <div class="tab-pane fade" id="change-password" role="tabpanel">
                            <form action="{{ route('auth.change-password') }}" method="POST"
                                onsubmit="showLoadingOnSubmit(event)">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label text-white">
                                        <i class="bi bi-lock me-1"></i>รหัสผ่านเดิม</label>
                                    <input type="password" name="current_password"
                                        class="form-control bg-dark text-white border-secondary rounded-3">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-white"><i
                                            class="bi bi-shield-lock me-1"></i>รหัสผ่านใหม่</label>
                                    <input type="password" name="new_password"
                                        class="form-control bg-dark text-white border-secondary rounded-3">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-white"><i
                                            class="bi bi-check2-square me-1"></i>ยืนยันรหัสผ่านใหม่</label>
                                    <input type="password" name="new_password_confirmation"
                                        class="form-control bg-dark text-white border-secondary rounded-3">
                                </div>
                                <button type="submit" class="btn btn-warning mt-2">
                                    <i class="bi bi-arrow-repeat me-1"></i>เปลี่ยนรหัสผ่าน
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>




@endsection
