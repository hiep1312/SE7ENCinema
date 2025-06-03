@push('styles')
    @vite('resources/css/components/user-profile.css')
@endpush
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient text-white py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-plus me-3 fs-4"></i>
                        <h5 class="mb-0 fw-bold">Chỉnh sửa người dùng</h5>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form wire:submit.prevent="save" wire:key="{{ $userId }}">
                        <!-- Avatar Upload -->
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                @if ($avatar)
                                    <img src="{{ $avatar->temporaryUrl() }}"
                                        class="rounded-circle border border-3 border-primary shadow" width="120"
                                        height="120" style="object-fit: cover;">
                                @else
                                    @if($avatar_user)
                                        <img src="{{ Storage::url($avatar_user) }}" alt="Customer Avatar" class="avatar">
                                    @else
                                        <div class="bg-light rounded-circle border border-3 border-primary d-flex align-items-center justify-content-center shadow"
                                            style="width: 120px; height: 120px;">
                                            <i class="fas fa-user text-muted fs-1"></i>
                                        </div>
                                    @endif
                                @endif
                                <label for="avatar"
                                    class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 cursor-pointer shadow">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input type="file" id="avatar" wire:model="avatar" class="d-none" accept="image/*">
                            </div>
                            @error('avatar')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row g-3">
                            <!-- Name -->
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">
                                    <i class="fas fa-user text-primary me-2"></i>Họ và Tên
                                </label>
                                <input type="text" wire:model="name"
                                    class="form-control @error('name') is-invalid @enderror">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="fas fa-envelope text-primary me-2"></i>Email
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    wire:model="email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">
                                    <i class="fas fa-phone text-primary me-2"></i>Số Điện Thoại
                                </label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                    wire:model="phone">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Birthday -->
                            <div class="col-md-6">
                                <label for="birthday" class="form-label fw-semibold">
                                    <i class="fas fa-birthday-cake text-primary me-2"></i>Ngày Sinh
                                </label>
                                <input type="date" class="form-control @error('birthday') is-invalid @enderror"
                                    wire:model="birthday">
                                @error('birthday')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Address -->
                            <div class="col-12">
                                <label for="address" class="form-label fw-semibold">
                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>Địa Chỉ
                                </label>
                                <textarea class="form-control @error('address') is-invalid @enderror"
                                    wire:model="address" rows="3" placeholder="Nhập địa chỉ đầy đủ"></textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Gender -->
                            <div class="col-md-4">
                                <label for="gender" class="form-label fw-semibold">
                                    <i class="fas fa-venus-mars text-primary me-2"></i>Giới Tính
                                </label>
                                <select class="form-select @error('gender') is-invalid @enderror" wire:model="gender">
                                    <option value="">Chọn giới tính</option>
                                    <option value="man">Nam</option>
                                    <option value="woman">Nữ</option>
                                    <option value="other">Khác</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Role -->
                            <div class="col-md-4">
                                <label for="role" class="form-label fw-semibold">
                                    <i class="fas fa-user-tag text-primary me-2"></i>Vai Trò
                                </label>
                                <select class="form-select @error('role') is-invalid @enderror" wire:model="role">
                                    <option value="user">Người dùng</option>
                                    <option value="staff">Nhân viên</option>
                                    <option value="admin">Quản trị viên</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Status -->
                            <div class="col-md-4">
                                <label for="status" class="form-label fw-semibold">
                                    <i class="fas fa-toggle-on text-primary me-2"></i>Trạng Thái
                                </label>
                                <select class="form-select @error('status') is-invalid @enderror" wire:model="status">
                                    <option value="active">Hoạt động</option>
                                    <option value="inactive">Không hoạt động</option>
                                    <option value="banned">Chặn</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-3 mt-4 pt-3 border-top">
                            <a href="{{ route('admin.users.index') }}" type="button"
                                class="btn btn-outline-secondary px-4">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary px-4" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="fas fa-save me-2"></i>Lưu Người Dùng
                                </span>
                                <span wire:loading>
                                    <i class="fas fa-spinner fa-spin me-2"></i>Đang lưu...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>