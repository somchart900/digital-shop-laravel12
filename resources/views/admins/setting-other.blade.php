@extends('layouts.app')

@section('title', $title ?? '')

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-danger btn-lg mt-4">
                <i class="bi bi-lightning-fill me-2"></i>แดชบอร์ด {{ $webname->value ?? 'ชื่อเว็บไซต์' }} สุดเท่
            </a>
            <p></p>
            <p class="lead">รายละเอียดการจัดการที่จำเป็นทั้งหมด</p>
        </div>
    </section>


    <!-- Admin Dashboard -->
    <section id="admin-dashboard" class="py-5" style="background-color: #1a1a1a; color: #fff;">
        <div class="container-fluid">
            <div class="row">

                <!-- Sidebar -->
                @include('partials.sidebar')


                <!-- Content Area -->
                <div class="col-md-9">
                    <div class="card bg-dark text-white shadow">
                        <div class="card-body">
                            <h4 class="mb-3"><i class="bi bi-house-door-fill me-2 text-primary"></i>ตั้งค่าอื่นๆ</h4>

                            <div class="table-responsive">
                                <table class="table table-dark table-striped table-hover table-bordered align-middle">
                                    <thead>
                                        <tr class="table-success text-dark">
                                            <th>ระบบจัดการ POPUP</th>
                                            <th style="width: 1%; white-space: nowrap;">แก้ไข</th>
                                            <th style="width: 1%; white-space: nowrap;">ลบ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>ข้อความประกาศหลัก: {{ $announce->value ?? '' }}</td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (@empty($announce->value))
                                                    <button class="btn btn-primary"
                                                        onclick="editSetting('announce', '{{ $announce->value ?? '' }}')">
                                                        <i class="bi bi-plus-circle me-2"></i> เปิด
                                                    </button>
                                                @else
                                                    <button class="btn btn-warning"
                                                        onclick="editSetting('announce', '{{ $announce->value ?? '' }}')">
                                                        <i class="bi bi-pencil-fill me-2"></i> แก้ไข
                                                    </button>
                                                @endif
                                            </td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (!empty($announce->value))
                                                    <button class="btn btn-danger"
                                                        onclick="deleteSetting('{{ $announce->id ?? '0' }}', 'ประกาศ เท่ากับ ปิด ')">
                                                        <i class="bi bi-trash-fill me-2"></i> ลบ
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>ข้อความประกาศอัปเดต: {{ $announce2->value ?? '' }}</td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (@empty($announce2->value))
                                                    <button class="btn btn-primary"
                                                        onclick="editSetting('announce2', '{{ $announce2->value ?? '' }}')">
                                                        <i class="bi bi-plus-circle me-2"></i> เพิ่ม
                                                    </button>
                                                @else
                                                    <button class="btn btn-warning"
                                                        onclick="editSetting('announce2', '{{ $announce2->value ?? '' }}')">
                                                        <i class="bi bi-pencil-fill me-2"></i> แก้ไข
                                                    </button>
                                                @endif
                                            </td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (!empty($announce2->value))
                                                    <button class="btn btn-danger"
                                                        onclick="deleteSetting('{{ $announce2->id ?? '0' }}', '')">
                                                        <i class="bi bi-trash-fill me-2"></i> ลบ
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-dark table-striped table-hover table-bordered align-middle">
                                    <thead>
                                        <tr class="table-success text-dark">
                                            <th>เปิด / ปิด ระบบหลังบ้าน</th>
                                            <th style="width: 1%; white-space: nowrap;">เปิด</th>
                                            <th style="width: 1%; white-space: nowrap;">ปิด</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>สถานะ : @if (!empty($enablebackend->value))
                                                    เปิด
                                                @else
                                                    ปิด
                                                @endif
                                            </td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (@empty($enablebackend->value))
                                                    <button class="btn btn-primary"
                                                        onclick="editSetting('enablebackend', 'ป้อนค่า 1 เพื่อเปิด')">
                                                        <i class="bi bi-plus-circle me-2"></i> เปิด
                                                    </button>
                                                @endif
                                            </td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (!empty($enablebackend->value))
                                                    <button class="btn btn-danger"
                                                        onclick="deleteSetting('{{ $enablebackend->id ?? '0' }}', 'ลบออก เพื่อปิด ')">
                                                        <i class="bi bi-trash-fill me-2"></i> ปิด
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-dark table-striped table-hover table-bordered align-middle">
                                    <thead>
                                        <tr class="table-success text-dark">
                                            <th>แจกเครดิต สมาชิก ใหม่</th>
                                            <th style="width: 1%; white-space: nowrap;">เปิด</th>
                                            <th style="width: 1%; white-space: nowrap;">ปิด</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                @if (!empty($bonus->value))
                                                    สถานะ : แจก {{ $bonus->value ?? '' }} เครดิต
                                                @else
                                                    สถานะ : ปิด
                                                @endif
                                            </td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (@empty($bonus->value))
                                                    <button class="btn btn-primary" onclick="editSetting('bonus', '')">
                                                        <i class="bi bi-plus-circle me-2"></i> แจก
                                                    </button>
                                                @endif
                                            </td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (!empty($bonus->value))
                                                    <button class="btn btn-danger"
                                                        onclick="deleteSetting('{{ $bonus->id ?? '0' }}', 'ลบออก เพื่อปิด ')">
                                                        <i class="bi bi-trash-fill me-2"></i> ปิด
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
@push('scripts')
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function editSetting(name, value) {
            Swal.fire({
                title: 'แก้ไขการตั้งค่า',
                html: `
          <input id="swal-input1" class="swal2-input" placeholder="Name" value="${name}" readonly>
          <textarea id="swal-input2" class="swal2-textarea" placeholder="กําหนดค่าตรงนี้" rows="2" cols="23">${value}</textarea>
          `,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'บันทึก',
                cancelButtonText: 'ยกเลิก',
                background: '#1a1a1a',
                color: '#e0ffe0',
                iconColor: '#00ff88',
            }).then((result) => {
                if (result.isConfirmed) {
                    const newValue = document.getElementById('swal-input2').value;
                    if (!newValue) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'กรุณากรอกค่าใหม่',
                            background: '#1a1a1a',
                            color: '#e0ffe0'
                        });
                        return;
                    }

                    // โชว์โหลดดิ้ง
                    Swal.fire({
                        title: 'กำลังบันทึก...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        background: '#1a1a1a',
                        color: '#e0ffe0'
                    });

                    fetch('/admin/setting/update', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                "Accept": "application/json"
                            },
                            body: JSON.stringify({
                                name: name,
                                value: newValue
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'สำเร็จ!',
                                    text: 'บันทึกการเปลี่ยนแปลงแล้ว',
                                    iconColor: '#00ff88',
                                    background: '#1a1a1a',
                                    color: '#e0ffe0'
                                }).then(() => location.reload());
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'เกิดข้อผิดพลาด',
                                    text: data.message || 'ไม่สามารถบันทึกได้',
                                    background: '#1a1a1a',
                                    color: '#ffe0e0'
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'เออเร่อ!',
                                text: error.message,
                                background: '#1a1a1a',
                                color: '#ffe0e0'
                            });
                        });
                }
            });
        }

        function deleteSetting(id, name) {
            Swal.fire({
                title: 'ยืนยันการลบ?',
                text: `คุณแน่ใจหรือไม่ว่าต้องการลบ "${name}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ใช่, ลบเลย!',
                cancelButtonText: 'ยกเลิก',
                background: '#1a1a1a',
                color: '#ffe0e0',
                iconColor: '#ffcc00'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('/admin/setting/delete', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                "Accept": "application/json"
                            },
                            body: JSON.stringify({
                                id: id
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'ลบแล้ว!',
                                    text: data.message,
                                    iconColor: '#00ff88',
                                    background: '#1a1a1a',
                                    color: '#e0ffe0',
                                }).then(() => location.reload());
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'ผิดพลาด',
                                    text: data.message,
                                    iconColor: '#ff4444',
                                    background: '#1a1a1a',
                                    color: '#ffe0e0',
                                });
                            }
                        })
                        .catch(() => {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: 'ไม่สามารถลบข้อมูลได้',
                                iconColor: '#ff4444',
                                background: '#1a1a1a',
                                color: '#ffe0e0',
                            });
                        });
                }
            });
        }
    </script>
@endpush
