<div>
    @if (session('light'))
        <div class="alert alert-light alert-dismissible fade show" role="alert">
            {{ session('light') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container" data-bs-theme="dark">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Thêm phòng chiếu mới</h2>
            <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <!-- Form thông tin phòng chiếu -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-dark border-    ">
                    <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="mb-0">Thông tin phòng chiếu</h5>
                    </div>
                    <div class="card-body bg-dark">
                        <form wire:submit.prevent="createRoom">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label text-light">Tên phòng chiếu *</label>
                                        <input type="text"
                                               wire:model="name"
                                               class="form-control bg-dark text-light border-light @error('name') is-invalid @enderror"
                                               placeholder="VD: Phòng A1">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label text-light">Trạng thái *</label>
                                        <select wire:model="status" class="form-select bg-dark text-light border-light @error('status') is-invalid @enderror">
                                            <option value="active">Hoạt động</option>
                                            <option value="maintenance">Bảo trì</option>
                                            <option value="inactive">Ngừng hoạt động</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="border-light">
                            <h6 class="text-light">Cấu hình sơ đồ ghế</h6>

                            <div class="alert alert-info bg-dark border-info">
                                <i class="fas fa-info-circle text-info"></i>
                                <strong class="text-info">Giới hạn:</strong>
                                <ul class="mb-0 mt-2 text-light">
                                    <li>Số hàng ghế: 5 - 26 hàng</li>
                                    <li>Ghế mỗi hàng: 10 - 30 ghế</li>
                                    <li>Tổng sức chứa: 50 - 500 ghế</li>
                                </ul>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="rows" class="form-label text-light">Số hàng ghế *</label>
                                        <input type="number"
                                               wire:model.live="rows"
                                               class="form-control bg-dark text-light border-light @error('rows') is-invalid @enderror"
                                               min="5" max="26">
                                        @error('rows')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="seatsPerRow" class="form-label text-light">Ghế mỗi hàng *</label>
                                        <input type="number"
                                               wire:model.live="seatsPerRow"
                                               class="form-control bg-dark text-light border-light @error('seatsPerRow') is-invalid @enderror"
                                               min="10" max="30">
                                        @error('seatsPerRow')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="capacity" class="form-label text-light">Tổng sức chứa</label>
                                        <input type="number"
                                               wire:model="capacity"
                                               class="form-control bg-dark text-light border-light"
                                               readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="vipRows" class="form-label text-light">Hàng VIP</label>
                                        <input type="text"
                                               wire:model.live="vipRows"
                                               class="form-control bg-dark text-light border-light"
                                               placeholder="VD: A,B,C">
                                        <small class="text-muted">Nhập các hàng VIP, cách nhau bằng dấu phẩy</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="coupleRows" class="form-label text-light">Hàng Couple</label>
                                        <input type="text"
                                               wire:model.live="coupleRows"
                                               class="form-control bg-dark text-light border-light"
                                               placeholder="VD: J,K">
                                        <small class="text-muted">Nhập các hàng Couple, cách nhau bằng dấu phẩy</small>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success" @if($capacity > 500) disabled @endif>
                                    <i class="fas fa-save"></i> Tạo phòng chiếu
                                </button>
                                <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-danger">
                                    Hủy bỏ
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
