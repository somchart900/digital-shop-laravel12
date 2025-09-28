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
                            <h4 class="mb-4"><i class="bi bi-people-fill me-2 text-success"></i>จัดการสมาชิก</h4>

                            <!-- Search Bar -->
                            <form method="GET" action="{{ url('/admin/user') }}" class="mb-3">
                                <div class="input-group">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        class="form-control bg-dark text-white border-secondary"
                                        placeholder="ค้นหาชื่อหรืออีเมล...">
                                    <button class="btn btn-success" type="submit"><i class="bi bi-search"></i>
                                        ค้นหา</button>
                                </div>
                            </form>

                            <!-- Users Table -->
                            <div class="table-responsive">
                                <table class="table table-dark table-striped table-hover align-middle text-center">
                                    <thead>
                                        <tr class="table-success text-dark">
                                            <th>#</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>ยืนยัน Email</th>
                                            <th>level</th>
                                            <th>Credit</th>
                                            <th>วันที่สมัคร</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users as $user)
                                            @if (Auth::user()->level != '99')
                                                @if ($user->level == '99')
                                                    @continue
                                                @endif
                                            @endif
                                            <tr>
                                                <td>{{ $user->id }}
                                                    <button class="btn btn-sm btn-danger"
                                                        onclick="deleteUser('{{ $user->id }}', '{{ $user->username }}')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    @if (Auth::user()->level == 99)                                                  
                                                            {{ $user->username }}
                                                        </a>
                                                    @else
                                                        {{  maskUsername($user->username) }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (Auth::user()->level == 99)
                                                        {{ $user->email }}
                                                    @else
                                                        {{ maskEmail($user->email) }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($user->email_verified_at)
                                                        <span class="badge bg-success">ยืนยันแล้ว</span>
                                                    @else
                                                        <span class="badge bg-danger">ยังไม่ยืนยัน</span>
                                                    @endif
                                                </td>
                                                <td>{{ $user->level }}
                                                    <button class="btn btn-sm btn-warning"
                                                        onclick="editUserLevel('{{ $user->id }}', '{{ $user->level }}')"><i
                                                            class="bi bi-pencil"></i></button>
                                                </td>
                                                <td>
                                                    {{ number_format(optional($user->credit)->amount ?? 0, 2) }}
                                                    <button class="btn btn-sm btn-warning"
                                                        onclick="editCredit('{{ $user->id }}', '{{ $user->credit->amount ?? 0 }}')">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                </td>
                                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6">ไม่มีข้อมูลสมาชิก</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-3">
                                {{ $users->links('pagination::bootstrap-5') }}
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
        // ตั้งค่า fetch ให้แนบ CSRF อัตโนมัติ
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function deleteUser(userId, username) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: `ลบ "${username}" และไม่สามารถกู้คืนได้`,
                icon: 'warning',
                iconColor: '#00ff88', // สีเขียวสะท้อนแสง
                background: '#1a1a1a',
                color: '#e0ffe0',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'ลบ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("{{ route('admin.user.delete') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": csrfToken,
                                "Accept": "application/json"
                            },
                            body: JSON.stringify({
                                user_id: userId
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === "success") {
                                Swal.fire({
                                    icon: "success",
                                    title: data.message,
                                    iconColor: '#00ff88',
                                    background: '#1a1a1a',
                                    color: '#e0ffe0',
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: data.message,
                                    iconColor: '#ff4444',
                                    background: '#1a1a1a',
                                    color: '#ffe0e0',
                                    confirmButtonText: 'ปิด'
                                });
                            }
                        })
                        .catch(err => {
                            Swal.fire({
                                title: "เกิดข้อผิดพลาด",
                                text: "ไม่สามารถลบได้",
                                icon: "error",
                                iconColor: '#ff0000',
                                background: '#1a1a1a',
                                color: '#e0ffe0',
                            });
                        });
                }
            });
        }

        function editCredit(userId, currentCredit) {
            Swal.fire({
                title: 'ปรับเครดิต',
                input: 'number',
                inputLabel: 'ใส่ยอดเครดิตใหม่',
                inputValue: currentCredit,
                inputAttributes: {
                    min: 0,
                    step: 0.01
                },
                background: '#1a1a1a',
                color: '#e0ffe0',
                icon: 'info',
                iconColor: '#00ff88',
                showCancelButton: true,
                confirmButtonText: 'บันทึก',
                cancelButtonText: 'ยกเลิก',
                confirmButtonColor: '#00cc88',
                cancelButtonColor: '#6c757d',
                preConfirm: (newCredit) => {
                    if (newCredit === "" || isNaN(newCredit)) {
                        Swal.showValidationMessage('กรุณาใส่ตัวเลขให้ถูกต้อง');
                        return false;
                    }

                    return fetch("{{ route('admin.user.update.credit') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                "Accept": "application/json"
                            },
                            body: JSON.stringify({
                                user_id: userId,
                                credit: newCredit
                            })
                        })
                        .then(res => res.json())
                        .catch(() => {
                            Swal.showValidationMessage('เกิดข้อผิดพลาดในการส่งข้อมูล');
                        });
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    const data = result.value;

                    Swal.fire({
                        icon: data.status === 'success' ? 'success' : 'error',
                        title: data.message,
                        iconColor: data.status === 'success' ? '#00ff88' : '#ff4444', // แยกสีเขียว/แดง
                        background: '#1a1a1a',
                        color: '#e0ffe0',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        if (data.status === 'success') {
                            window.location.reload();
                        }
                    });
                }
            });
        }

        function editUserLevel(userId, currentLevel) {
            Swal.fire({
                title: 'เปลี่ยนระดับผู้ใช้',
                input: 'number',
                inputLabel: 'กรอกระดับใหม่',
                inputValue: currentLevel,
                inputAttributes: {
                    min: 1, // ระดับต่ำสุด
                    step: 1 // เพิ่มทีละ 1
                },
                background: '#1a1a1a',
                color: '#e0ffe0',
                icon: 'info',
                iconColor: '#00ff88',
                showCancelButton: true,
                confirmButtonText: 'บันทึก',
                cancelButtonText: 'ยกเลิก',
                confirmButtonColor: '#00cc88',
                cancelButtonColor: '#6c757d',
                preConfirm: (newLevel) => {
                    const levelNum = parseInt(newLevel, 10);
                    if (isNaN(levelNum) || levelNum < 1) {
                        Swal.showValidationMessage('กรุณากรอกเลขระดับผู้ใช้ให้ถูกต้อง');
                        return false;
                    }

                    return fetch('{{ route('admin.user.update.level') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                "Accept": "application/json"
                            },
                            body: JSON.stringify({
                                user_id: userId,
                                level: levelNum
                            })
                        })
                        .then(res => res.json())
                        .catch(() => {
                            Swal.showValidationMessage('เกิดข้อผิดพลาดในการส่งข้อมูล');
                        });
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    const data = result.value;

                    Swal.fire({
                        icon: data.status === 'success' ? 'success' : 'error',
                        title: data.message,
                        iconColor: data.status === 'success' ? '#00ff88' : '#ff4444',
                        background: '#1a1a1a',
                        color: '#e0ffe0',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        if (data.status === 'success') {
                            window.location.reload();
                        }
                    });
                }
            });
        }
    </script>
@endpush
