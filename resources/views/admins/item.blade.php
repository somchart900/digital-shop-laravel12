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
                    <a href="{{ route('admin.setting.product', ['category_id' => $category_id]) }}"
                        class="btn btn-sm btn-primary mb-1"> <i class="bi bi-arrow-left"></i> กลับ
                    </a>
                    <div class="card bg-dark text-white shadow">
                        <div class="card-body">
                            <h4 class="mb-3"><i class="bi bi-plus-circle me-2 text-primary"></i>จัดการ สินค้า</h4>
                            <table class="table table-dark table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ชื่อสินค้า</th>
                                        <th>คำอธิบาย</th>
                                        <th>ราคา</th>
                                        <th>รูป</th>
                                        <th>จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($items as $item)
                                        <tr>
                                            <td> {{ $item->id }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->description }}</td>
                                            <td>{{ number_format($item->price, 2) }}</td>
                                            <td>
                                                @if ($item->img_link)
                                                    <img src="{{ url('public/uploads/products/' . $item->img_link) }}"
                                                        alt="{{ $item->name }}" width="50" height="50">
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="deleteItem('{{ $item->id }}', '{{ $item->name }}')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
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
                                    @if ($items->total() > 0)
                                        แสดง {{ $items->firstItem() }} - {{ $items->lastItem() }}
                                        จากทั้งหมด {{ $items->total() }} รายการ
                                    @endif
                                </div>
                                <div>
                                    {{ $items->links('pagination::bootstrap-5') }}
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card bg-dark text-white shadow mt-4">
                        <div class="card-body">
                            <h4 class="mb-3"><i class="bi bi-plus-circle me-2 text-primary"></i>เพิ่มสินค้า</h4>
                            <div>
                                <form id="itemForm" action="{{ route('admin.setting.item.create') }}" method="post"
                                    onsubmit="showLoadingOnSubmit(event)">
                                    @csrf
                                    <input type="hidden" name="category_id" value="{{ $category_id ?? 'no category' }}">
                                    <input type="hidden" name="product_id" value="{{ $product->id ?? 'no product' }}">
                                    <label for="name" class="form-label">ชื่อสินค้า</label>
                                    <input type="text" id = "name" name="name"
                                        value="{{ $product->name ?? 'no name' }}" class="form-control mb-2" readonly>
                                    <input type="hidden" id="description" name="description"
                                        value="{{ $product->description ?? 'no description' }}">
                                    <label for="price" class="form-label">ราคา </label>
                                    <input type="number" id="price" name="price"
                                        value="{{ $product->price ?? '0' }}" class="form-control mb-2" readonly>
                                    <input type="hidden" id="img_link" name="img_link"
                                        value="{{ $product->img_link ?? 'no img_link' }}">
                                    <label for="code" class="form-label">รหัสสินค้า </label>
                                    <input type="text" id="code" name="code" class="form-control mb-2"
                                        placeholder="รหัสสินค้า" required>
                                    <label for="article" class="form-label">ข้อมูลเชิงลึก</label>
                                    {{-- <textarea id="article" name="article" placeholder="ข้อมูลเชิงลึก" class="form-control mb-2" rows="3"></textarea> --}}
                                    <textarea id="article" name="article" style="display:none;"></textarea>
                                    {{--  Quill editor --}}
                                    <div id="editor" style="height: 200px; "></div>

                                    <label for="youtube" class="form-label">Youtube Link ลิ้งค์ตัวอย่าง ถ้ามี</label>
                                    <input type="text" id="youtube" name="youtube" class="form-control mb-2"
                                        placeholder="Youtube Link  ถ้ามี">
                                    <label for="external_link" class="form-label">External Link ลิ้งค์ตัวอย่าง ถ้ามี</label>
                                    <input type="text" id="external_link" name="external_link" class="form-control mb-2"
                                        placeholder="website Link  ถ้ามี">

                                    <label for="total" class="form-label">ระบุจํานวน (กรณีโค้ดเดียวกันหลายครั้ง เช่น
                                        ลิ้งดาวน์โหลด )</label>
                                    <input type="number" id="total" name="total" class="form-control mb-2"
                                        value="1">
                                    <button type="submit" class="btn btn-primary"><i
                                            class="bi bi-plus-circle me-2"></i>เพิ่มสินค้า</button>
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
        function deleteItem(id, name) {
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
                    fetch('{{ route('admin.setting.item.delete') }}', {
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

    <!-- Quill CSS + JS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script>
        // สร้าง Quill editor
        var quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{
                        header: [1, 2, 3, false]
                    }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{
                        color: []
                    }, {
                        background: []
                    }],
                    [{
                        list: 'ordered'
                    }, {
                        list: 'bullet'
                    }],
                    ['link', 'image'],
                    ['clean']
                ]
            }
        });

        // โหลดข้อความเก่าจาก textarea ถ้ามี
        var textarea = document.querySelector('#article');
        if (textarea.value.trim() !== '') {
            quill.root.innerHTML = textarea.value;
        }

        // เวลา submit form
        document.querySelector('#itemForm').onsubmit = function() {
            textarea.value = quill.root.innerHTML;
        };
    </script>
@endpush
