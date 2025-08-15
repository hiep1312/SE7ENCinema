<div class="scRender">
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-2 mx-2" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Thêm mã giảm giá mới</h2>
            <a href="{{ route('admin.promotions.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card bg-dark">
                    <div class="card-header bg-gradient text-light"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="my-1">Thông tin mã giảm giá</h5>
                    </div>
                    <div class="card-body bg-dark">
                        <form wire:submit.prevent="createPromotion" novalidate>
                            <div class="row align-items-start mb-1">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="title" class="form-label text-light">Tiêu đề mã giảm giá *</label>
                                        <input type="text" id="title" wire:model="title"
                                            class="form-control bg-dark text-light border-light @error('title') is-invalid @enderror"
                                            placeholder="VD: Giảm 50% cho đơn hàng đầu tiên">
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="code" class="form-label text-light">Mã giảm giá *</label>
                                        <div class="input-group">
                                            <input type="text" id="code" wire:model="code"
                                                class="form-control bg-dark text-light border-light @error('code') is-invalid @enderror"
                                                placeholder="VD: SALE50, NEWUSER, GIAM10K">
                                            <button type="button" wire:click="$set('code', '{{ strtoupper(Str::random(8)) }}')" class="btn btn-outline-warning">Tạo mã</button>
                                        </div>
                                        @error('code')
                                            <div class="invalid-feedback" style="display: block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="description" class="form-label text-light">Mô tả </label>
                                        <textarea id="description" wire:model="description" class="form-control bg-dark text-light border-light @error('description') is-invalid @enderror" placeholder="VD: Áp dụng cho khách hàng mới, không giới hạn ngành hàng"></textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="discount_type" class="form-label text-light">Loại giảm giá *</label>
                                        <select id="discount_type" wire:model.change="discount_type" class="form-select bg-dark text-light border-light @error('discount_type') is-invalid @enderror">
                                            <option value="fixed_amount">Cố định</option>
                                            <option value="percentage">Phần trăm</option>
                                        </select>
                                        @error('discount_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="discount_value" class="form-label text-light">Số tiền hoặc (%) giảm giá *</label>
                                        <div class="input-group">
                                            <input type="number" id="discount_value" wire:model="discount_value"
                                                class="form-control bg-dark text-light border-light @error('discount_value') is-invalid @enderror"
                                                :placeholder="`VD: ${$wire.discount_type === 'percentage' ? '% (1-100)' : '50000'}`" min="0">
                                            <span class="input-group-text" x-text="$wire.discount_type === 'percentage' ? '%' : 'đ'"></span>
                                        </div>
                                        @error('discount_value')
                                            <div class="invalid-feedback" style="display: block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label text-light">Thời gian bắt đầu *</label>
                                        <input type="datetime-local" id="start_date" wire:model="start_date"
                                            class="form-control bg-dark text-light border-light @error('start_date') is-invalid @enderror">
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label text-light">Thời gian kết thúc *</label>
                                        <input type="datetime-local" id="end_date" wire:model="end_date"
                                            class="form-control bg-dark text-light border-light @error('end_date') is-invalid @enderror">
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="usage_limit" class="form-label text-light">Giới hạn sử dụng </label>
                                        <input type="number" id = "usage_limit" wire:model="usage_limit"
                                            class="form-control bg-dark text-light border-light @error('usage_limit') is-invalid @enderror"
                                            placeholder="VD: 100" min="1">
                                        @error('usage_limit')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="min_purchase" class="form-label text-light">Giá trị đơn hàng tối thiểu </label>
                                        <input type="number" id="min_purchase" wire:model="min_purchase"
                                            class="form-control bg-dark text-light border-light @error('min_purchase') is-invalid @enderror"
                                            placeholder="VD: 200000" min="0">
                                        @error('min_purchase')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="status" class="form-label text-light">Trạng thái *</label>
                                        <select id="status" :value="$wire.status"
                                            class="form-select bg-dark text-light border-light"
                                            disabled>
                                            <option value="active">Hoạt động</option>
                                            <option value="inactive">Ngừng hoạt động</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Tạo mã giảm giá
                                </button>
                                <a href="{{ route('admin.promotions.index') }}" class="btn btn-outline-danger">
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
