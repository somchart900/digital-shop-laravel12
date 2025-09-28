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
                            <h4 class="mb-3"><i
                                    class="bi bi-house-door-fill me-2 text-primary"></i>ตั้งค่าช่องทางชําระเงิน</h4>
                            <div class="table-responsive">
                                <table class="table table-dark table-striped table-hover table-bordered align-middle">
                                    <thead>
                                        <tr class="table-success text-dark">
                                            <th>ทรูมันนี่</th>
                                            <th style="width: 1%; white-space: nowrap;">แก้ไข</th>
                                            <th style="width: 1%; white-space: nowrap;">ลบ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Truemoney: {{ $truemoney->value ?? '' }}</td>
                                            <td style="width: 1%; white-space: nowrap;">

                                                @if (empty($truemoney->value))
                                                    <button class="btn btn-primary"
                                                        onclick="editSetting('truemoney', '{{ $truemoney->value ?? '' }}')">
                                                        <i class="bi bi-plus-lg"></i> เพิ่ม
                                                    </button>
                                                @else
                                                    <button class="btn btn-warning"
                                                        onclick="editSetting('truemoney', '{{ $truemoney->value ?? '' }}')">
                                                        <i class="bi bi-pencil"></i>
                                                        แก้ไข
                                                    </button>
                                                @endif

                                            </td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (!empty($truemoney->value))
                                                    <button class="btn btn-danger"
                                                        onclick="deleteSetting('{{ $truemoney->id ?? '0' }}', 'truemoney')">
                                                        <i class="bi bi-trash"></i> ลบ
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            {{-- bank --}}
                            <div class="table-responsive mt-3">
                                <table class="table table-dark table-striped table-hover table-bordered align-middle">
                                    <thead>
                                        <tr class="table-success text-dark">
                                            <th>ธนาคาร</th>
                                            <th style="width: 1%; white-space: nowrap;">แก้ไข</th>
                                            <th style="width: 1%; white-space: nowrap;">ลบ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>ชื่อธนาคาร: {{ $bankname->value ?? '' }}</td>
                                            <td style="width: 1%; white-space: nowrap;">

                                                @if (empty($bankname->value))
                                                    <button
                                                        class="btn btn-primary"onclick="editSettingBank('bankname', '{{ $bankname->value ?? '' }}')">
                                                        <i class="bi bi-plus-lg"></i> เพิ่ม
                                                    </button>
                                                @else
                                                    <button
                                                        class="btn btn-warning"onclick="editSettingBank('bankname', '{{ $bankname->value ?? '' }}')">
                                                        <i class="bi bi-pencil"></i> แก้ไข
                                                    </button>
                                                @endif


                                            </td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (!empty($bankname->value))
                                                    <button class="btn btn-danger"
                                                        onclick="deleteSetting('{{ $bankname->id ?? '0' }}', 'bankname')">
                                                        <i class="bi bi-trash"></i> ลบ
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>เลขบัญชี: {{ $accountnumber->value ?? '' }}</td>
                                            <td style="width: 1%; white-space: nowrap;">

                                                @if (empty($accountnumber->value))
                                                    <button class="btn btn-primary text-warning"
                                                        onclick="editSetting('accountnumber', '{{ $accountnumber->value ?? '' }}')">
                                                        <i class="bi bi-plus-lg"></i> เพิ่ม
                                                    </button>
                                                @else
                                                    <button class="btn btn-warning"
                                                        onclick="editSetting('accountnumber', '{{ $accountnumber->value ?? '' }}')">
                                                        <i class="bi bi-pencil"></i> แก้ไข
                                                    </button>
                                                @endif



                                            </td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (!empty($accountnumber->value))
                                                    <button class="btn btn-danger"
                                                        onclick="deleteSetting('{{ $accountnumber->id ?? '' }}', 'accountnumber')">
                                                        <i class="bi bi-trash"></i> ลบ
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>ชื่อบัญชี: {{ $accountname->value ?? '' }}</td>
                                            <td style="width: 1%; white-space: nowrap;">

                                                @if (empty($accountname->value))
                                                    <button class="btn btn-primary"
                                                        onclick="editSetting('accountname', '{{ $accountname->value ?? '' }}')">
                                                        <i class="bi bi-plus-lg"></i> เพิ่ม
                                                    </button>
                                                @else
                                                    <button class="btn btn-warning"
                                                        onclick="editSetting('accountname', '{{ $accountname->value ?? '' }}')">
                                                        <i class="bi bi-pencil"></i> แก้ไข
                                                    </button>
                                                @endif


                                            </td>
                                            <td style="width: 1%; white-space: nowrap;">
                                                @if (!empty($accountname->id))
                                                    <button class="btn btn-danger"
                                                        onclick="deleteSetting('{{ $accountname->id ?? '' }}', 'accountname')">
                                                        <i class="bi bi-trash"></i> ลบ
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

        function editSettingBank(name, value) {
            Swal.fire({
                title: 'แก้ไขการตั้งค่า',
                html: `
            <select id="swal-input2" name="setting-value" class="form-control" required>
                <option value="">-- เลือกธนาคาร --</option>
                <option value="099 = พร้อมเพย์">099 = พร้อมเพย์</option>
                <option value="002 = ธ.กรุงเทพ">002 = ธ.กรุงเทพ</option>
                <option value="004 = ธ.กสิกรไทย">004 = ธ.กสิกรไทย</option>
                <option value="006 = ธ.กรุงไทย">006 = ธ.กรุงไทย</option>
                <option value="011 = ธ.ทหารไทยธนชาต">011 = ธ.ทหารไทยธนชาต</option>
                <option value="014 = ธ.ไทยพาณิชย์">014 = ธ.ไทยพาณิชย์</option>
                <option value="025 = ธ.กรุงศรีอยุธยา">025 = ธ.กรุงศรีอยุธยา</option>
                <option value="069 = ธ.เกียรตินาคินภัทร">069 = ธ.เกียรตินาคินภัทร</option>
                <option value="022 = ธ.ซีไอเอ็มบีไทย">022 = ธ.ซีไอเอ็มบีไทย</option>
                <option value="067 = ธ.ทิสโก้">067 = ธ.ทิสโก้</option>
                <option value="024 = ธ.ยูโอบี">024 = ธ.ยูโอบี</option>
                <option value="071 = ธ.ไทยเครดิตเพื่อรายย่อย">071 = ธ.ไทยเครดิตเพื่อรายย่อย</option>
                <option value="073 = ธ.แลนด์ แอนด์ เฮ้าส์">073 = ธ.แลนด์ แอนด์ เฮ้าส์</option>
                <option value="070 = ธ.ไอซีบีซี (ไทย)">070 = ธ.ไอซีบีซี (ไทย)</option>
                <option value="034 = ธ.เพื่อการเกษตรและสหกรณ์การเกษตร">034 = ธ.เพื่อการเกษตรและสหกรณ์การเกษตร</option>
                <option value="030 = ธ.ออมสิน">030 = ธ.ออมสิน</option>
                <option value="033 = ธ.อาคารสงเคราะห์">033 = ธ.อาคารสงเคราะห์</option>
                <option value="066 = ธ.อิสลามแห่งประเทศไทย">066 = ธ.อิสลามแห่งประเทศไทย</option>
            </select>
          `,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'บันทึก',
                cancelButtonText: 'ยกเลิก',
                background: '#1a1a1a',
                color: '#e0ffe0',
                iconColor: '#00ff88',
                preConfirm: () => {
                    const newValue = document.getElementById('swal-input2').value;
                    if (!newValue) {
                        Swal.showValidationMessage('กรุณาเลือกธนาคาร');
                        return false;
                    }

                    return fetch('/admin/setting/update', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                name: name,
                                value: newValue
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status !== 'success') {
                                throw new Error(data.message || 'เกิดข้อผิดพลาด');
                            }
                            return data;
                        })
                        .catch(error => {
                            Swal.showValidationMessage(error.message);
                        });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ!',
                        text: 'บันทึกการเปลี่ยนแปลงแล้ว',
                        iconColor: '#00ff88',
                        background: '#1a1a1a',
                        color: '#e0ffe0',
                    }).then(() => location.reload());
                }
            });
        }
    </script>
@endpush
