@extends('layouts.app')
@section('title', $title ?? '')
@section('content')
    <div class="hero">
        <h1 class="display-4 fw-bold">ยินดีต้อนรับสู่ {{ $webname->value ?? 'ชื่อเว็บไซต์' }}</h1>
        <p class="lead">แพลตฟอร์มขายสินค้าดิจิทัลอัตโนมัติ ปลอดภัย รวดเร็ว</p>
        <a href="{{ route('home') }}" class="btn btn-danger btn-lg mt-3">
            เริ่มเลย<i class="bi bi-lightning-fill ms-2 text-warning"></i>
        </a>
    </div>
    <!-- Recent Orders Section -->
    @if (count($recentOrders) > 0)
        <section class="py-5 bg-dark text-white">
            <div class="container">
                <div class="text-center mb-5">
                    <h3 class="display-6 fw-bold text-gradient mb-3">
                        <i class="bi bi-clock-history text-warning me-2"></i>
                        รายการสั่งซื้อล่าสุด
                    </h3>
                    <div class="mx-auto"
                        style="width: 60px; height: 3px; background: linear-gradient(90deg, #ffc107, #dc3545);"></div>
                </div>

                <div class="recent-orders-wrapper position-relative overflow-hidden">
                    <div class="recent-orders d-flex gap-3">
                        @forelse($recentOrders as $order)
                            <div class="card bg-gradient border-0 shadow-lg h-100 recent-order-card flex-shrink-0">
                                <div class="card-body p-4 text-center">
                                    <h6 class="card-title text-warning fw-bold mb-2">
                                         {{ $order->username ?? 'ไม่ระบุ' }}
                                    </h6>
                                    <p class="text-white-50 mb-2">{{ $order->name ?? 'สินค้าไม่ระบุ' }}</p>
                                    <span class="badge bg-success fs-6 mb-2">
                                        <i class="bi bi-currency-bitcoin me-1"></i>฿{{ number_format($order->price) }}
                                    </span>
                                    <p class="small text-white-50 mb-0">
                                        {{ $order->created_at->locale('th')->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="text-white-50">ยังไม่มีรายการสั่งซื้อ</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    @endif
    <style>
        .recent-orders-wrapper {
            overflow: hidden;
            position: relative;
            width: 100%;
        }

        .recent-orders {
            display: flex;
            gap: 1rem;
            animation: scrollLeft 20s linear infinite;
        }

        .recent-order-card {
            min-width: 250px;
            flex-shrink: 0;
            background: linear-gradient(135deg, #343a40, #495057);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .recent-order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 20px rgba(255, 193, 7, 0.5);
        }

        /* Animation smooth loop */
        @keyframes scrollLeft {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        /* Responsive */
        @media (max-width: 992px) {
            .recent-order-card {
                min-width: 200px;
            }
        }

        @media (max-width: 576px) {
            .recent-order-card {
                min-width: 150px;
            }
        }
    </style>
    <!-- Category Section -->
    <section id="category" class="py-5 bg-dark text-white">
        <div class="container">
            <!-- Section Header -->
            <div class="text-center mb-5">
                <h2 class="display-6 fw-bold text-gradient mb-3">
                    <i class="bi bi-grid-3x3-gap-fill text-warning me-2"></i>
                    หมวดหมู่สินค้า
                </h2>
                <div class="mx-auto"
                    style="width: 60px; height: 3px; background: linear-gradient(90deg, #ffc107, #dc3545);"></div>
            </div>

            <!-- Categories Grid -->
            <div class="row g-4 mb-5">
                @forelse ($categories as $category)
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="card bg-gradient border-0 shadow-lg h-100 category-card">
                            <div class="position-relative overflow-hidden rounded-top">
                                <img src="{{ url('public/uploads/categories/' . $category->img_link) }}"
                                    class="card-img-top category-img" alt="{{ $category->name }}"
                                    style="height: 220px; object-fit: cover; transition: transform 0.3s ease;">

                                <!-- Category Badges -->
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge bg-danger fs-6 shadow-sm">
                                        <i class="bi bi-fire me-1"></i>แนะนำ
                                    </span>
                                </div>
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-success fs-6 shadow-sm">
                                        <i class="bi bi-box-seam me-1"></i>{{ $category->product->count() }} รายการ
                                    </span>
                                </div>

                                <!-- Hover Overlay -->
                                <div
                                    class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-25 opacity-0 category-overlay d-flex align-items-center justify-content-center">
                                    <a href="{{ route('product', ['category_name' => $category->name]) }}"
                                        class="text-decoration-none">
                                        <i class="bi bi-eye text-white fs-1"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="card-body text-center p-4"
                                style="background: linear-gradient(135deg, #495057, #343a40);">
                                <h5 class="card-title text-white fw-bold mb-3">{{ $category->name }}</h5>
                                <p class="card-text text-white-50 mb-4">{{ $category->description }}</p>
                                <a href="{{ route('product', ['category_name' => $category->name]) }}"
                                    class="btn btn-outline-warning btn-lg px-4 py-2 fw-semibold category-btn">
                                    <i class="bi bi-arrow-right-circle me-2"></i>ดูสินค้า
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="empty-state">
                            <i class="bi bi-inbox display-1 text-muted mb-3"></i>
                            <h3 class="text-muted">ยังไม่มีหมวดหมู่สินค้า</h3>
                            <p class="text-white-50">โปรดรอเรากำลังเพิ่มสินค้าเข้ามาเร็วๆนี้</p>
                            <button class="btn btn-outline-success">
                                <a href="https://www.xvideos.com/lang/thai/1" class="text-decoration-none">
                                    <i class="bi bi-arrow-right me-2"></i>ดูหนังโป้รอไปก่อน
                                </a>
                            </button>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Featured Products Section Header -->
            <div class="text-center mb-5 mt-5">
                <h3 class="display-6 fw-bold mb-3">
                    <i class="bi bi-star-fill text-warning me-2"></i>
                    สินค้าแนะนำ
                </h3>
                <div class="mx-auto"
                    style="width: 60px; height: 3px; background: linear-gradient(90deg, #ffc107, #dc3545);"></div>
            </div>

            <!-- Featured Products Grid -->
            <div class="row g-4">
                @forelse ($products as $product)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <div class="card bg-gradient border-0 shadow-lg h-100 product-card">
                            <div class="position-relative overflow-hidden">
                                <img src="{{ url('public/uploads/products/' . $product->img_link) }}"
                                    class="card-img-top product-img" alt="{{ $product->name }}"
                                    style="height: 200px; object-fit: cover; transition: transform 0.3s ease;">

                                <!-- Stock Badge -->
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span
                                        class="badge {{ $product->item_count > 10 ? 'bg-success' : ($product->item_count > 0 ? 'bg-warning text-dark' : 'bg-danger') }} fs-6 shadow-sm">
                                        <i class="bi bi-boxes me-1"></i>{{ $product->item_count }} ชิ้น
                                    </span>
                                </div>

                                <!-- Sale Badge (if applicable) -->
                                @if (($product->order_count ?? 0) > 1)
                                    <div class="position-absolute top-0 start-0 m-2">
                                        <span class="badge bg-info fs-6 shadow-sm">
                                            <i class="bi bi-lightning-fill me-1"></i>ขายดี
                                        </span>
                                    </div>
                                @endif

                                <!-- Product Overlay -->
                                <div
                                    class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 opacity-0 product-overlay d-flex align-items-center justify-content-center">
                                    <div class="text-center">
                                        <a class="text-decoration-none"
                                            href="{{ route('item', ['category_name' => $product->category->name, 'product_name' => $product->name]) }}">
                                            <button class="btn btn-warning btn-sm mb-2">
                                                <i class="bi bi-eye"></i> ดูรายละเอียด
                                            </button>
                                        </a>
                                        <br>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body p-3" style="background: linear-gradient(135deg, #495057, #343a40);">
                                <h6 class="card-title text-white fw-bold mb-2">{{ $product->name }}</h6>

                                <!-- Price Section -->
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-warning fw-bold fs-5">
                                        <i class="bi bi-currency-bitcoin me-1"></i>฿{{ number_format($product->price) }}
                                    </span>
                                    <small class="text-white-50">
                                        <i class="bi bi-graph-up me-1"></i>{{ $product->order_count ?? 0 }} ขาย
                                    </small>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-grid gap-2">
                                    <a href="{{ route('item', ['category_name' => $product->category->name, 'product_name' => $product->name]) }}"
                                        class="btn btn-danger btn-sm fw-semibold product-buy-btn">
                                        <i class="bi bi-cart-plus me-2"></i> ซื้อสินค้า
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="empty-state">
                            <i class="bi bi-bag-x display-1 text-muted mb-3"></i>
                            <h3 class="text-muted">ยังไม่มีสินค้าแนะนำ</h3>
                            <p class="text-white-50">โปรดรอเรากำลังเพิ่มสินค้าเข้ามาเร็วๆนี้</p>
                            <button class="btn btn-outline-success">
                                <a href="https://www.xnxx.com/" class="text-decoration-none">
                                    <i class="bi bi-arrow-right me-2"></i>ดูหนังโป้รอไปก่อน
                                </a>
                            </button>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Custom CSS for Enhanced Effects -->
    <style>
        .bg-gradient {
            background: linear-gradient(135deg, #6c757d, #495057) !important;
        }

        .category-card:hover .category-img {
            transform: scale(1.05);
        }

        .category-card:hover .category-overlay {
            opacity: 1 !important;
        }

        .product-card:hover .product-img {
            transform: scale(1.1);
        }

        .product-card:hover .product-overlay {
            opacity: 1 !important;
        }

        .category-btn:hover,
        .product-buy-btn:hover {
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        .empty-state i {
            opacity: 0.5;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .display-6 {
                font-size: 1.5rem;
            }

            .card-body {
                padding: 1rem !important;
            }
        }
    </style>

    <!-- ขั้นตอนการสั่งซื้อ -->
    <section class="py-5  bg-secondary">
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



    <script>
        const wrapper = document.querySelector('.recent-orders-wrapper');
        let scrollAmount = 0;

        function scrollLoop() {
            scrollAmount += 1; // ปรับความเร็ว
            if (scrollAmount >= wrapper.scrollWidth - wrapper.clientWidth) {
                scrollAmount = 0; // reset เลื่อนวนใหม่
            }
            wrapper.scrollTo({
                left: scrollAmount,
                behavior: 'smooth'
            });
            requestAnimationFrame(scrollLoop);
        }

        scrollLoop();
    </script>


@endsection
