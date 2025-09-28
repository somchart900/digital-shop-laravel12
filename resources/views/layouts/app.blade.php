<!DOCTYPE html>
<html lang="th">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', ' | หน้าหลัก')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background: linear-gradient(to right, #111, #222);
            color: white;
        }


        .hero {
            background: linear-gradient(to right, #111, #222);
            color: white;
            padding: 100px 0;
            text-align: center;
        }

        footer {
            background: #111;
            color: #ccc;
        }

        footer a {
            color: #bbb;
            text-decoration: none;
        }

        footer a:hover {
            color: white;
            text-decoration: underline;
        }

        input::placeholder {
            color: #ddd;
        }

        input:focus {
            box-shadow: none;
        }
    </style>
    @stack('styles')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            color: white;
            font-size: 24px;
            display: none;
            z-index: 999;
        }

        .loading-overlay i {
            animation: spin 0.5s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }

        }
    </style>

    <script>
        window.onpageshow = function(event) {
            if (event.persisted) {
                location.reload();
            }
        };

        window.onpopstate = function() {
            location.href = location.pathname + "?nocache=" + Date.now();
        };

        history.pushState(null, "", location.href);
    </script>
</head>

<body>
    <div class="loading-overlay" id="loading-overlay">
        <div class="spinner-border text-success" style="width: 8rem; height: 8rem;" role="status">
            <span class="visually-hidden">กำลังโหลด...</span>
        </div>
        <!-- <p>กำลังดำเนินการ...</p> -->
    </div>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-black shadow">
        <div class="container">
            <a class="navbar-brand fw-bold text-danger" href="{{ route('home') }}" id="navbarMenu"><i
                    class="bi bi-fire me-2"></i>{{ $webname->value ?? 'ชื่อเว็บไซต์' }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link " href="{{ route('home') }}"><i
                                class="bi bi-house-door me-1"></i>หน้าหลัก</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('category') }}"><i
                                class="bi bi-grid me-1"></i>หมวดหมู่</a></li>
                    @auth
                        <li class="nav-item"><a class="nav-link" href="{{ route('user.topup') }}"><i
                                    class="bi bi-cash-coin me-1"></i>เติมเครดิต</a></li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->username }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item text-success fw-bold" href="{{ route('user.profile') }}"><i
                                            class="bi bi-person me-2"></i>โปรไฟล์</a></li>
                                @can('enablebackend')
                                    <li><a class="dropdown-item text-success fw-bold " href="{{ route('admin.dashboard') }}"><i
                                                class="bi bi-speedometer2 me-2"></i>แดชบอร์ด</a></li>
                                @endcan
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('auth.logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>ออกจากระบบ
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('auth.login') }}"><i
                                    class="bi bi-box-arrow-in-right me-1"></i>เข้าสู่ระบบ</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('auth.register') }}"><i
                                    class="bi bi-person-plus me-1"></i>สมัครสมาชิก</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    {{-- <main class="container mt-5 flex-grow-1"> --}}
    @yield('content')
    {{-- </main> --}}

    <!-- Footer -->
    <footer class="pt-5 pb-3">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5 class="text-white">เกี่ยวกับเรา</h5>
                    <p>{{ $webname->value ?? 'ชื่อเว็บไซต์' }} คือแพลตฟอร์มขายสินค้าดิจิทัลครบวงจร
                        พร้อมระบบอัตโนมัติปลอดภัย</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h5 class="text-white">ลิงก์สำคัญ</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}">หน้าแรก</a></li>
                        <li><a href="{{ route('category') }}">หมวดหมู่</a></li>
                        <li><a href="{{ route('user.topup') }}">เติมเครดิต</a></li>
                        <li><a href="{{ route('user.profile') }}">โปรไฟล์</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h5 class="text-white">ติดตามเรา</h5>
                    <a href="{{ $facebook->value ?? '#' }}" class="me-3"><i class="bi bi-facebook"></i></a>
                    <a href="{{ $youtube->value ?? '#' }}" class="me-3"><i class="bi bi-youtube"></i></a>
                    <a href="{{ $discord->value ?? '#' }}"><i class="bi bi-discord"></i></a>
                </div>
            </div>
            <div class="text-center pt-3 border-top border-secondary mt-4">
                <small>&copy; {{ date('Y') }} {{ $webname->value ?? 'ชื่อเว็บไซต์' }}. All rights
                    reserved.</small>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    <script>
        function showLoading(event, url) {
            //  event.preventDefault();
            document.getElementById("loading-overlay").style.display = "flex";
            let fixedUrl = decodeURIComponent(url);
            // setTimeout(() => {
            window.location.href = fixedUrl;

            // }, 10);

        }

        function showLoadingOnSubmit(event) {
            // แสดง overlay spinner
            document.getElementById('loading-overlay').style.display = 'flex';
            // ถ้าต้องการส่งฟอร์มตามปกติไม่ต้องป้องกัน event
            // event.preventDefault();  // ป้องกันการเปลี่ยนหน้า
            // ส่งฟอร์ม
            // setTimeout(() => { 
            //  event.target.submit();
            // }, 10);
        }

        function closeLoading() {
            document.getElementById('loading-overlay').style.display = 'none';
        }


        document.addEventListener("click", function(event) {
            let target = event.target.closest("a"); // หาลิงก์ <a> ที่ใกล้ที่สุด
            if (!target) return; // ถ้าไม่ใช่ <a> ข้ามไป

            let href = target.getAttribute("href");
            let targetAttr = target.getAttribute("target");

            // ❌ ข้ามลิงก์ที่เป็น "#" หรือไม่มี href หรือมี target="_blank"
            if (!href || href === "#" || targetAttr === "_blank") {
                return;
            }

            showLoading(event, target.href);
        });
    </script>

    @if (session('success'))
        <script>
            const message = `{{ session('message') }}`;

            Swal.fire({
                title: `✅ สำเร็จ!`,
                text: message,
                icon: 'success',
                iconColor: '#00ff88', // สีเขียวสะท้อนแสง
                background: '#1a1a1a',
                color: '#e0ffe0',
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false,
            }).then(() => {
                window.location.reload();
            });
        </script>
    @endif


    @if ($errors->any())
        <script>
            const errors = @json($errors->all());
            // const errors = <?php echo json_encode($errors->all()); ?>;
            Swal.fire({
                title: '❌ มีข้อผิดพลาด!',
                html: errors.join('<br>'),
                icon: 'error',
                iconColor: '#ff0000',
                background: '#1a1a1a',
                color: '#e0ffe0',
                confirmButtonColor: '#ff4d4d',
                customClass: {
                    popup: 'swal2-popup-dark'
                }
            }).then(() => {
                window.location.reload();
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            const message = `{{ session('message') }}`;
            Swal.fire({
                title: '❌ ไม่สำเร็จ!',
                text: message,
                icon: 'error',
                iconColor: '#ff0000',
                background: '#1a1a1a',
                color: '#e0ffe0',
                confirmButtonColor: '#ffc107'
            }).then(() => {
                window.location.reload();
            });
        </script>
    @endif
    @if (!empty($popup->value))
        @include('partials.popup')
    @endif
    @if (!empty($messenger->value) || !empty($line->value))
        @include('partials.FloatingContactButton')
    @endif
</body>

</html>
