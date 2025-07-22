<div class="scRender">
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show mt-2 mx-2" role="alert" wire:ignore>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-2 mx-2" role="alert" wire:ignore>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Quản lý thông báo</h2>
            <div>
                <a href="{{ route('admin.notifications.create') }}" class="btn btn-success me-2">
                    <i class="fas fa-plus me-1"></i>Tạo thông báo
                </a>
            </div>
        </div>

        <div class="card bg-dark" wire:poll.6s>
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="row g-3">
                    <!-- Tìm kiếm -->
                    <div class="col-md-4 col-lg-3">
                        <div class="input-group">
                            <input type="text"
                                   wire:model.live.debounce.300ms="search"
                                   class="form-control bg-dark text-light"
                                   placeholder="Tìm kiếm thông báo...">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Lọc theo người dùng -->
                    <div class="col-md-3 col-lg-2">
                        <select wire:model.live="userFilter" class="form-select bg-dark text-light">
                            <option value="">Tất cả người dùng</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" wire:key="user-{{ $user->id }}">{{ $user->name }} ({{ Str::limit($user->email, 20, '...') }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Lọc theo trạng thái -->
                    <div class="col-md-3 col-lg-2">
                        <select wire:model.live="readFilter" class="form-select bg-dark text-light">
                            <option value="">Tất cả trạng thái</option>
                            <option value="1">Đã có người đọc</option>
                            <option value="0">Chưa có ai đọc</option>
                        </select>
                    </div>

                    <!-- Reset filters -->
                    <div class="col-md-2">
                        <button wire:click="resetFilters" class="btn btn-outline-warning">
                            <i class="fas fa-refresh me-1"></i>Reset
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
                                <th class="text-center text-light">Ảnh thumbnail</th>
                                <th class="text-center text-light">Tiêu đề thông báo</th>
                                <th class="text-center text-light">Nội dung</th>
                                <th class="text-center text-light">Đường dẫn liên kết</th>
                                <th class="text-center text-light">Trạng thái</th>
                                <th class="text-center text-light">Ngày tạo</th>
                                <th class="text-center text-light">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($notifications as $notification)
                                <tr wire:key="notification-{{ $notification->id }}">
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <div class="notification-image">
                                                @if($notification->thumbnail)
                                                    <img src="{{ asset('storage/' . $notification->thumbnail) }}"
                                                        alt="Ảnh thông báo {{ $notification->title }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 0;">
                                                @else
                                                    <i class="fa-solid fa-bell"></i>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong class="text-light">{{ Str::limit($notification->title, 30, '...') }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-wrap text-muted lh-base" style="margin-bottom: 0;">{{ Str::limit($notification->content ?? 'Không có nội dung', 100, '...') }}</p>
                                    </td>
                                    <td class="text-center">
                                        @if($notification->link)
                                            <a href="{{ $notification->link }}"
                                               target="_blank"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Mở đường dẫn liên kết">
                                                <i class="fas fa-external-link-alt" style="margin-right: 0;"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">Không có</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @switch($notification->users_count)
                                            @case($notification->users->count()) <span class="badge bg-success"> @break
                                            @case(0) <span class="badge bg-danger"> @break
                                            @default <span class="badge bg-primary"> @break
                                        @endswitch
                                        {{ $notification->users_count }} / {{ $notification->users->count() }} <i class="fas fa-user"></i> đã đọc</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-light">
                                            {{ $notification->created_at ? $notification->created_at->format('d/m/Y H:i') : 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('admin.notifications.detail', $notification->id) }}"
                                                class="btn btn-sm btn-info"
                                                title="Xem chi tiết">
                                                <i class="fas fa-eye" style="margin-right: 0"></i>
                                            </a>
                                            @if(!$this->hasReader($notification->id))
                                                <button type="button"
                                                        class="btn btn-sm btn-danger"
                                                        wire:sc-model="deleteNotification({{ $notification->id }})"
                                                        wire:sc-confirm.warning="Bạn có chắc chắn muốn xóa thông báo '{{ $notification->title }}'? Hành động này không thể hoàn tác!"
                                                        title="Xóa">
                                                    <i class="fas fa-trash" style="margin-right: 0"></i>
                                                </button>
                                            @else
                                                <button type="button"
                                                        class="btn btn-sm btn-danger"
                                                        wire:sc-alert.error="Không thể xóa thông báo đã có người đọc!"
                                                        wire:sc-model
                                                        title="Xóa">
                                                    <i class="fas fa-trash" style="margin-right: 0"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>
                                                Không có thông báo nào
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
