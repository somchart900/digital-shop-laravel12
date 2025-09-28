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
                            <h4 class="mb-3"><i class="bi bi-house-door-fill me-2 text-primary"></i>ตั้งค่าเว็บไซต์</h4>
                            <div class="table-responsive">
                                <table class="table table-dark table-striped table-hover table-bordered align-middle">
                                    <thead>
                                        <tr class="table-success text-dark">
                                            <th>ชื่อเว็บไซต์</th>
                                            <th style="width: 1%; white-space: nowrap;">แก้ไข</th>
                                            <th style="width: 1%; white-space: nowrap;">ลบ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>ชื่อเว็บไซต์: {{ $webname->value ?? '' }}</td>
                                            <td style="width: 1%; white-space: nowrap;">

                                                @if (empty($webname->value))
                                                    <button
                                                        class="btn btn-primary"onclick="editSetting('webname', '{{ $webname->value ?? '' }}')">
                                                        <i class="bi bi-plus me-2"></i> เพิ่ม
                                                    </button>
                                                @else
                                                    <button
                                                        class="btn btn-warning"onclick="editSetting('webname', '{{ $webname->value ?? '' }}')">
                                                        <i class="bi bi-pencil me-2"></i> แก้ไข
                                                    </button>
                                                @endif

                                            </td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (!empty($webname->id))
                                                    <button
                                                        class="btn btn-danger"onclick="deleteSetting('{{ $webname->id ?? '0' }}', '{{ $webname->value ?? '0' }}')">
                                                        <i class="bi bi-trash me-2"></i> ลบ
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
                                            <th>แอดมิน messenger </th>
                                            <th style="width: 1%; white-space: nowrap;">แก้ไข</th>
                                            <th style="width: 1%; white-space: nowrap;">ลบ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>messenger: {{ $messenger->value ?? '' }}</td>
                                            <td style="width: 1%; white-space: nowrap;">

                                                @if (empty($messenger->value))
                                                    <button class="btn btn-primary"
                                                        onclick="editSetting('messenger', '{{ $messenger->value ?? '' }}')">
                                                        <i class="bi bi-plus me-2"></i> เพิ่ม
                                                    </button>
                                                @else
                                                    <button class="btn btn-warning"
                                                        onclick="editSetting('messenger', '{{ $messenger->value ?? '' }}')">
                                                        <i class="bi bi-pencil me-2"></i> แก้ไข
                                                    </button>
                                                @endif

                                            </td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (!empty($messenger->id))
                                                    <button
                                                        class="btn btn-danger"onclick="deleteSetting('{{ $messenger->id ?? '0' }}', 'messenger')">
                                                        <i class="bi bi-trash me-2"></i> ลบ
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
                                            <th>แอดมิน Line </th>
                                            <th style="width: 1%; white-space: nowrap;">แก้ไข</th>
                                            <th style="width: 1%; white-space: nowrap;">ลบ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Line: {{ $line->value ?? '' }}</td>
                                            <td style="width: 1%; white-space: nowrap;">

                                                @if (empty($line->value))
                                                    <button class="btn btn-primary"
                                                        onclick="editSetting('line', '')">
                                                        <i class="bi bi-plus me-2"></i> เพิ่ม
                                                    </button>
                                                @else
                                                    <button class="btn btn-warning"
                                                        onclick="editSetting('line', '{{ $line->value ?? '' }}')">
                                                        <i class="bi bi-pencil me-2"></i> แก้ไข
                                                    </button>
                                                @endif

                                            </td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (!empty($line->id))
                                                    <button
                                                        class="btn btn-danger"onclick="deleteSetting('{{ $line->id ?? '0' }}', 'line')">
                                                        <i class="bi bi-trash me-2"></i> ลบ
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
                                            <th>Follow link social media</th>
                                            <th style="width: 1%; white-space: nowrap;">แก้ไข</th>
                                            <th style="width: 1%; white-space: nowrap;">ลบ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>facebook: {{ $facebook->value ?? '' }}</td>
                                            <td style="width: 1%; white-space: nowrap;">

                                                @if (empty($facebook->value))
                                                    <button class="btn btn-primary"
                                                        onclick="editSetting('facebook', '{{ $facebook->value ?? '' }}')">
                                                        <i class="bi bi-plus me-2"></i> เพิ่ม
                                                    </button>
                                                @else
                                                    <button class="btn btn-warning"
                                                        onclick="editSetting('facebook', '{{ $facebook->value ?? '' }}')">
                                                        <i class="bi bi-pencil me-2"></i> แก้ไข
                                                    </button>
                                                @endif

                                            </td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (!empty($facebook->id))
                                                    <button class="btn btn-danger"
                                                        onclick="deleteSetting('{{ $facebook->id ?? '0' }}', 'facebook')">
                                                        <i class="bi bi-trash me-2"></i> ลบ
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>youtube: {{ $youtube->value ?? '' }}</td>
                                            <td style="width: 1%; white-space: nowrap;">

                                                @if (empty($youtube->value))
                                                    <button class="btn btn-primary"
                                                        onclick="editSetting('youtube', '{{ $youtube->value ?? '' }}')">
                                                        <i class="bi bi-plus me-2"></i> เพิ่ม
                                                    </button>
                                                @else
                                                    <button class="btn btn-warning"
                                                        onclick="editSetting('youtube', '{{ $youtube->value ?? '' }}')">
                                                        <i class="bi bi-pencil me-2"></i> แก้ไข
                                                    </button>
                                                @endif

                                            </td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (!empty($youtube->id))
                                                    <button class="btn btn-danger"
                                                        onclick="deleteSetting('{{ $youtube->id ?? '' }}', 'youtube')">
                                                        <i class="bi bi-trash me-2"></i> ลบ
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>discord: {{ $discord->value ?? '' }}</td>
                                            <td style="width: 1%; white-space: nowrap;">

                                                @if (empty($discord->value))
                                                    <button class="btn btn-primary"
                                                        onclick="editSetting('discord', '{{ $discord->value ?? '' }}')">
                                                        <i class="bi bi-plus me-2"></i> เพิ่ม
                                                    </button>
                                                @else
                                                    <button class="btn btn-warning"
                                                        onclick="editSetting('discord', '{{ $discord->value ?? '' }}')">
                                                        <i class="bi bi-pencil me-2"></i> แก้ไข
                                                    </button>
                                                @endif

                                            </td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (!empty($discord->id))
                                                    <button class="btn btn-danger"
                                                        onclick="deleteSetting('{{ $discord->id ?? '' }}', 'discord')">
                                                        <i class="bi bi-trash me-2"></i> ลบ
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
