<!-- Updated banner-edit.blade.php -->
<div>
    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Chỉnh sửa Banner: {{ $banner->title }}</h5>
            <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>

        <div class="card-body">
            <form wire:submit.prevent="updateBanner">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-3">Thông tin Banner</h6>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tiêu đề Banner *</label>
                            <input type="text" class="form-control" wire:model="title" placeholder="Nhập tiêu đề banner">
                            @error('title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Độ ưu tiên *</label>
                            <input type="number" class="form-control" wire:model="priority" min="0" max="100">
                            @error('priority')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">Số càng cao, độ ưu tiên càng lớn (0-100)</small>
                            <div class="mt-2">
                                <strong>Các độ ưu tiên hiện tại:</strong>
                                <div class="priority-display">
                                    @foreach ($available_priorities as $priority => $value)
                                        <span class="priority-item {{ $value === 'x' ? 'used' : ($value === 'current' ? 'current' : 'available') }}">
                                            {{ $value === 'current' ? $priority : $value }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Hình ảnh Banner</label>
                            <input type="file" class="form-control" wire:model="image">
                            @error('image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">Để trống nếu không muốn thay đổi. Định dạng: JPEG, PNG, JPG, GIF.</small>

                            @if ($current_image)
                                <div class="mt-2">
                                    <p>Hình ảnh hiện tại:</p>
                                    <img src="{{ asset($current_image) }}" alt="Current Banner" class="img-thumbnail" style="max-width:100% ; height: auto;">
                                </div>
                            @endif

                            @if ($image)
                                <div class="mt-2">
                                    <p>Hình ảnh mới (xem trước):</p>
                                    <img src="{{ $image->temporaryUrl() }}" alt="New Banner Preview" class="img-thumbnail" style="max-width:100% ; height: auto;">
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Link liên kết</label>
                            <input type="text" class="form-control" wire:model="link" placeholder="Nhập link hoặc để trống" disabled>
                            @error('link')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ngày bắt đầu *</label>
                            <input type="datetime-local" class="form-control" wire:model="start_date" disabled>
                            @error('start_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ngày kết thúc *</label>
                            <input type="datetime-local" class="form-control" wire:model="end_date">
                            @error('end_date')
                                <span class="text-danger">{{ $message }}</span>
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
                                <span class="text-danger">{{ $message }}</span>
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
                    <button type="submit" class="btn btn-primary me-2">Cập nhật Banner</button>
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
            border: 1px solid #dee2e6;
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
        .priority-item.current {
            background-color: #2196F3;
            color: white;
            font-weight: bold;
        }
    </style>
</div>
