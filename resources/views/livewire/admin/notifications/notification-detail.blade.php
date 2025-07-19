<div class="scRender">
    <div class="container-lg mb-4" wire:poll>
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Chi tiết thông báo: {{ $notification->title }}</h2>
            <div>
                <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Quay lại
                </a>
            </div>
        </div>

        @if($this->hasReader($notification->id))
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Thông báo đã có người đọc. Một số chức năng sẽ bị hạn chế.
            </div>
        @endif

        <div class="row mb-4 g-3">
            <div class="col-md-6">
                <div class="card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Tổng số người đã đọc</h6>
                                <h3 class="mb-0">{{ $notification->users_count }} / {{ $notification->users->count() }}</h3>
                                <small>Người đã đọc</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-user fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Mức độ đọc thông báo</h6>
                                <h3 class="mb-0">{{ number_format(($notification->users_count / ($notification->users->count() ?: 1)) * 100, 0, '.', '.') }}%</h3>
                                <small>Trung bình</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fa-solid fa-circle-check fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs bg-dark" role="tablist">
            <li class="nav-item">
                <button class="nav-link @if($tabCurrent === 'overview') active bg-light text-dark @else text-light @endif"
                        wire:click="$set('tabCurrent', 'overview')"
                        style="border-top-right-radius: 0;">
                    <i class="fas fa-info-circle me-1"></i>Tổng quan
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link @if($tabCurrent === 'userNotifications') active bg-light text-dark @else text-light @endif"
                        wire:click="$set('tabCurrent', 'userNotifications')"
                        style="border-top-left-radius: 0;">
                    <i class="fa-solid fa-user-group me-1"></i>Người nhận
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-1">
            <!-- Overview Tab -->
            @if($tabCurrent === 'overview')
                <div class="row">
                    <div class="col-lg-8 col-xl-9">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-info me-2"></i>Thông tin chi tiết</h5>
                            </div>
                            <div class="card-body bg-dark" style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                <div class="table-responsive">
                                    <table class="table table-borderless text-light">
                                        <tr>
                                            <td><strong class="text-warning">Tiêu đề:</strong></td>
                                            <td><strong>{{ $notification->title }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td><strong class="text-warning">Nội dung:</strong></td>
                                            <td class="text-wrap text-muted lh-base text-start">{{ $notification->content ?? 'Không có nội dung' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong class="text-warning">Đường dẫn liên kết:</strong></td>
                                            <td>
                                                @if($notification->link)
                                                    <a href="{{ $notification->link }}"
                                                    target="_blank"
                                                    class="btn btn-sm btn-outline-primary"
                                                    title="Mở đường dẫn liên kết">
                                                        <i class="fas fa-external-link-alt"></i>
                                                    </a>
                                                @else
                                                    <span class="text-muted">Không có đường dẫn liên kết</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong class="text-warning">Ngày tạo:</strong></td>
                                            <td>{{ $notification->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xl-3 mt-4 mt-lg-0">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-image me-2"></i>Ảnh thumbnail</h5>
                            </div>
                            <div class="card-body bg-dark"
                                style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                <div class="notification-image w-100" style="width: auto; height: auto;">
                                    @if($notification->thumbnail)
                                        <img src="{{ asset('storage/' . $notification->thumbnail) }}"
                                            alt="Ảnh thông báo {{ $notification->title }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 0;">
                                    @else
                                        <i class="fa-solid fa-bell"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($tabCurrent === 'userNotifications')
                <div class="row">
                    <div class="col-12">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fa-solid fa-list me-2"></i>Danh sách chi tiết người nhận</h5>
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
                                                <th class="text-center text-light">Vai trò</th>
                                                <th class="text-center text-light">Trạng thái</th>
                                                <th class="text-center text-light">Ngày đọc</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($users as $user)
                                                <tr wire:key="user-{{ $user->id }}">
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
                                                                <span class="badge bg-warning text-dark"><i class="fa-solid fa-calendar-clock me-1"></i>Không hoạt động</span>
                                                                @break
                                                            @case('banned')
                                                                <span class="badge bg-danger"><i class="fa-solid fa-calendar-xmark me-1"></i>Bị cấm</span>
                                                                @break
                                                        @endswitch
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge" style="background: linear-gradient(to right, #642b73, #c6426e) !important;">{{ $user->email }}
                                                            @if ($user->phone)
                                                                / {{ $user->phone }}
                                                            @endif
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <p class="text-wrap text-muted lh-base" style="margin-bottom: 0;">{{ Str::limit($user->address ?? 'N/A', 50, '...') }}</p>
                                                    </td>
                                                    <td class="text-center">
                                                        @switch($user->role)
                                                            @case('admin')
                                                                <span class="badge-clean-base badge-clean-yellow">
                                                                    <i class="fa-solid fa-crown me-1"></i>
                                                                    Quản trị viên
                                                                </span>
                                                                @break
                                                            @case('staff')
                                                                <span class="badge-clean-base badge-clean-rose">
                                                                    <i class="fa-solid fa-loveseat me-1"></i>
                                                                    Nhân viên
                                                                </span>
                                                                @break
                                                            @case('user')
                                                                <span class="badge-clean-base badge-clean-purple">
                                                                    <i class="fa-solid fa-chair-office me-1"></i>
                                                                    Người dùng
                                                                </span>
                                                                @break
                                                        @endswitch
                                                    </td>
                                                    <td class="text-center">
                                                        @switch($user->pivot->is_read)
                                                            @case(true)
                                                                <span class="badge bg-success">Đã đọc</span>
                                                                @break
                                                            @case(false)
                                                                <span class="badge bg-danger">Chưa đọc</span>
                                                                @break
                                                        @endswitch
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="text-light">
                                                            {{ $user->pivot->is_read ? $user->pivot->updated_at->format('d/m/Y H:i') : 'N/A' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                                            <p>Không chỉ định người nhận nào</p>
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
            @endif
        </div>
    </div>
</div>
