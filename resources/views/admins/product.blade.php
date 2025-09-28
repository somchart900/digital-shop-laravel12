@extends('layouts.app')

@section('title', $title ?? '')
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
                    <!-- Search Form -->
                    <div class="mb-4">
                        <form class="d-flex" method="GET"
                            action="{{ route('admin.setting.product', ['category_id' => $category_id]) }}">

                            <input type="text" name="search" class="form-control me-2" placeholder="ค้นหา ชนิดสินค้า..."
                                value="{{ $search ?? '' }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i> ค้นหา
                            </button>
                        </form>
                    </div>
                    <div class="card bg-dark text-white shadow">
                        <div class="card-body">
                            <h4 class="mb-3"><i class="bi bi-gear me-2 text-primary"></i>จัดการ ชนิดสินค้า</h4>
                            <table class="table table-dark table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ชื่อชนิดสินค้า</th>
                                        <th>คำอธิบาย</th>
                                        <th>ราคา</th>
                                        <th>รูป</th>
                                        <th>จํานวนสินค้า</th>
                                        <th>จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($products as $product)
                                        <tr>
                                            <td>
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="deleteProduct('{{ $product->id }}', '{{ $product->name }}')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->description }}</td>
                                            <td>{{ number_format($product->price, 2) }}</td>
                                            <td>
                                                @if ($product->img_link)
                                                    <img src="{{ url('public/uploads/products/' . $product->img_link) }}"
                                                        alt="{{ $product->name }}" width="50" height="50">
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $product->item_count ?? 0 }}</td> <!-- นับ items ด้วย withCount -->
                                            <td>
                                                <a href="{{ route('admin.setting.item', ['category_id' => $category_id, 'product_id' => $product->id]) }}"
                                                    class="btn btn-sm btn-primary mb-1">
                                                    <i class="bi bi-gear"></i> จัดการ
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">ไม่มีสินค้าในหมวดนี้</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            <!-- Pagination -->
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div>
                                    @if ($products->total() > 0)
                                        แสดง {{ $products->firstItem() }} - {{ $products->lastItem() }}
                                        จากทั้งหมด {{ $products->total() }} รายการ
                                    @endif
                                </div>
                                <div>
                                    {{ $products->links('pagination::bootstrap-5') }}
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card bg-dark text-white shadow mt-4">
                        <div class="card-body">
                            <h4 class="mb-3"><i class="bi bi-plus-circle me-2 text-primary"></i>เพิ่มชนิดสินค้า</h4>
                            <div>
                                <form action="{{ route('admin.setting.product.create') }}" method="post"
                                    enctype="multipart/form-data" onsubmit="showLoadingOnSubmit(event)">
                                    @csrf
                                    <input type="hidden" name="category_id" value="{{ $category_id ?? '' }}">
                                    <label for="name" class="form-label">ชื่อชนิดสินค้า</label>
                                    <input type="text" id = "name" name="name" placeholder="ชื่อชนิดสินค้า"
                                        class="form-control mb-2">
                                    <label for="img_link" class="form-label">รูป</label>
                                    <input type="file" id="img_link" name="img_link" class="form-control mb-2">
                                    <label for="description" class="form-label">คําอธิบาย</label>
                                    <textarea id="description" name="description" placeholder="คําอธิบาย" class="form-control mb-2" rows="3" required></textarea>
                                    <label for="price" class="form-label">ราคา</label>
                                    <input type="number" id="price" name="price" placeholder="ราคา"
                                        class="form-control mb-2" required>

                                    <label for="is_featured" class="form-label">แนะนำ?</label>
                                    <select id="is_featured" name="is_featured" class="form-control mb-2">
                                        <option value="1">ใช่</option>
                                        <option value="0">ไม่ใช่</option>
                                    </select>

                                    <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle-fill"></i>
                                        เพิ่มชนิด</button>
                                </form>
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
       function deleteProduct(id, name) {
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
                    fetch('{{ route('admin.setting.product.delete') }}', {
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
