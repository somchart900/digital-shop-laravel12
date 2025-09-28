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
            <p class="lead">กรอกรหัส OTP เพื่อยืนยัน</p>
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
                        <strong>{{ auth()->user()->email ?? 'อีเมล-ของคุณ' }}</strong>
                    </p>

                    <form method="POST" action="{{ route('auth.verification-process') }}"
                        onsubmit="showLoadingOnSubmit(event)">
                        @csrf
                       <input type="hidden" name="email" value="{{ auth()->user()->email ?? 'อีเมล-ของคุณ' }}">
                        <div class="mb-3">
                            <label for="otp" class="form-label">รหัส OTP  </label>
                            <input type="text" class="form-control bg-secondary text-white border-dark text-center"
                                id="otp" name="otp" maxlength="6" placeholder="กรอกรหัส 6 หลัก" required
                                autofocus>
                        </div>
                       
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-check-circle me-2"></i>ยืนยัน
                            </button>
                        </div>
                    </form>


                    <div class="text-center mt-2">
                        <a href="{{ route('user.profile') }}" class="text-decoration-none">
                            <i class="bi bi-arrow-left me-1"></i>กลับไปโปรไฟล์
                        </a>
                    </div>
                </div>
            </div>
        </div>

    @endsection
