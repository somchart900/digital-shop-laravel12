@extends('layouts.app')
@section('title', $title ?? '')
@section('content')
    <div class="hero">
        <h1 class="display-4 fw-bold"> {{ $webname->value ?? 'ชื่อเว็ปไซต์' }}</h1>
        <p class="lead">แพลตฟอร์มขายสินค้าดิจิทัลอัตโนมัติ ปลอดภัย รวดเร็ว</p>
        <a href="{{ route('category') }}" class="btn btn-danger btn-lg mt-3">
            เริ่มเลย<i class="bi bi-lightning-fill ms-2 text-warning"></i>
        </a>
    </div>
    <!-- Product Section -->
    <section id="category" class="py-5 bg-dark text-white">
        <div class="container">
            <!-- Section Header -->
            <div class="text-center mb-5">
                <h2 class="display-6 fw-bold mb-3">
                    <i class="bi bi-box-seam-fill text-warning me-2"></i>
                    รายการ {{ $category->name ?? '' }}
                </h2>
                <div class="mx-auto mb-4"
                    style="width: 60px; height: 3px; background: linear-gradient(90deg, #ffc107, #dc3545);"></div>
                <p class="text-white-50 fs-6">ค้นหาและเลือกซื้อสินค้าที่คุณต้องการ</p>
            </div>

            <!-- Enhanced Search Form -->
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 col-md-10">
                    <form action="{{ url()->current() }}" method="GET" class="search-form">
                        <div class="input-group input-group-lg shadow-lg">
                            <span class="input-group-text bg-gradient-danger border-0 text-white">
                                <i class="bi bi-search fs-5"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="form-control bg-dark text-white border-0 search-input"
                                placeholder="ค้นหาชื่อสินค้าที่ต้องการ..." autocomplete="off">
                            <button type="submit" class="btn btn-danger btn-lg px-4 fw-semibold search-btn">
                                <i class="bi bi-search me-2"></i>
                                ค้นหา
                            </button>
                        </div>

                        <!-- Search Status -->
                        @if (request('search'))
                            <div class="mt-3 text-center">
                                <div
                                    class="d-inline-flex align-items-center bg-warning bg-opacity-10 rounded-pill px-3 py-2">
                                    <i class="bi bi-funnel-fill text-warning me-2"></i>
                                    <small class="text-warning me-3">
                                        ผลการค้นหา: "<strong>{{ request('search') }}</strong>"
                                    </small>
                                    <a href="{{ url()->current() }}" class="btn btn-outline-warning btn-sm rounded-pill">
                                        <i class="bi bi-x-circle me-1"></i> ล้าง
                                    </a>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="row g-4 mb-5">
                @forelse ($products as $product)
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="card product-card bg-gradient border-0 shadow-lg h-100">
                            <div class="position-relative overflow-hidden rounded-top">
                                <img src="{{ url('public/uploads/products/' . $product->img_link) }}"
                                    class="card-img-top product-img" alt="{{ $product->name }}"
                                    style="height: 240px; object-fit: cover; transition: all 0.4s ease;">

                                <!-- Product Badges -->
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-danger bg-gradient shadow-sm fs-6 pulse-badge">
                                        <i class="bi bi-fire me-1"></i>แนะนำ
                                    </span>
                                </div>

                                <div class="position-absolute top-0 end-0 m-3">
                                    <span class="badge bg-success bg-gradient shadow-sm fs-6">
                                        <i class="bi bi-boxes me-1"></i>{{ $product->item->count() }} รายการ
                                    </span>
                                </div>

                                <!-- Discount Badge (if applicable) -->
                                <div class="position-absolute bottom-0 start-0 m-3">
                                    <span class="badge bg-info bg-gradient shadow-sm fs-6">
                                        <i class="bi bi-percent me-1"></i>ลดพิเศษ
                                    </span>
                                </div>

                                <!-- Product Overlay -->
                                <div
                                    class="position-absolute top-0 start-0 w-100 h-100 product-overlay d-flex align-items-center justify-content-center">
                                    <div class="text-center">
                                        <div class="mb-3">
                                            <i class="bi bi-eye-fill text-white display-4 overlay-icon"></i>
                                        </div>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('item', ['category_name' => $product->category->name, 'product_name' => $product->name]) }}"
                                                class="text-decoration-none">
                                                <button class="btn btn-warning btn-sm rounded-pill overlay-btn">
                                                    <i class="bi bi-zoom-in me-1"></i> ดูรายละเอียด
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body p-4 product-body">
                                <!-- Product Name -->
                                <h5 class="card-title text-white fw-bold mb-3 product-title">
                                    {{ $product->name }}
                                </h5>

                                <!-- Product Description -->
                                <p class="card-text text-white-50 mb-4 product-desc">
                                    {{ Str::limit($product->description, 100) }}
                                </p>

                                <!-- Product Stats -->
                                <div class="row g-2 mb-4">
                                    <div class="col-6">
                                        <div class="bg-dark bg-opacity-50 rounded text-center p-2">
                                            <small class="text-white-50 d-block">รายการ</small>
                                            <strong class="text-info">{{ $product->item->count() }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bg-dark bg-opacity-50 rounded text-center p-2">
                                            <small class="text-white-50 d-block">ขายแล้ว</small>
                                            <strong class="text-success">{{ $product->order_count ?? 0 }}</strong>
                                        </div>
                                    </div>

                                </div>

                                <!-- Price and Action -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        @if (isset($product->price))
                                            <span class="text-warning fw-bold fs-5">
                                                <i class="bi bi-currency-bitcoin me-1"></i>
                                                ฿{{ number_format($product->price) }}
                                            </span>
                                            <br>
                                            <small class="text-decoration-line-through text-muted">
                                                ฿{{ number_format($product->price * 1.2) }}
                                            </small>
                                        @else
                                            <span class="text-info fw-bold">ราคาตามรายการ</span>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        <small class="text-white-50">
                                            <i class="bi bi-graph-up me-1"></i>
                                            ยอดนิยม
                                        </small>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div class="d-grid">
                                    <a href="{{ route('item', ['category_name' => $product->category->name, 'product_name' => $product->name]) }}"
                                        class="btn btn-outline-warning btn-lg fw-semibold product-btn">
                                        <i class="bi bi-arrow-right-circle me-2"></i>
                                        ซื้อสินค้า
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5 empty-state">
                            <div class="mb-4">
                                @if (request('search'))
                                    <i class="bi bi-search display-1 text-muted opacity-50 mb-3"></i>
                                    <h3 class="text-muted">ไม่พบสินค้าที่ค้นหา</h3>
                                    <p class="text-white-50 mb-4">
                                        ไม่พบสินค้าที่ตรงกับคำค้นหา "<strong>{{ request('search') }}</strong>"
                                    </p>
                                    <div class="d-flex gap-3 justify-content-center">
                                        <a href="{{ url()->current() }}" class="btn btn-outline-warning">
                                            <i class="bi bi-arrow-left me-2"></i>ดูสินค้าทั้งหมด
                                        </a>
                                        <button class="btn btn-outline-info"
                                            onclick="document.querySelector('input[name=search]').value=''; document.querySelector('form').submit();">
                                            <i class="bi bi-arrow-clockwise me-2"></i>ค้นหาใหม่
                                        </button>
                                    </div>
                                @else
                                    <i class="bi bi-box display-1 text-muted opacity-50 mb-3"></i>
                                    <h3 class="text-muted">ยังไม่มีสินค้า</h3>
                                    <p class="text-white-50 mb-4">
                                        ขณะนี้ยังไม่มีสินค้าในระบบ โปรดรอเรากำลังเพิ่มสินค้าเข้ามาเร็วๆนี้
                                    </p>
                                    <a href="{{ route('home') }}" class="btn btn-outline-success btn-lg">
                                        <i class="bi bi-home me-2"></i>กลับหน้าแรก
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Results Counter -->
            @if (request('search') && $products->count() > 0)
                <div class="text-center">
                    <div class="d-inline-flex align-items-center bg-success bg-opacity-10 rounded-pill px-4 py-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <small class="text-success fw-semibold">
                            พบสินค้า {{ $products->count() }} รายการ จากการค้นหา "{{ request('search') }}"
                        </small>
                    </div>
                </div>
            @endif

            <!-- Pagination (if applicable) -->
            @if (method_exists($products, 'links'))
                <div class="d-flex justify-content-center mt-5">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </section>

    <!-- Enhanced CSS Styles -->
    <style>
        /* Product Cards */
        .product-card {
            background: linear-gradient(145deg, #495057, #343a40) !important;
            transform: translateY(0);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 1rem !important;
        }

        .product-card:hover {
            transform: translateY(-15px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4) !important;
        }

        .product-img {
            filter: brightness(0.9) contrast(1.1);
            border-radius: 1rem 1rem 0 0;
        }

        .product-card:hover .product-img {
            transform: scale(1.1);
            filter: brightness(1.1) contrast(1.2);
        }

        /* Search Enhancement */
        .search-input {
            background: rgba(52, 58, 64, 0.9) !important;
            border: 2px solid transparent !important;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .search-input:focus {
            background: rgba(52, 58, 64, 0.95) !important;
            border-color: #ffc107 !important;
            box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.3);
            transform: scale(1.02);
        }

        .search-btn:hover {
            transform: translateX(5px) scale(1.05);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
        }

        .bg-gradient-danger {
            background: linear-gradient(135deg, #dc3545, #c82333) !important;
        }

        /* Product Overlay */
        .product-overlay {
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.8), rgba(52, 58, 64, 0.9));
            opacity: 0;
            transition: all 0.4s ease;
            backdrop-filter: blur(5px);
        }

        .product-card:hover .product-overlay {
            opacity: 1;
        }

        .overlay-icon {
            transform: scale(0.7) rotate(0deg);
            transition: all 0.4s ease;
        }

        .product-card:hover .overlay-icon {
            transform: scale(1) rotate(360deg);
        }

        .overlay-btn {
            transform: translateY(20px);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .product-card:hover .overlay-btn {
            transform: translateY(0);
            opacity: 1;
        }

        .overlay-btn:nth-child(1) {
            transition-delay: 0.1s;
        }

        .overlay-btn:nth-child(2) {
            transition-delay: 0.2s;
        }

        /* Buttons */
        .product-btn {
            border-width: 2px;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .product-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 193, 7, 0.4);
            background: linear-gradient(135deg, #ffc107, #ffca2c);
            color: #000 !important;
        }

        .product-btn:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .product-btn:hover:before {
            left: 100%;
        }

        /* Animations */
        .pulse-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.08);
            }
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .product-card:hover {
                transform: translateY(-8px);
            }
        }

        @media (max-width: 768px) {
            .display-6 {
                font-size: 1.75rem;
            }

            .product-card {
                margin-bottom: 1.5rem;
            }

            .search-input {
                font-size: 1rem;
            }

            .product-btn {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .product-card:hover {
                transform: translateY(-5px);
            }

            .overlay-btn {
                font-size: 0.8rem;
            }
        }
    </style>
    <!-- ขั้นตอนการสั่งซื้อ -->
    <section class="py-5">
        <div class="container">
            <h3 class="text-center text-white mb-5">ขั้นตอนการสั่งซื้อ</h3>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="text-center text-white">
                        <div class="bg-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 80px; height: 80px;">
                            <i class="bi bi-credit-card-2-front fs-2"></i>
                        </div>
                        <h5>1. เติมเครดิต</h5>
                        <p class="small">เติมเงินเข้าระบบด้วยวิธีที่สะดวกสำหรับคุณ</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center text-white">
                        <div class="bg-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 80px; height: 80px;">
                            <i class="bi bi-search fs-2"></i>
                        </div>
                        <h5>2. เลือกสินค้า</h5>
                        <p class="small">ค้นหาและเลือกสินค้าที่ต้องการจากหมวดหมู่ต่างๆ</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center text-white">
                        <div class="bg-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 80px; height: 80px;">
                            <i class="bi bi-cart-check fs-2"></i>
                        </div>
                        <h5>3. กดซื้อ</h5>
                        <p class="small">กดซื้อสินค้าและชำระด้วยเครดิตในระบบ</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center text-white">
                        <div class="bg-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 80px; height: 80px;">
                            <i class="bi bi-download fs-2"></i>
                        </div>
                        <h5>4. รับสินค้าทันที</h5>
                        <p class="small">ดาวน์โหลดสินค้าดิจิทัลได้ทันทีหลังชำระเงิน</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
