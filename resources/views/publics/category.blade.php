@extends('layouts.app')
@section('title', $title ?? '')
@section('content')
    <div class="hero">
        <h1 class="display-4 fw-bold">{{ $webname->value ?? 'ยินดีต้อนรับ' }}</h1>
        <p class="lead">แพลตฟอร์มขายสินค้าดิจิทัลอัตโนมัติ ปลอดภัย รวดเร็ว</p>
        <a href="{{ route('category') }}" class="btn btn-danger btn-lg mt-3">
            เริ่มเลย<i class="bi bi-lightning-fill ms-2 text-warning"></i>
        </a>
    </div>
    <!-- Category Section -->
    <section id="category" class="py-5 bg-dark text-white">
        <div class="container">
            <!-- Section Header -->
            <div class="text-center mb-5">
                <h2 class="display-6 fw-bold mb-3">
                    <i class="bi bi-grid-3x3-gap-fill text-warning me-2"></i>
                    หมวดหมู่สินค้า
                </h2>
                <div class="mx-auto mb-4"
                    style="width: 60px; height: 3px; background: linear-gradient(90deg, #ffc107, #dc3545);"></div>
                <p class="text-white-50 fs-6">เลือกหมวดหมู่สินค้าที่คุณต้องการ</p>
            </div>

            <!-- Search Form -->
            <div class="row justify-content-center mb-5">
                <div class="col-lg-6 col-md-8">
                    <form action="{{ route('category') }}" method="GET" class="search-form">
                        <div class="input-group input-group-lg shadow-sm">
                            <span class="input-group-text bg-gradient-danger border-0 text-white">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="form-control bg-dark text-white border-0 search-input"
                                placeholder="ค้นหาชื่อหมวดหมู่ที่ต้องการ..." autocomplete="off">
                            <button type="submit" class="btn btn-danger btn-lg px-4 fw-semibold search-btn">
                                <i class="bi bi-search me-1"></i>
                                ค้นหา
                            </button>
                        </div>
                        @if (request('search'))
                            <div class="mt-2 text-center">
                                <small class="text-warning">
                                    <i class="bi bi-funnel-fill me-1"></i>
                                    กำลังค้นหา: "{{ request('search') }}"
                                </small>
                                <a href="{{ route('category') }}" class="btn btn-outline-warning btn-sm ms-2">
                                    <i class="bi bi-x-circle"></i> ล้างการค้นหา
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Categories Grid -->
            <div class="row g-4 mb-5">
                @forelse ($categories as $category)
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="card category-card bg-gradient border-0 shadow-lg h-100">
                            <div class="position-relative overflow-hidden rounded-top">
                                <img src="{{ url('public/uploads/categories/' . $category->img_link) }}"
                                    class="card-img-top category-img" alt="{{ $category->name }}"
                                    style="height: 220px; object-fit: cover; transition: all 0.4s ease;">

                                <!-- Category Badges -->
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge bg-danger bg-gradient shadow-sm fs-6 pulse-badge">
                                        <i class="bi bi-fire me-1"></i>แนะนำ
                                    </span>
                                </div>
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-success bg-gradient shadow-sm fs-6">
                                        <i class="bi bi-cart me-1"></i>{{ $category->product->count() }} รายการ
                                    </span>
                                </div>

                                <!-- Hover Overlay -->
                                <div
                                    class="position-absolute top-0 start-0 w-100 h-100 category-overlay d-flex align-items-center justify-content-center">
                                    <a href="{{ route('product', ['category_name' => $category->name]) }}"
                                        class="text-decoration-none">
                                        <div class="text-center">
                                            <i class="bi bi-eye text-white display-4 mb-2 overlay-icon"></i>
                                            <p class="text-white fw-bold">ดูสินค้าทั้งหมด</p>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <div class="card-body p-4 category-body">
                                <div class="text-center">
                                    <h5 class="card-title text-white fw-bold mb-3 category-title">{{ $category->name }}
                                    </h5>
                                    <p class="card-text text-white-50 mb-4 category-desc">
                                        {{ Str::limit($category->description, 80) }}
                                    </p>

                                    <!-- Stats Row -->
                                    <div class="row g-2 mb-4">
                                        <div class="col-12">
                                            <div class="bg-dark bg-opacity-50 rounded p-2">
                                                <small class="text-white-50 d-block">สินค้าทั้งหมด</small>
                                                <strong class="text-warning">{{ $category->product->count() }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Button -->
                                    <a href="{{ route('product', ['category_name' => $category->name]) }}"
                                        class="btn btn-outline-warning btn-lg px-4 py-2 fw-semibold category-btn">
                                        <i class="bi bi-arrow-right-circle me-2"></i>
                                        เข้าชมสินค้า
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
                                    <i class="bi bi-search display-1 text-muted opacity-50"></i>
                                    <h3 class="text-muted mt-3">ไม่พบหมวดหมู่ที่ค้นหา</h3>
                                    <p class="text-white-50 mb-4">ไม่พบหมวดหมู่ที่ตรงกับ "{{ request('search') }}"</p>
                                    <a href="{{ route('category') }}" class="btn btn-outline-warning">
                                        <i class="bi bi-arrow-left me-2"></i>กลับไปดูทั้งหมด
                                    </a>
                                @else
                                    <i class="bi bi-inbox display-1 text-muted opacity-50"></i>
                                    <h3 class="text-muted mt-3">ยังไม่มีหมวดหมู่สินค้า</h3>
                                    <p class="text-white-50 mb-4">เรากำลังเพิ่มหมวดหมู่เข้ามาเร็วๆนี้</p>
                                    <button class="btn btn-outline-success">
                                        <a href="{{ route('home') }}" class="text-decoration-none">
                                            <i class="bi bi-home me-2"></i>หน้าหลัก
                                        </a>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforelse
                <!-- Pagination (if applicable) -->
                @if (method_exists($categories, 'links'))
                    <div class="d-flex justify-content-center mt-5">
                        {{ $categories->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>

            <!-- Results Counter (if search is active) -->
            @if (request('search') && $categories->count() > 0)
                <div class="text-center mb-3">
                    <small class="text-success">
                        <i class="bi bi-check-circle me-1"></i>
                        พบ {{ $categories->count() }} หมวดหมู่จากการค้นหา "{{ request('search') }}"
                    </small>
                </div>
            @endif
        </div>
    </section>

    <!-- Enhanced CSS Styles -->
    <style>
        /* Category Cards */
        .category-card {
            background: linear-gradient(145deg, #495057, #343a40) !important;
            transform: translateY(0);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .category-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3) !important;
        }

        .category-img {
            filter: brightness(0.9);
            transition: all 0.4s ease;
        }

        .category-card:hover .category-img {
            transform: scale(1.1);
            filter: brightness(1.1);
        }

        /* Search Form */
        .search-input {
            background: rgba(52, 58, 64, 0.8) !important;
            border: 2px solid transparent !important;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            background: rgba(52, 58, 64, 0.9) !important;
            border-color: #ffc107 !important;
            box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.25);
            color: white !important;
        }

        .search-btn:hover {
            transform: translateX(5px);
            transition: all 0.3s ease;
        }

        .bg-gradient-danger {
            background: linear-gradient(45deg, #dc3545, #c82333) !important;
        }

        /* Overlay Effects */
        .category-overlay {
            background: rgba(0, 0, 0, 0.7);
            opacity: 0;
            transition: all 0.4s ease;
        }

        .category-card:hover .category-overlay {
            opacity: 1;
        }

        .overlay-icon {
            transform: scale(0.8);
            transition: transform 0.3s ease;
        }

        .category-card:hover .overlay-icon {
            transform: scale(1);
        }

        /* Buttons */
        .category-btn {
            border-width: 2px;
            transition: all 0.3s ease;
        }

        .category-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.3);
            background: #ffc107;
            color: #000 !important;
        }

        /* Animations */
        .pulse-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .display-6 {
                font-size: 1.75rem;
            }

            .category-card {
                margin-bottom: 1rem;
            }

            .input-group-lg .form-control {
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .category-btn {
                font-size: 0.875rem;
                padding: 0.5rem 1rem;
            }

            .search-btn {
                padding: 0.75rem 1rem;
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
