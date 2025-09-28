@extends('layouts.app')

@section('title', $title ?? '')

@section('content')
    <!-- Hero Section: Topup -->
    <section class="hero">
        <div class="container text-center text-white">
            <a href="{{ route('user.topup') }}" class="btn btn-danger btn-lg mt-4">
                <i class="bi bi-lightning-fill me-2"></i>ศูนย์เติมเครดิต {{ $webname->value ?? 'ชื่อเว็บไซต์' }} สุดเท่
            </a>
            <p></p>
            <p class="lead">เลือกวิธีที่สะดวกที่สุด เติมแล้วใช้งานได้ทันที</p>
        </div>
    </section>

    <!-- Top-up Section -->
    <section id="topup" class="py-5 text-white" style="background-color: #1b1b1b;">
        <div class="container">
            <div class="mb-4 text-center">
                <h4>ยอดเครดิตของคุณ: <span class="text-success fw-bold"><i class="bi bi-currency-dollar"></i>
                        {{ number_format($credits, 2) }}</span></h4>
            </div>

            <ul class="nav nav-tabs mb-4 justify-content-center" id="topupTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="truewallet-tab" data-bs-toggle="tab" data-bs-target="#truewallet"
                        type="button" role="tab">
                        <i class="bi bi-wallet2"></i> ทรูมันนี่ อั่งเปา
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="bank-tab" data-bs-toggle="tab" data-bs-target="#bank" type="button"
                        role="tab">
                        <i class="bi bi-bank"></i> โอนผ่านธนาคาร
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="topupTabsContent">

                <!-- TrueMoney Tab -->
                <div class="tab-pane fade show active" id="truewallet" role="tabpanel">
                    <h5 class="text-info">ทรูมันนี่ อั่งเปา</h5>
                    @if (!empty($data['truemoney']))
                        <div class="mb-3">
                            <h5 class="text-warning">วิธีสร้างลิงก์ซองของขวัญทรูมันนี่</h5>
                            <ol>
                                <li>
                                    <strong> เข้าสู่แอปพลิเคชัน TrueMoney Wallet ในโทรศัพท์มือถือของคุณ </strong>
                                </li>
                                <li>
                                    <strong> เลือกเมนู "ส่งซองของขวัญ หรือ สร้าางซองของขวัญ" </strong>
                                </li>
                                <li>
                                    <strong> จำนวนเงิน: ตั้งแต่ 10 บาท ขึ้นไป
                                        จำนวนผู้รับ: ระบุจำนวนผู้รับ 1 คน
                                    </strong>
                                </li>
                                <li>
                                    <strong>
                                        สร้างซอง:ตรวจสอบรายละเอียดให้ถูกต้อง แล้วกดปุ่ม "สร้างซอง" หรือ "ยืนยัน"
                                    </strong>
                                </li>
                                <li>
                                    <strong>
                                        คัดลอกลิงก์ซองของขวัญ แล้วนำมาวางในช่องด้านล่างนี้
                                    </strong>
                                </li>
                            </ol>
                        </div>
                        <form action="{{ route('user.topup.redeem') }}" method="post">
                            <div class="mb-3">
                                @csrf
                                <label for="truemoney" class="form-label">ลิงก์ซองของขวัญ</label>
                                <textarea class="form-control bg-dark text-white border-secondary" id="truemoney" name="truemoney" rows="4"
                                    placeholder="วางลิงก์ที่ได้จากแอป TrueMoney Wallet" required></textarea>
                            </div>
                            <button class="btn btn-danger"><i class="bi bi-send-check"></i> ส่งลิงก์</button>
                        </form>
                    @else
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            กรุณาตรวจติดต่อผู้ดูแลระบบเพื่อเปิดใช้งานฟังก์ชั่นนี้
                        </div>
                    @endif
                </div>

                <!-- Bank Transfer Tab -->
                <div class="tab-pane fade" id="bank" role="tabpanel">
                    <h5 class="text-info">รายละเอียดบัญชีธนาคาร</h5>
                    @if ($data['apikey'] != 'apikey')
                        <div class="mb-3">
                            <ul class="list-unstyled">
                                <li><strong>ธนาคาร:</strong> {{ $data['bankname'] ?? 'กรุณาติดต่อผู้ดูแลระบบ' }}</li>
                                <li><strong>ชื่อบัญชี:</strong> {{ $data['accountname'] ?? 'กรุณาติดต่อผู้ดูแลระบบ' }}</li>
                                <li>
                                    <strong>เลขบัญชี:</strong>
                                    <span id="bankNumber"
                                        class="text-warning">{{ $data['accountnumber'] ?? '0000-0000-0000' }}</span>
                                    <button class="btn btn-outline-light btn-sm ms-2" onclick="copyBankNumber()">
                                        <i class="bi bi-clipboard"></i> คัดลอก
                                    </button>
                                </li>
                            </ul>
                            <p class="text-info">หมายเหตุ: กรุณอัพโหลดสลีปโอนเงินที่ถูกต้องภายใน 20 นาที หลังจากโอนเงิน</p>
                        </div>
                        <div class="mb-3">
                            <form id="myForm" action="{{ route('user.topup.checkslip') }}" method="post"
                                enctype="multipart/form-data" onsubmit="showLoadingOnSubmit(event)">
                                @csrf
                                <label for="slipUpload" class="form-label">อัปโหลดสลิปโอนเงิน</label>
                                <input type="file" id="slip" name="image" accept="image/*"
                                    onchange="previewAndScanImage(event)">
                                <div id="imagePreviewContainer" class="mb-3" style="display: none;">
                                    <h5>ตัวอย่างสลีปที่อัพโหลด:</h5>
                                    <img id="imagePreview" class="img-fluid" style="max-width: 100%; height: auto;" />
                                </div>
                                <input type="hidden" name="qrText" id="qrText"> <!-- เก็บค่า QR Code ที่อ่านได้ -->
                        </div>
                        <button class="btn btn-primary"><i class="bi bi-cloud-upload"></i> ส่งสลิป</button>
                        </form>
                    @else
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            กรุณาตรวจติดต่อผู้ดูแลระบบเพื่อเปิดใช้งานฟังก์ชั่นนี้
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>



