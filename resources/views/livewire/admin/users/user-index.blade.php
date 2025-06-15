<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" wire:ignore>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" wire:ignore>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Quản lý người dùng</h2>
            <div>
                @if (!$showDeleted)
                    <a href="{{ route('admin.users.create') }}" class="btn btn-success me-2">
                        <i class="fas fa-plus me-1"></i>Thêm người dùng
                    </a>
                @endif
                <button wire:click="$toggle('showDeleted')" class="btn btn-outline-danger">
                    @if ($showDeleted)
                        <i class="fas fa-eye me-1"></i>Xem người dùng hoạt động
                    @else
                        <i class="fas fa-trash me-1"></i>Xem người dùng đã bị xóa
                    @endif
                </button>
            </div>
        </div>

        <div class="card bg-dark" wire:poll.3s="realtimeCheckOperation">
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="row g-3">
                    <!-- Tìm kiếm -->
                    <div class="col-md-4 col-lg-3">
                        <div class="input-group">
                            <input type="text" wire:model.live.debounce.300ms="search"
                                class="form-control bg-dark text-light" placeholder="Tìm kiếm người dùng...">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Lọc theo trạng thái -->
                    @if (!$showDeleted)
                        <div class="col-md-3 col-lg-2">
                            <select wire:model.live="roleFilter" class="form-select bg-dark text-light">
                                <option value="">Tất cả vai trò</option>
                                <option value="user">Người dùng</option>
                                <option value="staff">Nhân viên</option>
                                <option value="admin">Quản trị viên</option>
                            </select>
                        </div>

                        <!-- Lọc theo suất chiếu -->
                        <div class="col-md-3 col-lg-2">
                            <select wire:model.live="statusFilter" class="form-select bg-dark text-light">
                                <option value="">Tất cả trạng thái</option>
                                <option value="active">Đang hoạt động</option>
                                <option value="inactive">Ngừng hoạt động</option>
                                <option value="banned">Bị cấm</option>
                            </select>
                        </div>

                        <!-- Reset filters -->
                        <div class="col-md-2">
                            <button wire:click="resetFilters" class="btn btn-outline-warning">
                                <i class="fas fa-refresh me-1"></i>Reset
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card-body bg-dark">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <tr>
                                <th class="text-center text-light">STT</th>
                                <th class="text-center text-light">Ảnh đại diện</th>
                                <th class="text-center text-light">Tên người dùng</th>
                                <th class="text-center text-light">Email / SĐT</th>
                                <th class="text-center text-light">Địa chỉ</th>
                                <th class="text-center text-light">Vai trò</th>
                                <th class="text-center text-light">Trạng thái</th>
                                @if ($showDeleted)
                                    <th class="text-center text-light">Ngày xóa</th>
                                @else
                                    <th class="text-center text-light">Ngày tạo</th>
                                @endif
                                <th class="text-center text-light">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr wire:key="{{ $user->id }}">
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="mt-1 overflow-auto d-block text-center"
                                            style="max-height: 75px; width: 75px;">
                                            <img src="{{ asset('storage/' . ($user->avatar ?? '404.webp')) }}"
                                                alt="Ảnh đại diện của {{ $user->name }}" style="width: 75px; height: 75px; border-radius: 50%; object-fit: cover; border: 3px solid white; box-shadow: 0 3px 10px rgba(0,0,0,0.1);">
                                        </div>
                                    </td>
                                    <td>
                                        <strong class="text-light">{{ $user->name }}</strong>
                                        @if ($user->trashed())
                                            <span class="badge bg-danger ms-1">Đã xóa</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-gradient text-light" style="background: linear-gradient(to right, #642b73, #c6426e) !important;">
                                            {{ $user->email }} @if($user->phone) / {{ $user->phone }} @endif
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if ($user->address)
                                            <span
                                                class="text-light text-wrap lh-base">{{ Str::limit($user->address, 50, '...') }}</span>
                                        @else
                                            <span class="text-muted">Không có mô tả</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @switch($user->role)
                                            @case('user')
                                                <span class="badge bg-info text-dark">Người dùng</span>
                                            @break
                                            @case('staff')
                                                <span class="badge bg-primary">Nhân viên</span>
                                            @break
                                            @case('admin')
                                                <span class="badge bg-success">Quản trị viên</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td class="text-center">
                                        @if (!$showDeleted && !$user->trashed())
                                            @switch($user->status)
                                                @case('active')
                                                    <span class="badge bg-success">Đang hoạt động</span>
                                                @break
                                                @case('inactive')
                                                    <span class="badge bg-warning text-dark">Ngừng hoạt động</span>
                                                @break
                                                @case('banned')
                                                    <span class="badge bg-danger">Bị cấm</span>
                                                    @break
                                            @endswitch
                                        @else
                                            <span class="badge bg-secondary">Đã xóa</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($showDeleted)
                                            <span class="text-light">
                                                {{ $user->deleted_at ? $user->deleted_at->format('d/m/Y H:i') : 'N/A' }}
                                            </span>
                                        @else
                                            <span class="text-light">
                                                {{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($showDeleted)
                                            <div class="d-flex gap-3 justify-content-center">
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    wire:sc-model="forceDeleteUser({{ $user->id }})"
                                                    wire:sc-confirm.warning="Bạn có chắc chắn muốn XÓA VĨNH VIỄN người dùng '{{ $user->name }}'? Hành động này KHÔNG THỂ HOÀN TÁC!"
                                                    title="Xóa vĩnh viễn">
                                                    <i class="fas fa-trash-alt" style="margin-right: 0"></i>
                                                </button>
                                            </div>
                                        @else
                                            <div class="d-flex gap-2 justify-content-center">
                                                <a href="{{ route('admin.users.detail', $user->id) }}"
                                                    class="btn btn-sm btn-info" title="Xem chi tiết">
                                                    <i class="fas fa-eye" style="margin-right: 0"></i>
                                                </a>
                                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                                    class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                                    <i class="fas fa-edit" style="margin-right: 0"></i>
                                                </a>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                <p>
                                                    @if ($showDeleted)
                                                        Không có người dùng nào đã bị xóa
                                                    @else
                                                        Không có người dùng nào
                                                    @endif
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
