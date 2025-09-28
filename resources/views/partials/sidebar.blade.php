                <div class="col-md-3 mb-4 mb-md-0">
                    <div class="d-flex flex-column h-100 position-sticky top-0" style="min-height: 20vh;">
                        <div class="list-group shadow rounded-0 bg-dark border-end border-secondary h-100">
                            <div class="list-group-item bg-black text-white fw-bold text-center border-0 py-3">
                                <a href="{{ route('admin.dashboard') }}" class="bg-dark text-white text-decoration-none">
                                    <i class="bi bi-speedometer2 me-2 text-warning"></i>แดชบอร์ดผู้ดูแล
                            </div>
                            <a href="{{ route('admin.setting.web') }}"
                                class="list-group-item list-group-item-action bg-dark text-white border-secondary">
                                <i class="bi bi-gear me-2 text-info"></i>ตั้งค่าเว็บไซต์
                            </a>
                            <a href="{{ route('admin.setting.payment') }}"
                                class="list-group-item list-group-item-action bg-dark text-white border-secondary">
                                <i class="bi bi-wallet2 me-2 text-warning"></i>ตั้งค่าการชําระเงิน
                            </a>
                            <a href="{{ route('admin.setting.api') }}"
                                class="list-group-item list-group-item-action bg-dark text-white border-secondary">
                                <i class="bi bi-key me-2 text-warning"></i>ตั้งค่า API ๆ
                            </a>
                            <a href="{{ route('admin.setting.other') }}"
                                class="list-group-item list-group-item-action bg-dark text-white border-secondary">
                                <i class="bi bi-sliders me-2 text-warning"></i>ตั้งค่าอื่น ๆ
                            </a>
                            <a href="{{ route('admin.user') }}"
                                class="list-group-item list-group-item-action bg-dark text-white border-secondary">
                                <i class="bi bi-people-fill me-2 text-success"></i>จัดการสมาชิก
                            </a>
                            <a href="{{ route('admin.setting.category') }}"
                                class="list-group-item list-group-item-action bg-dark text-white border-secondary">
                                <i class="bi bi-box me-2 text-danger"></i>จัดการ สินค้า
                            </a>

                            <a href="{{ route('admin.report') }}"
                                class="list-group-item list-group-item-action bg-dark text-white border-secondary">
                                <i class="bi bi-graph-up-arrow me-2 text-success"></i>รายงาน
                            </a>
                        </div>
                    </div>
                </div>
