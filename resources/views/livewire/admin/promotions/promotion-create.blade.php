<div class="scRender container-lg mb-4">
    <div class="d-flex justify-content-between align-items-center my-3">
        <h2 class="text-light">Thêm khuyến mãi mới</h2>
        <a href="{{ route('admin.promotions.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card bg-dark">
        <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h5 class="my-1">Thông tin khuyến mãi</h5>
        </div>
        <div class="card-body bg-dark">
            <form wire:submit.prevent="save">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-light">Tiêu đề *</label>
                        <input type="text" wire:model="title" class="form-control bg-dark text-light border-light @error('title') is-invalid @enderror" placeholder="Nhập tiêu đề">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-light">Mã khuyến mãi *</label>
                        <div class="input-group">
                            <input type="text" wire:model="code" class="form-control bg-dark text-light border-light @error('code') is-invalid @enderror" placeholder="Nhập mã khuyến mãi">
                            <button type="button" wire:click="generateCode" class="btn btn-outline-primary">Tạo mã</button>
                        </div>
                        @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-light">Mô tả</label>
                        <input type="text" wire:model="description" class="form-control bg-dark text-light border-light @error('description') is-invalid @enderror" placeholder="Nhập mô tả">
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-light">Loại giảm giá *</label>
                        <select wire:model.live="discount_type" class="form-select bg-dark text-light border-light @error('discount_type') is-invalid @enderror">
                            <option value="percentage">Phần trăm</option>
                            <option value="fixed_amount">Cố định</option>
                        </select>
                        @error('discount_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-light">Số tiền hoặc (%) khuyến mãi *</label>
                        <input type="number"
                            wire:model.live="discount_value"
                            step="any"
                            class="form-control bg-dark text-light border-light @error('discount_value') is-invalid @enderror"
                            id="discount_value"
                            placeholder="{{ $discount_type === 'percentage' ? 'Nhập % (1-100)' : 'Nhập số tiền' }}"
                            min="{{ $discount_type === 'percentage' ? '1' : '0' }}"
                            @if($discount_type === 'percentage') max="100" @endif
                            step="{{ $discount_type === 'percentage' ? '0.01' : '1000' }}">
                        @error('discount_value') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-light">Giá trị tối thiểu đơn hàng</label>
                        <input type="number" wire:model="min_purchase" class="form-control bg-dark text-light border-light @error('min_purchase') is-invalid @enderror" placeholder="Nhập giá trị tối thiểu">
                        @error('min_purchase') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-light">Thời gian bắt đầu *</label>
                        <input type="datetime-local" wire:model="start_date" class="form-control bg-dark text-light border-light @error('start_date') is-invalid @enderror">
                        @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-light">Thời gian kết thúc *</label>
                        <input type="datetime-local" wire:model="end_date" class="form-control bg-dark text-light border-light @error('end_date') is-invalid @enderror">
                        @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-light">Giới hạn sử dụng</label>
                        <input type="number" wire:model="usage_limit" class="form-control bg-dark text-light border-light @error('usage_limit') is-invalid @enderror" placeholder="Nhập giới hạn sử dụng">
                        @error('usage_limit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-light">Trạng thái</label>
                        <select wire:model="status" class="form-select bg-dark text-light border-light @error('status') is-invalid @enderror">
                            <option value="active">Hoạt động</option>
                            <option value="inactive">Ngừng hoạt động</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Tạo khuyến mãi
                    </button>
                    <a href="{{ route('admin.promotions.index') }}" class="btn btn-outline-danger">
                        Hủy bỏ
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
