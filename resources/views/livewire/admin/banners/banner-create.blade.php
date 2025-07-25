<div class="scRender">
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Thêm banner mới</h2>
            <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <!-- Form thông tin banner -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-dark">
                    <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="my-1">Thông tin banner</h5>
                    </div>
                    <div class="card-body bg-dark">
                        <form wire:submit.prevent="createBanner" enctype="multipart/form-data" novalidate>
                            <div class="row">
                                @if ($image)
                                    <div class="col-md-3 col-5 mb-3">
                                        <div class="mt-1 banner-image w-100">
                                            <img src="{{ $image->temporaryUrl() }}" alt="Ảnh banner tải lên"
                                                style="width: 100%; height: 100%; object-fit: cover; border-radius: 0;">
                                        </div>
                                    </div>
                                    <div class="col-md-9 row">
                                @endif
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="title" class="form-label text-light">Tiêu đề banner *</label>
                                        <input type="text"
                                            id = "title"
                                            wire:model="title"
                                            class="form-control bg-dark text-light border-light @error('title') is-invalid @enderror"
                                            placeholder="VD: Khuyến mãi hấp dẫn, Giảm giá 50%">
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="image" class="form-label text-light">Ảnh banner *</label>
                                        <input type="file"
                                            id = "image"
                                            wire:model.live="image"
                                            class="form-control bg-dark text-light border-light @error('image') is-invalid @enderror"
                                            accept="image/*">
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="link" class="form-label text-light">Đường dẫn liên kết </label>
                                        <input type="url"
                                            id = "link"
                                            wire:model="link"
                                            class="form-control bg-dark text-light border-light @error('link') is-invalid @enderror"
                                            placeholder="VD: https://www.example.com">
                                        @error('link')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label text-light">Trạng thái *</label>
                                        <select id="status" wire:model="status" class="form-select bg-dark text-light border-light @error('status') is-invalid @enderror">
                                            <option value="active">Hoạt động</option>
                                            <option value="inactive">Ngừng hoạt động</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label text-light">Ngày giờ bắt đầu *</label>
                                        <input type="datetime-local"
                                            id = "start_date"
                                            wire:model="start_date"
                                            class="form-control bg-dark text-light border-light @error('start_date') is-invalid @enderror"
                                            placeholder="VD: 2025-06-15 08:00">
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label text-light">Ngày giờ kết thúc </label>
                                        <input type="datetime-local"
                                            id = "end_date"
                                            wire:model="end_date"
                                            class="form-control bg-dark text-light border-light @error('end_date') is-invalid @enderror"
                                            placeholder="VD: 2025-06-20 20:00">
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="priority" class="form-label text-light">Độ ưu tiên *</label>
                                        <input type="number"
                                            id = "priority"
                                            wire:model="priority"
                                            class="form-control bg-dark text-light border-light @error('priority') is-invalid @enderror"
                                            placeholder="VD: 1, 2, 3" min="0">
                                        @error('priority')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="mt-2">
                                            <strong>Các độ ưu tiên hiện tại: </strong>
                                            <div class="priority-display">
                                                @foreach ($priorityCurrent as $value)
                                                    <span class="priority-item {{ $value === "x" ? "used" : "available" }}">
                                                        {{ $value }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($image) </div> @endif
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Tạo banner
                                </button>
                                <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-danger">
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