@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.3.1/dist/jsQR.min.js"></script>
    <script>
        function previewAndScanImage(event) {
            let file = event.target.files[0];
            if (!file) return;

            let reader = new FileReader();
            reader.onload = function(e) {
                let img = document.getElementById("imagePreview");
                img.src = e.target.result;
                document.getElementById("imagePreviewContainer").style.display = "block";

                scanQRCode(img.src); // ส่งรูปไปสแกน QR Code
            };
            reader.readAsDataURL(file);
        }

        function scanQRCode(imageSrc) {
            let img = new Image();
            img.src = imageSrc;
            img.onload = function() {
                let canvas = document.createElement("canvas");
                let ctx = canvas.getContext("2d");
                canvas.width = img.width;
                canvas.height = img.height;
                ctx.drawImage(img, 0, 0, img.width, img.height);

                let imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                let qrCode = jsQR(imageData.data, imageData.width, imageData.height);

                if (qrCode) {
                    document.getElementById("qrText").value = qrCode.data; // เก็บค่า QR Code
                    // alert("พบ QR Code: " + qrCode.data);
                } else {
                    document.getElementById("qrText").value = "";
                    Swal.fire({
                        icon: 'error',
                        title: 'ไม่พบ QR Code',
                        iconColor: '#ff0000',
                        background: '#1a1a1a',
                        color: '#e0ffe0',
                        text: 'กรุณาเลือกไฟล์ใหม่',
                        confirmButtonText: 'ตกลง'
                    }).then(() => {
                        location.reload(); // รีโหลดหน้าใหม่เมื่อกดตกลง
                    });
                }
            };
        }

        function checkQRCode() {
            let qrValue = document.getElementById("qrText").value;
            if (!qrValue) {
                Swal.fire({
                    icon: 'warning',
                    title: 'ไม่มี QR Code',
                    iconColor: '#ff0000',
                    background: '#1a1a1a',
                    color: '#e0ffe0',
                    text: 'กรุณาเลือกไฟล์ที่มี QR Code',
                    confirmButtonText: 'ตกลง'
                });
                return false; // ป้องกันการส่งฟอร์มหากไม่มี QR Code
            }
            return true;
        }

        function copyBankNumber() {
            const bankNumber = document.getElementById("bankNumber").innerText.trim();
            navigator.clipboard.writeText(bankNumber).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'คัดลอกแล้ว!',
                    iconColor: '#ff0000',
                    background: '#1a1a1a',
                    color: '#e0ffe0',
                    text: '{{ $data['accountnumber'] ?? '0000000000' }}',
                    timer: 1500,
                    showConfirmButton: false
                });
            });
        }
    </script>
@endpush
