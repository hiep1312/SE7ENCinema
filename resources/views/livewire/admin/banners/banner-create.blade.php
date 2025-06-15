<div>
    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Thêm Banner mới</h5>
            <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>

        <div class="card-body">
            <form wire:submit.prevent="createBanner">
                <div class="row">
                    <div class="col-md-6">
                            <h6 class="mb-3">Thông tin Banner</h6>
                                <div class="mb-3">
                                    <label>Tiêu đề Banner *</label>
                                    <input type="text" class="form-control" wire:model="title">
                                    @error('title')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Độ ưu tiên *</label>
                                    <input type="number" class="form-control" wire:model="priority">
                                    @error('priority')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Số càng cao, độ ưu tiên càng lớn (0-100)
                                    </small>
                                    <div class="mt-2">
                                        <strong>Các độ ưu tiên hiện tại:</strong>
                                        <div class="priority-display col-lg-8">
                                            @foreach ($available_priorities as $priority => $value)
                                                <span class="priority-item {{ $value === 'x' ? 'used' : 'available' }}">
                                                    {{ $value }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label>Hình ảnh Banner *</label>
                                    <input type="file" class="form-control" wire:model="image">
                                    @error('image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Định dạng: JPEG, PNG, JPG, GIF.
                                    </small>

                                    @if ($image)
                                        <div class="mt-2">
                                            <strong>Hình ảnh xem trước:</strong>
                                            <img src="{{ $image->temporaryUrl() }}" style="max-width:100% ; height: auto;">
                                        </div>
                                    @endif
                                </div>

                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Link liên kết</label>
                            <input type="url" class="form-control" wire:model="link" placeholder="https://example.com">
                            @error('link')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">Link sẽ được mở khi click vào banner (không bắt buộc)</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ngày bắt đầu *</label>
                            <input type="datetime-local" class="form-control" wire:model="start_date">
                            @error('start_date')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ngày kết thúc *</label>
                            <input type="datetime-local" class="form-control" wire:model="end_date">
                            @error('end_date')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Trạng thái *</label>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" wire:model="status" value="active" id="statusActive">
                                <label class="form-check-label" for="statusActive">Hoạt động</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" wire:model="status" value="inactive" id="statusInactive">
                                <label class="form-check-label" for="statusInactive">Không hoạt động</label>
                            </div>
                            @error('status')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <strong>Lưu ý:</strong>
                            <ul>
                                <li>Banner sẽ tự động chuyển sang trạng thái "Không hoạt động" khi hết hạn</li>
                                <li>Độ ưu tiên cao hơn sẽ được hiển thị trước</li>
                                <li>Hình ảnh nên có tỷ lệ phù hợp với vị trí hiển thị</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-2">Tạo Banner</button>
                    <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">Hủy bỏ</a>
                </div>
            </form>
        </div>
    </div>
    <style>
      .priority-display {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            gap: 5px;
            margin-top: 5px;
            padding: 5px;
            border: 1px solid #dee2e6; /* Maintain existing border */
            border-radius: 3px;
            max-width: 1000px;
        }
        .priority-item {
            flex: 0 0 auto;
            scroll-snap-stop: always;
            scroll-snap-align: center;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 12px;
        }
        .priority-item.used {
            background-color: #ff4444;
            color: white;
        }
        .priority-item.available {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</div>
