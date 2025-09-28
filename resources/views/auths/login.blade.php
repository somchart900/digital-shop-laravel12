@extends('layouts.app')

@section('title', $title ?? '')

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <a href="{{ route('auth.login') }}" class="btn btn-danger btn-lg mt-4">
                <i class="bi bi-lightning-fill me-2"></i>เข้าสู่ระบบ {{ $webname->value ?? 'ชื่อเว็บไซต์' }} สุดเท่
            </a>
            <p></p>
            <p class="lead">เพื่อ เล่น และ ช้อป ตลอด 24 ชั่วโมง</p>
        </div>
    </section>

    <div class="row justify-content-center my-5">
        <div class="col-md-6">
            <div class="card bg-dark text-light border-0 shadow-lg rounded-4">
                <div class="card-body p-5">
                    <h2 class="mb-4 text-center text-warning"><i class="bi bi-box-arrow-in-right me-2"></i>เข้าสู่ระบบ</h2>

                    <form method="POST" action="{{ route('auth.login-process') }}" onsubmit="showLoadingOnSubmit(event)">
                        @csrf

                        {{-- Username --}}
                        <div class="mb-3">
                            <label for="username" class="form-label">ชื่อผู้ใช้</label>
                            <input type="text" name="username" id="username"
                                class="form-control bg-dark text-light border-secondary" placeholder="ชื่อผู้ใช้... "
                                required autofocus>
                        </div>

                        {{-- Password --}}
                        <div class="mb-4">
                            <label for="password" class="form-label">รหัสผ่าน</label>
                            <input type="password" name="password" id="password"
                                class="form-control bg-dark text-light border-secondary" placeholder="รหัสผ่าน..." required>
                        </div>
                        <div class="mb-3 d-flex justify-content-between">
                            <span class="text-primary">
                                <i class="bi-lock-fill fs-6"></i> RateLimit
                            </span>
                            @if (empty($sitekey->value))
                                <span class="text-secondary">
                                    <i class="bi-unlock-fill fs-6"></i> reCAPTCHA v2
                                </span>
                            @else
                                <span class="text-primary">
                                    <i class="bi-lock-fill fs-6"></i> reCAPTCHA v2
                                </span>
                            @endif
                        </div>
                        @if (session('show_recaptcha')) {{-- ถ้าติด  rate limit แล้ว --}}
                        
                            @if (empty($sitekey->value)) {{-- ถ้าไซต์คีย์มันว่าง ส่งไปก็ไม่ผ่านรอหมดเวลา rate limit --}}
                                
                                <div class="mt-3 text-center text-danger my-3" id="countdown-box">
                                    <p>กรุณารอสักครู่... คุณสามารถพยายามเข้าสู่ระบบใหม่ได้ใน <span id="countdown">60</span>
                                        วินาที</p>
                                </div>
                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-warning fw-bold" disabled> {{-- ปิดไว้ --}}
                                        <i class="bi bi-lightning-fill me-2"></i>เข้าสู่ระบบทันที
                                    </button>
                                </div>
                            @else
                                {{-- ถ้าไซต์คีย์พร้อมใช้งาน ส่งไปให้ google reCAPTCHA v2 verify --}}
                                <!-- โหลด reCAPTCHA v2 -->
                                <div class="g-recaptcha" data-sitekey="{{ $sitekey->value ?? 'sitekey' }}"></div>
                                <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-warning fw-bold">
                                        <i class="bi bi-lightning-fill me-2"></i>เข้าสู่ระบบทันที
                                    </button>
                                </div>
                            @endif
                            
                        @else {{-- ถ้า ไม่ติด rate limit  ไม่ต้องใช้ reCAPTCHA v2 --}}
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-warning fw-bold">
                                    <i class="bi bi-lightning-fill me-2"></i>เข้าสู่ระบบทันที
                                </button>
                            </div>
                        @endif
                        {{-- Link --}}
                        <div class="text-center">
                            <small>ยังไม่มีบัญชี? <a href="{{ route('auth.register') }}" class="text-info">สมัครสมาชิก</a> |
                                <a href="{{ route('auth.forget-password') }}" class="text-info">ลืมรหัสผ่าน</a>
                            </small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        let seconds = 60;
        const countdownEl = document.getElementById("countdown");

        const interval = setInterval(async () => {
            seconds--;
            countdownEl.textContent = seconds;
            if (seconds <= 0) {
                clearInterval(interval);
                await fetch("{{ route('auth.login-process') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        clear: 'เคลียร์ RateLimit',
                    }),

                });
                location.reload(); // รีโหลดหน้า
            }
        }, 1000);
    </script>
@endpush
