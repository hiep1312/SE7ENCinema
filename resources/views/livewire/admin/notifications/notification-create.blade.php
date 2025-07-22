@use('App\Models\User')
@assets
<script>
    function updateSelected(selectedAll = true){
        const checkboxItems = document.querySelectorAll('#checkboxList input[type="checkbox"]');
        checkboxItems.forEach(item => {
            item.checked !== selectedAll && item.click();
        });
    }
</script>
@endassets
<div class="scRender">
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Thêm thông báo mới</h2>
            <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <!-- Form thông tin thông báo -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-dark">
                    <div class="card-header bg-gradient text-light"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="my-1">Thông tin thông báo</h5>
                    </div>
                    <div class="card-body bg-dark">
                        <form wire:submit.prevent="createNotification" enctype="multipart/form-data" novalidate>
                            <div class="row align-items-start mb-2">
                                @if ($thumbnail)
                                    <div class="col-md-3 col-xl-2 col-6 mb-3">
                                        <div class="mt-1 notification-image w-100">
                                            <img src="{{ $thumbnail->temporaryUrl() }}" alt="Ảnh thông báo"
                                                style="width: 100%; height: 100%; object-fit: cover; border-radius: 0;">
                                        </div>
                                    </div>
                                    <div class="col-md-9 col-xl-10 row">
                                @endif
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="title" class="form-label text-light">Tiêu đề thông báo *</label>
                                        <input type="text" id="title" wire:model="title"
                                            class="form-control bg-dark text-light border-light @error('title') is-invalid @enderror"
                                            placeholder="VD: Cảnh báo bảo trì hệ thống">
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="thumbnail" class="form-label text-light">Ảnh thumbnail</label>
                                        <input type="file" id = "thumbnail"
                                            wire:model.live="thumbnail"
                                            class="form-control bg-dark text-light border-light @error('thumbnail') is-invalid @enderror"
                                            accept="image/*">
                                        @error('thumbnail')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="link" class="form-label text-light">Đường dẫn liên kết</label>
                                        <input type="url" id = "link"
                                            wire:model.live.debounce.500ms="link"
                                            class="form-control bg-dark text-light border-light @error('link') is-invalid @enderror"
                                            placeholder="VD: https://example.com/chi-tiet-thong-bao">
                                        @error('link')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="content" class="form-label text-light">Nội dung *</label>
                                        <textarea id="content" wire:model="content" class="form-control bg-dark text-light border-light @error('content') is-invalid @enderror"
                                        placeholder="VD: Hệ thống sẽ được bảo trì từ 22:00 đến 02:00 sáng mai. Trong thời gian này, bạn sẽ không thể truy cập. Mong bạn thông cảm."></textarea>
                                        @error('content')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                @if($thumbnail) </div> @endif
                            </div>
                            <div class="w-100 mt-2">
                                <ul class="nav nav-tabs bg-dark" role="tablist">
                                    <li class="nav-item">
                                        <button type="button" class="nav-link @if($tabCurrent === 'notificationTo') active bg-light text-dark @else text-light @endif"
                                                wire:click="$set('tabCurrent', 'notificationTo')"
                                                style="border-top-right-radius: 0;">
                                            <i class="fa-solid fa-bullhorn me-1"></i>Thông báo đến
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button type="button" class="nav-link @if($tabCurrent === 'link') active bg-light text-dark @else text-light @endif"
                                                wire:click="$set('tabCurrent', 'link')"
                                                style="border-top-left-radius: 0;">
                                            <i class="fa-regular fa-globe me-1 me-1"></i>Đường dẫn liên kết
                                        </button>
                                    </li>
                                </ul>
                                <div class="tab-content tab-manager">
                                    @if ($tabCurrent === 'notificationTo')
                                        <div class="search-box">
                                            <input type="text" wire:model.live.debounce.300ms="searchUser" placeholder="Tìm kiếm người dùng..." autocomplete="off">
                                            <div class="search-icon">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <circle cx="11" cy="11" r="8"></circle>
                                                    <path d="m21 21-4.35-4.35"></path>
                                                </svg>
                                            </div>
                                        </div>

                                        <div class="filter-controls">
                                            <button type="button" id="selectAll" class="control-btn" onclick="updateSelected(true)">Chọn tất cả</button>
                                            <button type="button" id="deselectAll" class="control-btn" onclick="updateSelected(false)">Bỏ chọn tất cả</button>
                                            <button type="button" id="clearSearch" class="control-btn clear-btn" wire:click="$set('searchUser', '')">Xóa tìm kiếm</button>
                                        </div>

                                        <div class="checkbox-container">
                                            <div class="checkbox-list" id="checkboxList">
                                                @forelse($users as $user)
                                                    <div class="checkbox-item" onclick="this.querySelector('input[type=checkbox]').click()" wire:key="user-{{ $user->id }}">
                                                        <div class="checkbox-wrapper">
                                                            <input type="checkbox" wire:model.live="usersSelected" value="{{ $user->id }}">
                                                            <span class="checkmark"></span>
                                                        </div>
                                                        <label class="checkbox-label">{{ $user->name }} <span style="color: #ffefbe;">({{ Str::limit($user->email, 30, '...') }})</span></label>
                                                    </div>
                                                @empty
                                                    <div class="empty-state">Không tìm thấy người dùng nào</div>
                                                @endforelse
                                            </div>
                                        </div>
                                        <div class="selected-items">
                                            <h3>Đã chọn <span id="selectedCount">{{ count($usersSelected) }}</span>/{{ $users->count() }} người dùng:</h3>
                                            <div class="selected-tags" id="selectedTags">
                                                @forelse($usersSelected as $userId)
                                                    <div class="tag">
                                                        {{ User::find($userId)?->name }}<span style="color: #ffefbe; margin-left: -4px;">({{ Str::limit(User::find($userId)?->email, 30, '...') }})</span>
                                                        <span class="remove-tag" onclick="document.querySelector('input[type=checkbox][value=\'{{ $userId }}\']').click()">×</span>
                                                    </div>
                                                @empty
                                                    <div class="empty-state" style="padding: 10px 20px;">Chưa có người dùng nào</div>
                                                @endforelse
                                            </div>
                                        </div>
                                        @error('usersSelected')
                                            <div class="error-panel" style="display: block;">
                                                <button type="button" class="close-error" onclick="this.parentElement.style.display = 'none'">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                                <div class="error-title">
                                                    <i class="fa-solid fa-triangle-exclamation error-icon"></i>
                                                    Lỗi xác thực
                                                </div>
                                                <div class="error-content">
                                                    {{ $message }}
                                                </div>
                                            </div>
                                        @enderror
                                    @elseif($tabCurrent === 'link')
                                        <div class="video-container glow-effect mt-1">
                                            <div class="video-header">
                                                <div class="video-icon">
                                                    <i class="fa-solid fa-eye"></i>
                                                </div>
                                                <div>
                                                    <h3 class="video-title">Xem trước đường dẫn liên kết</h3>
                                                </div>
                                            </div>
                                            <div class="video-wrapper" style="border-radius: 0;">
                                                <div class="responsive-iframe">
                                                    <iframe src="{{ getURLPath($link) ?: asset('storage/404.webp') }}"
                                                        title="YouTube video player" frameborder="0"
                                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                        allowfullscreen style="border-radius: 0;"></iframe>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Tạo thông báo
                                </button>
                                <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-danger">
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
