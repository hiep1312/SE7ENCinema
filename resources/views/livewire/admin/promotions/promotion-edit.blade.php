<div class="scRender">
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Chỉnh sửa mã giảm giá: {{ $promotion->code }}</h2>
            <a href="{{ route('admin.promotions.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <!-- Form thông tin mã giảm giá -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-dark">
                    <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="my-1">Thông tin mã giảm giá</h5>
                    </div>
                    <div class="card-body bg-dark">
                        <form wire:submit.prevent="updatePromotion" novalidate>
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
                                            <input type="text" id="code" :value="$wire.code"
                                                class="form-control bg-dark text-light border-light"
                                                placeholder="VD: SALE50, NEWUSER, GIAM10K" readonly>
                                            <button type="button" wire:click="$set('code', '{{ strtoupper(Str::random(8)) }}')" class="btn btn-outline-warning" disabled>Tạo mã</button>
                                        </div>
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
                                            class="form-control bg-dark text-light border-light @error('start_date') is-invalid @enderror"
                                            {{ $promotion->start_date->isPast() ? 'readonly' : '' }}>
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
                                        <label for="usage_limit" class="form-label text-light">Giới hạn sử dụng @if($usage_limit) <span class="text-muted">(còn {{ $usage_limit - $promotion->usages->count() }} lượt)</span> @endif</label>
                                        <input type="number" id = "usage_limit" wire:model.live.debounce.300ms="usage_limit"
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
                                        <select id="status" wire:model="status" class="form-select bg-dark text-light border-light @error('status') is-invalid @enderror" :disabled="Date.parse($wire.end_date) < Date.now()">
                                            <template x-if="Date.parse($wire.end_date) < Date.now()">
                                                <option value="expired" selected>Đã hết hạn</option>
                                            </template>
                                            <option value="active">Hoạt động</option>
                                            <option value="inactive">Ngừng hoạt động</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Cập nhật thông tin
                                </button>
                                <a href="{{ route('admin.promotions.index') }}" class="btn btn-outline-danger">
                                    Hủy bỏ
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-3" wire:poll>
                <div class="card bg-dark border-light">
                    <div class="card-header bg-gradient text-light"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5><i class="fa-solid fa-list me-2"></i>Danh sách người dùng đã sử dụng</h5>
                    </div>
                    <div class="card-body bg-dark"
                        style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                        <div class="table-responsive">
                            <table class="table table-dark table-striped table-hover text-light border">
                                <thead>
                                    <tr>
                                        <th class="text-center text-light">Ảnh avatar</th>
                                        <th class="text-center text-light">Tên người dùng</th>
                                        <th class="text-center text-light">Email / SĐT</th>
                                        <th class="text-center text-light">Địa chỉ</th>
                                        <th class="text-center text-light">Mã đơn hàng</th>
                                        <th class="text-center text-light">Tổng tiền</th>
                                        <th class="text-center text-light">Ngày sử dụng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($usages as $usage)
                                        <tr wire:key="{{ $usage->id }}">
                                            @php $user = $usage->booking->user @endphp
                                            <td>
                                                <div class="user-avatar-clean" style="width: 55px; aspect-ratio: 1; height: auto; margin: 0 auto; border-radius: 50%;">
                                                    @if($user->avatar)
                                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt style="width: 100%; height: 100%; object-fit: cover; border-radius: inherit;">
                                                    @else
                                                        <i class="fas fa-user icon-white" style="font-size: 20px;"></i>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <strong class="text-light text-wrap d-block mb-2">{{ $user->name }}</strong>
                                                @switch($user->status)
                                                    @case('active')
                                                        <span class="badge bg-success"><i class="fas fa-play me-1"></i>Đang hoạt động</span>
                                                        @break
                                                    @case('inactive')
                                                        <span class="badge bg-warning text-dark"><i class="fa-solid fa-user-slash me-1"></i>Không hoạt động</span>
                                                        @break
                                                    @case('banned')
                                                        <span class="badge bg-danger"><i class="fa-solid fa-ban me-1"></i>Bị cấm</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td class="text-center">
                                                <span class="text-light">{{ $user->email }}
                                                    @if ($user->phone)
                                                        / {{ $user->phone }}
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="text-center" style="max-width: 240px;">
                                                <p class="text-wrap text-muted lh-base" style="margin-bottom: 0;">{{ Str::limit($user->address . Str::repeat('Address ', 30) ?? 'N/A', 50, '...') }}</p>
                                            </td>
                                            <td class="text-center">
                                                <strong class="text-light">{{ $usage->booking->booking_code }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-gradient fs-6">
                                                    {{ number_format($usage->booking->total_price, 0, '.', '.') }}đ
                                                </span>
                                                <small class="text-danger fw-bold d-block mt-1 ms-1">- {{ number_format($usage->discount_amount, 0, '.', '.') }}đ KM</small>
                                            </td>
                                            <td class="text-center">{{ $usage->used_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                                    <p>Chưa có người dùng nào sử dụng</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($usages->hasPages())
                            <div class="mt-3 mt-xl-1">
                                {{ $usages->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
