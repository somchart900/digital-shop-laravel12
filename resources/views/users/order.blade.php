@extends('layouts.app')
@section('title', $title ?? '')
@section('content')
    <div class="hero">
        <h1 class="display-4 fw-bold"> {{ $webname->value ?? '' }}</h1>
        <p class="lead">รายละเอียดสินค้า</p>
    </div>


    <div class="container my-5 bg-transparent">
        <div class="row justify-content-center bg-transparent">
            <div class="col-lg-10">
                <a href="{{ route('home') }}" class="btn btn-primary mb-3"><i class="bi bi-arrow-left"></i> หน้าหลัก </a>
                <a href="{{ route('user.profile') }}" class="btn btn-primary mb-3"><i class="bi bi-arrow-left"></i>
                    ข้อมูลส่วนตัว</a>
                <a href="{{ route('user.order.list') }}" class="btn btn-primary mb-3"><i class="bi bi-arrow-left"></i>
                    รายการสั่งซื้อ</a>

                <!-- Delivery Card -->
                <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                    <div class="row g-0 bg-secondary">
                        <!-- Product Image -->
                        <div class="col-md-5 bg-secondary p-4 d-flex align-items-center">
                            <img src="{{ url('public/uploads/products/' . ($order->img_link ?? 'no-image.svg')) }}"
                                class="img-fluid w-100 object-fit-contain" style="max-height: 400px;"
                                alt="{{ $order->name ?? '' }}">
                        </div>

                        <!-- Delivery Details -->
                        <div class="col-md-7 d-flex flex-column p-4 p-lg-5">
                            <!-- Product Header -->
                            <h2 class="fw-bold mb-3">{{ $order->name ?? '' }}</h2>
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <span class="badge bg-light bg-opacity-10 text-dark fs-6 p-2">
                                    <i class="bi bi-receipt me-2"></i> Order ID: {{ $order->id ?? '' }}
                                </span>
                                <h4 class="text-danger fw-bold mb-0">{{ number_format($order->price ?? 0, 2) }} บาท</h4>
                            </div>

                            <!-- Product Description -->
                            <div class="mb-4">
                                <p class="text-muted">{{ $order->description ?? '' }}</p>
                            </div>

                            @if (!empty($order->youtube))
                                <div class="ratio ratio-16x9 rounded bg-light overflow-hidden mb-4">
                                    <iframe src="{{ getYouTubeEmbedURL($order->youtube) }} ?autoplay=1&mute=1"
                                        title="YouTube video" allow="autoplay" allowfullscreen class="border-0">
                                    </iframe>
                                </div>
                            @endif

                            <!-- Delivery Information -->
                            <div class="border-top border-bottom py-4 my-3">
                                <h5 class="fw-bold text-black mb-3">
                                    <i class="bi bi-box-seam me-2"></i> ข้อมูลการส่งมอบ
                                </h5>

                                @if (isUrl($order->code ?? ''))
                                    <div class="alert alert-success">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-file-earmark-arrow-down fs-4 me-3"></i>
                                            <div>
                                                <p class="fw-bold mb-1">ไฟล์สินค้าพร้อมดาวน์โหลด</p>
                                                <p class="small text-muted mb-0">คลิกปุ่มด้านล่างเพื่อดาวน์โหลด</p>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ $order->code ?? '' }}" target="_blank"
                                        class="btn btn-success btn-lg w-100 py-3 mb-3">
                                        <i class="bi bi-download me-2"></i> ดาวน์โหลดไฟล์สินค้า
                                    </a>
                                @else
                                    <div class="alert alert-info">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-key fs-4 me-3"></i>
                                            <div>
                                                <p class="fw-bold mb-1">รหัสสินค้า</p>
                                                <p class="small text-muted mb-0">กรุณาคัดลอกรหัสด้านล่างเพื่อใช้งาน</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control form-control-lg fw-bold text-center"
                                            id="delivery-code" value="{{ $order->code ?? '' }}" readonly
                                            style="background-color: #f8f9fa;">
                                        <button class="btn btn-success" type="button"
                                            onclick="copyToClipboard('{{ $order->code ?? '' }}')">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <!-- Additional Actions -->
                            <div class="mt-auto">
                                <button class="btn btn-outline-secondary w-100 py-3" onclick="window.print()">
                                    <i class="bi bi-printer me-2"></i> พิมพ์รายละเอียด
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Extra Description Card -->
                <div class="card shadow-sm border-0 rounded-3 mt-4 bg-secondary">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="fw-bold mb-3"><i class="bi bi-list"></i> รายละเอียดเชิงลึก</h2>
                        <p class="text-muted">
                           <?= $order->article ?? 'ไม่มีรายละเอียดเชิงลึก' ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>





@endsection
@push('scripts')
    <script>
        function copyToClipboard(value) {
            navigator.clipboard.writeText(value).then(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'คัดลอกแล้ว!',
                    text: value,
                    timer: 1500,
                    showConfirmButton: false
                });
            }).catch(function(err) {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถคัดลอกได้: ' + err,
                });
            });
        }
    </script>
@endpush
