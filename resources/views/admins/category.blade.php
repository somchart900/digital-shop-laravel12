@extends('layouts.app')

@section('title', 'จัดการหมวดสินค้า')
@push('styles')
    <style>
        input,
        textarea {
            background-color: transparent !important;
            ;
            color: #fff !important;
        }

        input::placeholder,
        textarea::placeholder {
            color: #ccc !important;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid py-5">
        <div class="row">

            <!-- Sidebar -->
            @include('partials.sidebar')

            <!-- Main Content -->
            <div class="col-md-9">

                <!-- Search Form -->
                <div class="mb-4">
                    <form class="d-flex" method="GET" action="{{ route('admin.setting.category') }}">
                        <input type="text" name="search" class="form-control me-2" placeholder="ค้นหา category..."
                            value="{{ $search ?? '' }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> ค้นหา
                        </button>
                    </form>
                </div>

                <!-- Category Table -->
                <div class="card bg-dark text-white shadow mb-4">
                    <div class="card-body">
                        <h4 class="mb-3"><i class="bi bi-gear me-2 text-primary"></i>จัดการ หมวดสินค้า</h4>
                        <div class="table-responsive">
                            <table class="table table-dark table-hover table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ชื่อ</th>
                                        <th>คำอธิบาย</th>
                                        <th>รูป</th>
                                        <th>แนะนำ</th>
                                        <th>ชนิดสินค้า</th>
                                        <th>จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($categories as $category)
                                        <tr>
                                            <td>

                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="deleteCategory('{{ $category->id }}', '{{ $category->name }}')">
                                                    <i class="bi bi-trash"></i>
                                                </button>

                                            </td>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->description }}</td>
                                            <td>
                                                @if ($category->img_link)
                                                    <img src="{{ url('public/uploads/categories/' . $category->img_link) }}"
                                                        alt="{{ $category->name }}" width="80" height="80">
                                                @endif
                                            </td>
                                            <td>{{ $category->is_featured ? 'ใช่' : 'ไม่ใช่' }}</td>
                                            <td>{{ $category->product_count }}</td>
                                            <td>
                                                <a href="{{ route('admin.setting.product', ['category_id' => $category->id]) }}"
                                                    class="btn btn-sm btn-primary mb-1">
                                                    <i class="bi bi-gear"></i> จัดการ
                                                </a>

                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">ไม่มีหมวดสินค้า</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div>
                                @if ($categories->total() > 0)
                                    แสดง {{ $categories->firstItem() }} - {{ $categories->lastItem() }}
                                    จากทั้งหมด {{ $categories->total() }} รายการ
                                @endif
                            </div>
                            <div>
                                {{ $categories->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Category Form -->
                <div class="card bg-dark text-white shadow">
                    <div class="card-body">
                        <h4 class="mb-3"><i class="bi bi-plus-circle me-2 text-primary"></i>เพิ่มหมวดสินค้า</h4>
                        <form action="{{ url('/admin/setting/category/create') }}" method="post"
                            enctype="multipart/form-data" onsubmit="showLoadingOnSubmit(event)">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">ชื่อหมวด</label>
                                <input type="text" id="name" name="name" class="form-control"
                                    placeholder="ชื่อหมวด" required>
                            </div>

                            <div class="mb-3">
                                <label for="img_link" class="form-label">รูป</label>
                                <input type="file" id="img_link" name="img_link" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">คำอธิบาย</label>
                                <textarea id="description" name="description" class="form-control" rows="3" placeholder="คำอธิบาย" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="is_featured" class="form-label">แนะนำ?</label>
                                <select id="is_featured" name="is_featured" class="form-select">
                                    <option value="1">ใช่</option>
                                    <option value="0">ไม่ใช่</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-plus-circle-fill"></i> เพิ่มหมวด
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
       function deleteCategory(id, name) {
            Swal.fire({
                title: 'ยืนยันการลบ?',
                text: `คุณต้องการลบสินค้า "${name}" id: ${id} หรือไม่?`,
                icon: 'warning',
                iconColor: '#ff0000',
                background: '#1a1a1a',
                color: '#e0ffe0',
                showCancelButton: true,
                confirmButtonText: 'ลบเลย',
                confirmButtonColor: '#ff4d4d', // ปุ่ม confirm สีแดง
                cancelButtonColor: '#666666', // ปุ่ม cancel สีเทา
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route('admin.setting.category.delete') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                "Accept": "application/json"
                            },
                            body: JSON.stringify({
                                id:id
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'ลบแล้ว',
                                    icon: 'success',
                                    background: '#1a1a1a',
                                    color: '#a0ffa0',
                                    confirmButtonColor: '#28a745', // เขียวเข้ม
                                }).then(() => location.reload());
                            } else {
                                Swal.fire({
                                    title: 'ลบไม่สำเร็จ',
                                    text: data.message ?? 'ไม่ทราบสาเหตุ',
                                    icon: 'error',
                                    background: '#1a1a1a',
                                    color: '#ffcccc',
                                    confirmButtonColor: '#ff4d4d', // แดง
                                });
                            }
                        })
                        .catch(err => Swal.fire({
                            title: 'ลบไม่สำเร็จ',
                            text: err.message,
                            icon: 'error',
                            background: '#1a1a1a',
                            color: '#ffcccc',
                            confirmButtonColor: '#ff4d4d',
                        }));
                }
            });
        }
    </script>
@endpush
