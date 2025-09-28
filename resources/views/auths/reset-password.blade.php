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
            <p class="lead">กรอกรหัส OTP กำหนดรหัสผ่านใหม่</p>
        </div>
    </section>
    <div class="row justify-content-center my-5">
        <div class="col-md-6">
            <div class="card bg-dark text-white border-secondary">
                <div class="card-header bg-black">
                    <h4 class="mb-0"><i class="bi bi-shield-check me-2"></i>ยืนยันรหัส OTP</h4>
                </div>
                <div class="card-body">
                    <p class="text-white">กรอกรหัส OTP ที่ส่งไปยังอีเมล:
                        <strong>{{ session('reset_email') ?? 'อีเมลของคุณ' }}</strong>
                    </p>

                    <form method="POST" action="{{ route('auth.reset-password-process') }}"
                        onsubmit="showLoadingOnSubmit(event)">
                        @csrf
                         <input type="hidden" name="email" value="{{ session('reset_email') }}">
                        <div class="mb-3">
                            <label for="otp" class="form-label">รหัส OTP</label>
                            <input type="text" class="form-control bg-secondary text-white border-dark text-center"
                                id="otp" name="otp" maxlength="6" placeholder="กรอกรหัส 6 หลัก" required
                                autofocus>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">รหัสผ่านใหม่</label>
                            <input type="password" class="form-control bg-secondary text-white border-dark" id="password"
                                name="password" placeholder="รหัสผ่านใหม่..." required>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">ยืนยันรหัสผ่านใหม่</label>
                            <input type="password" class="form-control bg-secondary text-white border-dark"
                                id="password_confirmation" name="password_confirmation" placeholder="ยืนยันรหัสผ่านใหม่..."
                                required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-check-circle me-2"></i>ยืนยันรหัส
                            </button>
                        </div>
                    </form>


                    <div class="text-center mt-2">
                        <a href="{{ route('auth.forget-password') }}" class="text-decoration-none">
                            <i class="bi bi-arrow-left me-1"></i>กลับไปหน้าลืมรหัสผ่าน
                        </a>
                    </div>
                </div>
            </div>
        </div>

    @endsection
