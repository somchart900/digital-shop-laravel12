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
                            <h4 class="mb-3"><i class="bi bi-house-door-fill me-2 text-primary"></i>ตั้งค่า API</h4>
                            <div class="table-responsive">
                                <table class="table table-dark table-striped table-hover table-bordered align-middle">
                                    <thead>
                                        <tr class="table-success text-dark">
                                            <th>api byshop</th>
                                            <th style="width: 1%; white-space: nowrap;">แก้ไข</th>
                                            <th style="width: 1%; white-space: nowrap;">ลบ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>api:
                                                @if (auth()->user()->level == 99)
                                                    {{ $byshop->value ?? '' }}
                                                @else
                                                    @if (!empty($byshop->value))
                                                        <span class="text-danger">เฉพาะผุ้ดูแลระบบเท่านั้นที่มองเห็น </span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (@empty($byshop->value))
                                                    <button class="btn btn-primary" onclick="editSetting('byshop', '')">
                                                        <i class="bi bi-plus-circle-fill"></i> เพิ่ม
                                                    </button>
                                                @else
                                                    <button class="btn btn-warning"
                                                        onclick="editSetting('byshop', '{{ auth()->user()->level == 99 ? $byshop->value ?? '' : '***' }}')">
                                                        <i class="bi bi-pencil-fill"></i> แก้ไข
                                                    </button>
                                                @endif
                                            </td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (!empty($byshop->value))
                                                    <button class="btn btn-danger"
                                                        onclick="deleteSetting('{{ $byshop->id ?? '0' }}', 'byshop')">
                                                        <i class="bi bi-trash-fill"></i> ลบ
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                        @if (!empty($byshop->value))
                                            <tr>
                                                <td>
                                                    <span id="creditbyshop" class="text-danger">

                                                    </span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-primary" onclick="checkCredit()">
                                                        <i class="bi bi-credit-card-fill"></i>
                                                        เช็ค
                                                    </button>
                                                </td>
                                                <td></td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <div class="table-responsive mt-3">
                                <table class="table table-dark table-striped table-hover table-bordered align-middle">
                                    <thead>
                                        <tr class="table-success text-dark">
                                            <th>ระบบส่งอีเมล gmail </th>
                                            <th style="width: 1%; white-space: nowrap;">แก้ไข</th>
                                            <th style="width: 1%; white-space: nowrap;">ลบ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>gmail:
                                                @if (auth()->user()->level == 99)
                                                    {{ $email->value ?? '' }}
                                                @else
                                                    @if (!empty($email->value))
                                                        <span class="text-danger">เฉพาะผุ้ดูแลระบบเท่านั้นที่มองเห็น </span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (empty($email->value))
                                                    <button class="btn btn-primary" onclick="editSetting('email', '')">
                                                        <i class="bi bi-plus-circle-fill"></i> เพิ่ม
                                                    </button>
                                                @else
                                                    <button class="btn btn-warning"
                                                        onclick="editSetting('email', '{{ auth()->user()->level == 99 ? $email->value ?? '' : '***' }}')">
                                                        <i class="bi bi-pencil-fill"></i> แก้ไข
                                                    </button>
                                                @endif
                                            </td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (!empty($email->id))
                                                    <button class="btn btn-danger"
                                                        onclick="deleteSetting('{{ $email->id ?? '0' }}', 'email')">
                                                        <i class="bi bi-trash-fill"></i> ลบ
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>apppassword:
                                                @if (auth()->user()->level == 99)
                                                    {{ $apppassword->value ?? '' }}
                                                @else
                                                    @if (!empty($apppassword->value))
                                                        <span class="text-danger">เฉพาะผุ้ดูแลระบบเท่านั้นที่มองเห็น </span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (empty($apppassword->value))
                                                    <button class="btn btn-primary"
                                                        onclick="editSetting('apppassword', '')">
                                                        <i class="bi bi-plus-circle-fill"></i> เพิ่ม
                                                    </button>
                                                @else
                                                    <button class="btn btn-warning"
                                                        onclick="editSetting('apppassword', '{{ auth()->user()->level == 99 ? $apppassword->value ?? '' : '***' }}')">
                                                        <i class="bi bi-pencil-fill"></i> แก้ไข
                                                    </button>
                                                @endif
                                            </td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (!empty($apppassword->id))
                                                    <button class="btn btn-danger"
                                                        onclick="deleteSetting('{{ $apppassword->id ?? '0' }}', 'apppassword')">
                                                        <i class="bi bi-trash-fill"></i> ลบ
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="table-responsive mt-3">
                                <table class="table table-dark table-striped table-hover table-bordered align-middle">
                                    <thead>
                                        <tr class="table-success text-dark">
                                            <th>ระบบแจ้งเตือน telegram</th>
                                            <th style="width: 1%; white-space: nowrap;">แก้ไข</th>
                                            <th style="width: 1%; white-space: nowrap;">ลบ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>bottoken:
                                                @if (auth()->user()->level == 99)
                                                    {{ $bottoken->value ?? '' }}
                                                @else
                                                    @if (!empty($bottoken->value))
                                                        <span class="text-danger">เฉพาะผุ้ดูแลระบบเท่านั้นที่มองเห็น </span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (empty($bottoken->value))
                                                    <button class="btn btn-primary" onclick="editSetting('bottoken', '')">
                                                        <i class="bi bi-plus-circle-fill"></i> เพิ่ม
                                                    </button>
                                                @else
                                                    <button class="btn btn-warning"
                                                        onclick="editSetting('bottoken', '{{ auth()->user()->level == 99 ? $bottoken->value ?? '' : '***' }}')">
                                                        <i class="bi bi-pencil-fill"></i> แก้ไข
                                                    </button>
                                                @endif
                                            </td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (!empty($bottoken->id))
                                                    <button class="btn btn-danger"
                                                        onclick="deleteSetting('{{ $bottoken->id ?? '0' }}', 'bottoken')">
                                                        <i class="bi bi-trash-fill"></i> ลบ
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>chatid:
                                                @if (auth()->user()->level == 99)
                                                    {{ $chatid->value ?? '' }}
                                                @else
                                                    @if (!empty($chatid->value))
                                                        <span class="text-danger">เฉพาะผุ้ดูแลระบบเท่านั้นที่มองเห็น </span>
                                                    @endif
                                                @endif

                                            </td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (empty($chatid->value))
                                                    <button class="btn btn-primary" onclick="editSetting('chatid', '')">
                                                        <i class="bi bi-plus-circle-fill"></i> เพิ่ม
                                                    </button>
                                                @else
                                                    <button class="btn btn-warning"
                                                        onclick="editSetting('chatid', '{{ auth()->user()->level == 99 ? $chatid->value ?? '' : '***' }}')">
                                                        <i class="bi bi-pencil-fill"></i> แก้ไข
                                                    </button>
                                                @endif
                                            </td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (!empty($chatid->id))
                                                    <button class="btn btn-danger"
                                                        onclick="deleteSetting('{{ $chatid->id ?? '' }}', 'chatid')">
                                                        <i class="bi bi-trash-fill"></i> ลบ
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="table-responsive mt-3">
                                <table class="table table-dark table-striped table-hover table-bordered align-middle">
                                    <thead>
                                        <tr class="table-success text-dark">
                                            <th>ระบบป้องกันบอท google recapcha v2</th>
                                            <th style="width: 1%; white-space: nowrap;">แก้ไข</th>
                                            <th style="width: 1%; white-space: nowrap;">ลบ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>secretkey:
                                                @if (auth()->user()->level == 99)
                                                    {{ $secretkey->value ?? '' }}
                                                @else
                                                    @if (!empty($secretkey->value))
                                                        <span class="text-danger">เฉพาะผุ้ดูแลระบบเท่านั้นที่มองเห็น
                                                        </span>
                                                    @endif
                                                @endif

                                            </td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (empty($secretkey->value))
                                                    <button class="btn btn-primary"
                                                        onclick="editSetting('secretkey', '')">
                                                        <i class="bi bi-plus-circle-fill"></i> เพิ่ม
                                                    </button>
                                                @else
                                                    <button class="btn btn-warning"
                                                        onclick="editSetting('secretkey', '{{ auth()->user()->level == 99 ? $secretkey->value ?? '' : '***' }}')">
                                                        <i class="bi bi-pencil-fill"></i> แก้ไข
                                                    </button>
                                                @endif
                                            </td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (!empty($secretkey->id))
                                                    <button class="btn btn-danger"
                                                        onclick="deleteSetting('{{ $secretkey->id ?? '0' }}', 'secretkey')">
                                                        <i class="bi bi-trash-fill"></i> ลบ
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>sitekey:
                                                @if (auth()->user()->level == 99)
                                                    {{ $sitekey->value ?? '' }}
                                                @else
                                                    @if (!empty($sitekey->value))
                                                        <span class="text-danger">เฉพาะผุ้ดูแลระบบเท่านั้นที่มองเห็น
                                                        </span>
                                                    @endif
                                                @endif

                                            </td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (empty($sitekey->value))
                                                    <button class="btn btn-primary" onclick="editSetting('sitekey', '')">
                                                        <i class="bi bi-plus-circle-fill"></i> เพิ่ม
                                                    </button>
                                                @else
                                                    <button class="btn btn-warning"
                                                        onclick="editSetting('sitekey', '{{ auth()->user()->level == 99 ? $sitekey->value ?? '' : '***' }}')">
                                                        <i class="bi bi-pencil-fill"></i> แก้ไข
                                                    </button>
                                                @endif
                                            </td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (!empty($sitekey->id))
                                                    <button class="btn btn-danger"
                                                        onclick="deleteSetting('{{ $sitekey->id ?? '' }}', 'sitekey')">
                                                        <i class="bi bi-trash-fill"></i> ลบ
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

        function checkCredit() {
            showLoadingOnSubmit();
            fetch(`{{ route('admin.setting.check_credit_byshop') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        "Accept": "application/json"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById('creditbyshop').textContent = 'ยอดเครดิต: ' + data.message + ' บาท';
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ!',
                            text: data.message,
                            iconColor: '#ff4444',
                            background: '#1a1a1a',
                            color: '#ffe0e0',
                            confirmButtonText: 'ok'
                        }).then(() => closeLoading());
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'ผิดพลาด',
                            text: data.message,
                            iconColor: '#ff4444',
                            background: '#1a1a1a',
                            color: '#ffe0e0',
                            confirmButtonText: 'ok'
                        }).then(() => closeLoading());
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'ไม่สามารถตรวจสอบยอดเครดิตได้',
                        iconColor: '#ff4444',
                        background: '#1a1a1a',
                        color: '#ffe0e0',
                        confirmButtonText: 'ok'
                    }).then(() => closeLoading());
                });
        }
    </script>
@endpush
