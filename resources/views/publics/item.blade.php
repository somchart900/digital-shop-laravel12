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
                <a href="{{ route('home') }}" class="btn btn-primary mb-3"><i class="bi bi-arrow-left"></i> หน้าหลัก</a>
                <a href="{{ route('category') }}" class="btn btn-primary mb-3"><i class="bi bi-arrow-left"></i> หมวดหมู่</a>
                <a href="{{ url()->previous() }}" class="btn btn-primary mb-3"><i class="bi bi-arrow-left"></i> รายการ
                </a>

                <!-- Product Card -->
                <div class="card shadow-sm border-0 rounded-3 overflow-hidden bg-secondary">
                    <div class="row g-0">
                        <!-- Product Image -->
                        <div class="col-md-5 bg-secondary">
                            <img src="{{ url('public/uploads/products/' . ($items->img_link ?? 'no-image.svg')) }}"
                                class="img-fluid w-100 h-100 object-fit-contain p-4"
                                style="min-height: 400px; max-height: 500px;" alt="{{ $items->name ?? '' }}">
                        </div>

                        <!-- Product Details -->
                        <div class="col-md-7 d-flex flex-column p-4 p-lg-5">
                            <!-- Product Header -->
                            <h1 class="fw-bold mb-3">{{ $items->name ?? 'ไม่มีชื่อ' }}</h1>
                            <h3 class="text-danger fw-bold mb-4">{{ number_format($items->price ?? 0) }} บาท</h3>

                            <!-- Product Description -->
                            <div class="mb-4">
                                <h5 class="fw-semibold text-dark mb-3">คำอธิบาย</h5>
                                <p class="text-muted mb-0">{{ $items->description ?? 'ไม่มีคำอธิบาย' }}</p>
                            </div>

                            <!-- Media Content -->
                            @if (!empty($items->youtube))
                                <div class="mb-4">
                                    <div class="ratio ratio-16x9 rounded bg-light overflow-hidden">
                                        <iframe src="{{ getYouTubeEmbedURL($items->youtube) }}?autoplay=1&mute=1"
                                            title="YouTube video" allow="autoplay" allowfullscreen>
                                        </iframe>
                                    </div>
                                </div>
                            @endif

                            @if (!empty($items->external_link))
                                <div class="mb-4">
                                    <a href="{{ $items->external_link ?? '' }}" target="_blank"
                                        class="btn btn-outline-primary">
                                        <i class="bi bi-box-arrow-up-right me-2"></i> ดูตัวอย่างสินค้า
                                    </a>
                                </div>
                            @endif

                            <!-- Quantity Selector -->
                            <div class="my-4 bg-secondary">
                                <label for="quantity" class="form-label fw-semibold">จำนวน</label>
                                <div class="d-flex align-items-center gap-3">
                                    <input type="number" id="quantity" name="quantity"
                                        class="form-control form-control-lg text-center fw-bold" style="width: 100px;"
                                        value="1" min="1" max="<?= $count ?? '1' ?>">
                                    <span class="text-muted">มีสินค้าทั้งหมด <?= $count ?? '0' ?> ชิ้น</span>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-auto d-grid gap-2 bg-secondary">
                                <button id="order-btn" class="btn btn-success btn-lg fw-bold py-3"
                                    onclick="order({{ $items->product_id ?? '0' }}, {{ $items->price ?? '0' }}, document.getElementById('quantity').value)"
                                    <?= ($count ?? 0) <= 0 ? 'disabled' : '' ?>>
                                    <i class="bi bi-cart-check me-2"></i>
                                    {{ ($count ?? 0) > 0 ? 'สั่งซื้อสินค้า' : 'สินค้าหมด' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- extract details -->
                <!-- Extra Description Card -->
                <div class="card shadow-sm border-0 rounded-3 mt-4 bg-secondary">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="fw-bold mb-3"><i class="bi bi-list"></i> รายละเอียดเชิงลึก</h2>
                        <p class="text-muted">
                           <?=  $items->article ?? 'ไม่มีรายละเอียดเชิงลึก' ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('quantity');
            const button = document.getElementById('order-btn');
            const min = 1;
            const max = {{ $count ?? '' }};

            function validateInput() {
                const val = parseInt(input.value);
                button.disabled = isNaN(val) || val < min || val > max;
            }

            input.addEventListener('input', validateInput);
            validateInput(); // เช็กตอนโหลดหน้า
        });
    </script>
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function order(id, price, total) {
            Swal.fire({
                title: 'ยืนยันการสั่งซื้อ?',
                text: `ราคา (ปัจจุบัน: ${price*total} บาท)`,
                icon: 'warning',
                iconColor: '#ff0000',
                background: '#1a1a1a',
                color: '#e0ffe0',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    // ใช้ Fetch API ส่งค่า POST
                    fetch('{{ route('orderadd') }}', {
                            method: 'POST',
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": csrfToken,
                                "Accept": "application/json"
                            },
                            body: JSON.stringify({
                                id: id,
                                total: total
                            })
                        })
                        .then(response => response.json()) // รับผลลัพธ์เป็น JSON
                        .then(data => {
                            // ตรวจสอบข้อมูลที่ได้จาก PHP
                            if (data.success === true) {
                                Swal.fire({
                                        icon: 'success',
                                        title: 'สั่งซื้อสินค้าสําเร็จ',
                                        text: data.message,
                                        iconColor: '#00ff88',
                                        background: '#1a1a1a',
                                        color: '#e0ffe0',
                                        showConfirmButton: false,
                                        timer: 1500
                                    })
                                    .then(() => {
                                        // รีไดเร็คไปรายการสั่งซื้อ
                                        window.location.href = '{{ route('user.order.list') }}';

                                    });
                            } else {
                                Swal.fire({
                                        icon: 'error',
                                        title: 'ผิดพลาด!',
                                        text: data.message,
                                        iconColor: '#ff4444',
                                        background: '#1a1a1a',
                                        color: '#ffe0e0',
                                    })
                                    .then(() => {
                                        // รีไดเร็คไปเติมเงิน หากยังไมได้ล็อคอิน หน้าเติมเงินจะส่งไปหน้าล็อคอิน
                                        window.location.href = '{{ route('user.topup') }}';
                                    });
                            }
                        })
                        .catch(error => {
                            console.error('เกิดข้อผิดพลาดในการติดต่อเซิร์ฟเวอร์:', error);
                            Swal.fire('ผิดพลาด!', 'เกิดข้อผิดพลาดในการติดต่อเซิร์ฟเวอร์', 'error');
                        });
                }
            });
        }
    </script>
@endpush
