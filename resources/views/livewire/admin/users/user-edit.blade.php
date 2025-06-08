<div>
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Cập nhật thông tin người dùng: {{ $user->name }}</h2>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <!-- Form thông tin người dùng -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-dark">
                    <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="my-1">Thông tin người dùng</h5>
                    </div>
                    <div class="card-body bg-dark">
                        <form wire:submit.prevent="updateUser" enctype="multipart/form-data" novalidate>
                            <div class="row align-items-start mb-2">
                                <div class="col-xl-3 mb-3">
                                        <div class="mt-1 overflow-auto position-relative text-center" style="max-height: 230px;">
                                            <img src="{{ asset('storage/' . ($user->avatar ?? '404.webp')) }}" alt="Ảnh biến thể hiện tại"
                                                style="width: 230px; height: 230px; border-radius: 50%; object-fit: cover; border: 3px solid white; box-shadow: 0 3px 10px rgba(0,0,0,0.1);">
                                            <span class="position-absolute opacity-50 badge rounded bg-danger" style="top: 49%; left: 50%; transform: translate(-50%, -50%);">
                                                Ảnh hiện tại
                                            </span>
                                        </div>
                                    @if ($avatar && $avatar instanceof Illuminate\Http\UploadedFile)
                                        <div class="mt-2 overflow-auto position-relative text-center" style="max-height: 230px;">
                                            <img src="{{ $avatar->temporaryUrl() }}" alt="Ảnh người dùng tải lên"
                                                style="width: 230px; height: 230px; border-radius: 50%; object-fit: cover; border: 3px solid white; box-shadow: 0 3px 10px rgba(0,0,0,0.1);">
                                            <span class="position-absolute opacity-50 badge rounded bg-success" style="top: 49%; left: 50%; transform: translate(-50%, -50%);">
                                                Ảnh mới
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-xl-9 row align-items-start">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label text-light">Tên người dùng </label>
                                            <input type="text"
                                                id = "name"
                                                value="{{ $user->name }}"
                                                class="form-control bg-dark text-light border-light"
                                                placeholder="VD: Nguyễn Văn A" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label text-light">Email </label>
                                            <input type="email"
                                                id = "email"
                                                value="{{ $user->email }}"
                                                class="form-control bg-dark text-light border-light"
                                                placeholder="VD: nguyenvana@gmail.com" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label text-light">Số điện thoại</label>
                                            <input type="text"
                                                id = "phone"
                                                wire:model="phone"
                                                class="form-control bg-dark text-light border-light @error('phone') is-invalid @enderror"
                                                placeholder="VD: 0123456789" @if($user->phone) readonly @endif>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="avatar" class="form-label text-light">Ảnh đại diện</label>
                                            <input type="file"
                                                id = "avatar"
                                                wire:model.live="avatar"
                                                class="form-control bg-dark text-light border-light @error('avatar') is-invalid @enderror"
                                                accept="image/*">
                                            @error('avatar')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="address" class="form-label text-light">Địa chỉ</label>
                                            <textarea id="address" wire:model="address" class="form-control bg-dark text-light border-light @error('address') is-invalid @enderror" placeholder="VD: " @if($user->address) readonly @endif></textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="birthday" class="form-label text-light">Ngày sinh</label>
                                            <input type="date"
                                                id = "birthday"
                                                wire:model="birthday"
                                                class="form-control bg-dark text-light border-light @error('birthday') is-invalid @enderror"
                                                placeholder="VD: 2014-03-22" @if($user->birthday) readonly @endif>
                                            @error('birthday')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="gender" class="form-label text-light">Giới tính </label>
                                            <select id="gender" wire:model="gender" class="form-select bg-dark text-light border-light @error('gender') is-invalid @enderror" @if($user->gender) disabled @endif>
                                                <option value="man">Nam</option>
                                                <option value="woman">Nữ</option>
                                                <option value="other">Khác</option>
                                            </select>
                                            @error('gender')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="role" class="form-label text-light">Vai trò</label>
                                            <select id="role" wire:model="role" class="form-select bg-dark text-light border-light @error('role') is-invalid @enderror">
                                                <option value="user">Người dùng</option>
                                                <option value="staff">Nhân viên</option>
                                                <option value="admin">Quản trị viên</option>
                                            </select>
                                            @error('role')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status" class="form-label text-light">Trạng thái</label>
                                            <select id="status" wire:model="status" class="form-select bg-dark text-light border-light @error('status') is-invalid @enderror">
                                                <option value="active">Đang hoạt động</option>
                                                <option value="inactive">Ngừng hoạt động</option>
                                                <option value="banned">Bị cấm</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Cập nhật thông tin
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-danger">
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
