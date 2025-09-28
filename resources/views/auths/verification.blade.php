@extends('layouts.app')

@section('title', $title ?? '')

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <a href="{{ route('auth.login') }}" class="btn btn-danger btn-lg mt-4">
                <i class="bi bi-lightning-fill me-2"></i>ศูนย์ยืนยัน {{ $webname->value ?? 'ชื่อเว็บไซต์' }} สุดเท่
            </a>
            <p></p>
            <p class="lead">รับรหัส OTP ยืนยันอีเมล</p>
        </div>
    </section>
    <div class="row justify-content-center my-5">
        <div class="col-md-6">
            <div class="card bg-dark text-white border-secondary">
                <div class="card-header bg-black">
                    <h4 class="mb-0"><i class="bi bi-shield-check me-2"></i>ยืนยันอีเมล</h4>
                </div>
                <div class="card-body">
                    @if (empty($apppassword->value))
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <span>โปรดติดต่อผู้ดูแลระบบเพื่อเปิดใช้งานฟังก์ชั่นนี้</span>
                        </div>
                    @else
                        <form method="POST" action="{{ route('auth.verification-request') }}"
                            onsubmit="showLoadingOnSubmit(event)">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">อีเมล</label>
                                <input type="email" class="form-control bg-secondary text-white border-dark"
                                    id="email" name="email" value="{{ auth()->user()->email }}"
                                    placeholder="กรอกอีเมลของคุณ" readonly>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-danger btn-lg">
                                    <i class="bi bi-send me-2"></i>ส่งรหัส OTP
                                </button>
                            </div>
                        </form>
                    @endif
                    <div class="text-center mt-3">
                        <a href="{{ route('user.profile') }}" class="text-decoration-none">
                            <i class="bi bi-arrow-left me-1"></i>กลับไปโปรไฟล์
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
