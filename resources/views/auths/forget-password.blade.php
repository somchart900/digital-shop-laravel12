@extends('layouts.app')

@section('title', $title ?? '')

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <a href="{{ route('auth.login') }}" class="btn btn-danger btn-lg mt-4">
                <i class="bi bi-lightning-fill me-2"></i>ศูนย์ช่วยเหลือ {{ $webname->value ?? 'ชื่อเว็บไซต์' }} สุดเท่
            </a>
            <p></p>
            <p class="lead">รับรหัส OTP สำหรับรีเซตรหัสผ่าน</p>
        </div>
    </section>
    <div class="row justify-content-center my-5">
        <div class="col-md-6">
            <div class="card bg-dark text-white border-secondary">
                <div class="card-header bg-black">
                    <h4 class="mb-0"><i class="bi bi-key me-2"></i>ลืมรหัสผ่าน</h4>
                </div>
                <div class="card-body">
                    <p class="text-white">กรอกอีเมลของคุณเพื่อรับรหัส OTP สำหรับรีเซตรหัสผ่าน</p>
                    @if (empty($apppassword->value))
                        <div class="alert alert-danger" role="alert"></div>
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <span>โปรดติดต่อผู้ดูแลระบบเพื่อเปิดใช้งานฟังก์ชั่นนี้</span>
                    @else
                        <form method="POST" action="{{ route('auth.forget-password-process') }}"
                            onsubmit="showLoadingOnSubmit(event)">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">อีเมล</label>
                                <input type="email" class="form-control bg-secondary text-white border-dark"
                                    id="email" name="email" value="" placeholder="กรอกอีเมลของคุณ" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-white">Captcha</label>
                                <div class="d-flex align-items-center gap-2">
                                    {{-- <img src="{{ captcha_src() }}" alt="captcha"> ค่าเริ่มต้นดูยาก --}}
                                    {{-- <img src="{{ captcha_src('flat') }}" alt="captcha"> แบบดูง่าย --}}
                                    <img src="{{ captcha_src('math') }}" alt="captcha" class="border rounded"
                                        style="height: 50px;">
                                    <input type="text" name="captcha" class="form-control bg-secondary text-white" placeholder="กรอกคำตอบ"
                                        required>
                                </div>
                                <small class="text-white">ตอบโจทย์ตัวเลขด้านบน</small>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-danger btn-lg">
                                    <i class="bi bi-send me-2"></i>ส่งรหัส OTP
                                </button>
                            </div>
                        </form>
                    @endif
                    <div class="text-center mt-3">
                        <a href="{{ route('auth.login') }}" class="text-decoration-none">
                            <i class="bi bi-arrow-left me-1"></i>กลับไปหน้าเข้าสู่ระบบ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
