<div>
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Thêm người dùng mới</h2>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <!-- Form thông tin người dùng -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-dark">
                    <div class="card-header bg-gradient text-light"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="my-1">Thông tin người dùng</h5>
                    </div>
                    <div class="card-body bg-dark">
                        <form wire:submit.prevent="createUser" enctype="multipart/form-data" novalidate>
                            <div class="row align-items-start mb-2">
                                @if ($avatar)
                                    <div class="col-xl-3 col-5 mb-3">
                                        <div class="mt-1 overflow-auto text-center" style="max-height: 230px;">
                                            <img src="{{ $avatar->temporaryUrl() }}" alt="Ảnh người dùng tải lên"
                                                style="width: 230px; height: 230px; border-radius: 50%; object-fit: cover; border: 3px solid white; box-shadow: 0 3px 10px rgba(0,0,0,0.1);">
                                        </div>
                                    </div>
                                    <div class="col-xl-9 row">
                                @endif
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label text-light">Tên người dùng *</label>
                                            <input type="text" id="name" wire:model="name"
                                                class="form-control bg-dark text-light border-light @error('name') is-invalid @enderror"
                                                placeholder="VD: Nguyễn Văn A">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label text-light">Email *</label>
                                            <input type="email" id="email" wire:model="email"
                                                class="form-control bg-dark text-light border-light @error('email') is-invalid @enderror"
                                                placeholder="VD: nguyenvana@gmail.com">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password" class="form-label text-light">Mật khẩu *</label>
                                            <input type="password" id="password" wire:model="password"
                                                class="form-control bg-dark text-light border-light @error('password') is-invalid @enderror"
                                                placeholder="VD: ********">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="confirm_password" class="form-label text-light">Xác nhận mật
                                                khẩu *</label>
                                            <input type="password" id="confirm_password" wire:model="confirm_password"
                                                class="form-control bg-dark text-light border-light @error('confirm_password') is-invalid @enderror"
                                                placeholder="VD: ********">
                                            @error('confirm_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label text-light">Số điện thoại</label>
                                            <input type="text" id="phone" wire:model="phone"
                                                class="form-control bg-dark text-light border-light @error('phone') is-invalid @enderror"
                                                placeholder="VD: 0123456789">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="avatar" class="form-label text-light">Ảnh đại diện</label>
                                            <input type="file" id="avatar" wire:model.live="avatar"
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
                                            <textarea id="address" wire:model="address"
                                                class="form-control bg-dark text-light border-light @error('address') is-invalid @enderror"
                                                placeholder="VD: "></textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="birthday" class="form-label text-light">Ngày sinh</label>
                                            <input type="date" id="birthday" wire:model="birthday"
                                                class="form-control bg-dark text-light border-light @error('birthday') is-invalid @enderror"
                                                placeholder="VD: 0123456789">
                                            @error('birthday')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="gender" class="form-label text-light">Giới tính *</label>
                                            <select id="gender" wire:model="gender"
                                                class="form-select bg-dark text-light border-light @error('gender') is-invalid @enderror">
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
                                            <label for="role" class="form-label text-light">Vai trò *</label>
                                            <select id="role" wire:model="role"
                                                class="form-select bg-dark text-light border-light @error('role') is-invalid @enderror">
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
                                            <label for="status" class="form-label text-light">Trạng thái *</label>
                                            <select id="status" wire:model="status"
                                                class="form-select bg-dark text-light border-light @error('status') is-invalid @enderror">
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
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label text-light">Email *</label>
                                        <input type="email"
                                            id = "email"
                                            wire:model="email"
                                            class="form-control bg-dark text-light border-light @error('email') is-invalid @enderror"
                                            placeholder="VD: nguyenvana@gmail.com">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label text-light">Mật khẩu *</label>
                                        <input type="password"
                                            id = "password"
                                            wire:model="password"
                                            class="form-control bg-dark text-light border-light @error('password') is-invalid @enderror"
                                            placeholder="VD: ********">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label text-light">Xác nhận mật khẩu *</label>
                                        <input type="password"
                                            id = "confirm_password"
                                            wire:model = "confirm_password"
                                            class="form-control bg-dark text-light border-light @error('confirm_password') is-invalid @enderror"
                                            placeholder="VD: ********">
                                        @error('confirm_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label text-light">Số điện thoại</label>
                                        <input type="text"
                                            id = "phone"
                                            wire:model="phone"
                                            class="form-control bg-dark text-light border-light @error('phone') is-invalid @enderror"
                                            placeholder="VD: 0123456789">
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
                                        <textarea id="address" wire:model="address" class="form-control bg-dark text-light border-light @error('address') is-invalid @enderror" placeholder="VD: "></textarea>
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
                                            placeholder="VD: 2014-03-22">
                                        @error('birthday')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="gender" class="form-label text-light">Giới tính *</label>
                                        <select id="gender" wire:model="gender" class="form-select bg-dark text-light border-light @error('gender') is-invalid @enderror">
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
                                        <label for="role" class="form-label text-light">Vai trò *</label>
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
                                        <label for="status" class="form-label text-light">Trạng thái *</label>
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
                            @if($avatar) </div> @endif
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Tạo người dùng
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
