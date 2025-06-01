<div>
{{-- Thông báo thành công --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
{{-- Thông báo lỗi --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-times me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container" data-bs-theme="dark">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Quản lý tài khoản</h2>
        </div>
        <div class="card bg-dark">
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="row g-3">
                    <!-- Tìm kiếm -->
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text"
                                   wire:model.live.debounce.300ms="search"
                                   class="form-control bg-dark text-light"
                                   placeholder="Tìm kiếm tài khoản...">
                        </div>
                    </div>
                    <!-- Lọc theo trạng thái -->
                    <div class="col-md-2">
                            <select wire:model.live="status" class="form-select bg-dark text-light">
                                <option value="">Tất cả trạng thái</option>
                                <option value="active">Hoạt động</option>
                                <option value="inactive">Ngừng hoạt động</option>
                                <option value="banned">Đã bị cấm</option>
                            </select>
                    </div>
                    <!-- Lọc theo suất chiếu -->
                    <div class="col-md-2">
                            <select wire:model.live="role" class="form-select bg-dark text-light">
                                <option value="">Tất cả vai trò</option>
                                <option value="staff">Nhân viên</option>
                                <option value="admin">Quản trị viên</option>
                                <option value="user">Người dùng</option>
                            </select>
                    </div>
                        <!-- Reset filters -->
                    <div class="col-md-2">
                            <button wire:click="resetFilters" class="btn btn-outline-warning">
                                <i class="fas fa-refresh me-1"></i>Đặt lại
                            </button>
                    </div>
                </div>
            </div>
            <div class="card-body bg-dark">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <tr>
                                <th class="text-center text-light">STT</th>
                                <th class="text-light">Tên tài khoản</th>
                                <th class="text-center text-light">Email</th>
                                <th class="text-center text-light">Trạng thái</th>
                                <th class="text-center text-light">Vai trò</th>
                                <th class="text-center text-light">Ngày tạo</th>
                                <th class="text-center text-light">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $key=>$user)
                                <tr>
                                    {{-- <td wire:key='{{$user->id}}' class="text-center fw-bold">{{($users->currentPage()-1)*$users->perPage()+$key+1}}</td> --}}
                                    <td wire:key='{{$user->id}}' class="text-center fw-bold">{{$users->firstItem()+$key}}</td>
                                    <td>
                                        <strong class="text-info">{{ $user->name }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-gradient fs-6" style="background: linear-gradient(45deg, #667eea, #764ba2);">
                                            {{ $user->email }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                         @switch($user->status)
                                             @case('active')
                                                 <span class="badge bg-success">Hoạt động</span>
                                                 @break
                                             @case('inactive')
                                                 <span class="badge bg-warning text-dark">Ngừng hoạt động</span>
                                                 @break
                                             @case('banned')
                                                 <span class="badge bg-danger">Đã bị chặn</span>
                                                 @break
                                         @endswitch
                                    </td>
                                    <td class="text-center">
                                        @switch($user->role)
                                        @case('admin')
                                            <span class="badge bg-primary">Quản trị viên</span>
                                            @break
                                        @case('staff')
                                            <span class="badge bg-secondary">Nhân viên</span>
                                            @break
                                        @case('user')
                                            <span class="badge bg-success">Người dùng</span>
                                            @break
                                        @default
                                            <span class="badge bg-light text-dark">Lỗi</span>
                                            @break
                                        @endswitch                                    
                                    <td class="text-center">
                                        <span class="text-info">
                                            {{ $user->created_at ? $user->created_at->format('d/m/Y') : 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                         <!-- Hành động với user -->
                                        <div class="d-flex gap-3 justify-content-center">
                                                {{-- Xem chi tiết --}}
                                            <a href="{{ route('admin.users.detail',$user->id) }}"
                                               class="btn btn-sm btn-info"
                                                title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>                          
                                                {{-- Chỉnh sửa --}}
                                            <a href="{{ route('admin.users.edit',$user->id) }}"
                                                class="btn btn-sm btn-warning"
                                                title="Chỉnh sửa">
                                               <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>
                                                    Không có tài khoản nào ở đây
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div >
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
