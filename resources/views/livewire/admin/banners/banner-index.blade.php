<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {!! nl2br(e(session('success'))) !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-lg mb-4" wire:poll.10s>
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Quản lý Banner</h2>
            <div>
                <a href="{{ route('admin.banners.create') }}" class="btn btn-success me-2">
                    <i class="fas fa-plus me-1"></i>Thêm Banner
                </a>
            </div>
        </div>

        <div class="card bg-dark">
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="row g-3">
                    <!-- Tìm kiếm -->
                    <div class="col-md-4 col-lg-3">
                        <div class="input-group">
                            <input type="text"
                                   wire:model.live.debounce.300ms="search"
                                   class="form-control bg-dark text-light border-light"
                                   placeholder="Tìm kiếm banner...">
                            <span class="input-group-text bg-dark border-light">
                                <i class="fas fa-search text-light"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Lọc theo trạng thái -->
                    <div class="col-md-3 col-lg-2">
                        <select wire:model.live="statusFilter" class="form-select bg-dark text-light border-light">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active">Hoạt động</option>
                            <option value="inactive">Không hoạt động</option>
                        </select>
                    </div>

                    <!-- Lọc theo độ ưu tiên -->
                    <div class="col-md-3 col-lg-5 bg-dark text-light bg-dark text-light border-custom" style="border: 1px solid #ffffff; border-radius: 8px;">
                        <div class="input-group ">
                            <input
                                type="range"
                                wire:model.live="priorityFilter"
                                class="text-light col-lg-8 bg-dark border-light"
                                min="0"
                                max="100"
                                step="1"
                                id="priorityRange"
                            >
                            <span class="input-group-text bg-dark border-dark text-light small" id="priorityValue">
                                {{ $priorityFilter ?: 'Tất cả' }}
                            </span>
                            <button
                                type="button"
                                wire:click="$set('priorityFilter', null)"
                                class="btn btn-outline-secondary btn-sm border-dark"
                                title="Reset"
                            >
                                <i class="fas fa-undo"></i>
                            </button>
                        </div>
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
                                <th class="text-light">Hình ảnh</th>
                                <th class="text-light">Tiêu đề</th>
                                <th class="text-center text-light">Thời gian hiển thị</th>
                                <th class="text-center text-light">Trạng thái</th>
                                <th class="text-center text-light">Độ ưu tiên</th>
                                <th class="text-center text-light">Link</th>
                                <th class="text-center text-light">Ngày tạo</th>
                                <th class="text-center text-light">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($banners as $banner)
                                @php
                                    $displayStatus = $this->getBannerDisplayStatus($banner);
                                    $priorityColor = $this->getPriorityColor($banner->priority);
                                @endphp
                                <tr>
                                    <td class="text-center fw-bold text-light">{{ $loop->iteration }}</td>
                                    <td>
                                        <img src="{{ asset($banner->image) }}"
                                             alt="{{ $banner->title }}"
                                             class="img-thumbnail"
                                             style="width: 80px; height: 50px; object-fit: cover; border-radius: 0;">
                                    </td>
                                    <td>
                                        <strong class="text-light">{{ $banner->title }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <div class="mb-1">
                                            <small style="color: #34c759;">
                                                <i class="fas fa-play me-1"></i>
                                                {{ $banner->start_date->format('d/m/Y H:i') }}
                                            </small>
                                        </div>
                                        <div>
                                            <small style="color: #ff4d4f;">
                                                <i class="fas fa-stop me-1"></i>
                                                {{ $banner->end_date->format('d/m/Y H:i') }}
                                            </small>
                                        </div>
                                        @if($displayStatus === 'expired')
                                            <div class="mt-1">
                                                <span class="badge" style="background-color: #ffd700; color: #212529;">
                                                    <i class="fas fa-clock me-1"></i>Đã hết hạn
                                                </span>
                                            </div>
                                        @elseif($displayStatus === 'upcoming')
                                            <div class="mt-1">
                                                <span class="badge" style="background-color: #40c4ff; color: #ffffff;">
                                                    <i class="fas fa-clock me-1"></i>Chưa bắt đầu
                                                </span>
                                            </div>
                                        @elseif($displayStatus === 'active')
                                            <div class="mt-1">
                                                <span class="badge" style="background-color: #28a745; color: #ffffff;">
                                                    <i class="fas fa-play me-1"></i>Đang hiển thị
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($banner->status === 'active')
                                            <span class="badge bg-success">Hoạt động</span>
                                        @else
                                            <span class="badge bg-danger">Không hoạt động</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $priorityColor }} fs-6">
                                            {{ $banner->priority }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($banner->image)
                                            <a href="{{ asset($banner->image) }}"
                                               target="_blank"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Xem hình ảnh">
                                                <i class="fas fa-image"></i>
                                            </a>
                                        @elseif($banner->link)
                                            <a href="{{ $banner->link }}"
                                               target="_blank"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Xem link">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">Không có</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="text-light">
                                            {{ $banner->created_at->format('d/m/Y H:i') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-3 justify-content-center">
                                            <!-- Toggle Status -->
                                            <button type="button"
                                                    wire:click="toggleStatus({{ $banner->id }})"
                                                    class="btn btn-sm {{ $banner->status === 'active' ? 'btn-warning' : 'btn-success' }}"
                                                    title="{{ $banner->status === 'active' ? 'Tắt' : 'Bật' }}"
                                                    @if($displayStatus === 'expired' && $banner->status === 'inactive') disabled @endif>
                                                <i class="fas {{ $banner->status === 'active' ? 'fa-pause' : 'fa-play' }}" style="margin-right: 0"></i>
                                            </button>

                                            <!-- Edit -->
                                            <a href="{{ route('admin.banners.edit', $banner->id) }}"
                                               class="btn btn-sm btn-info"
                                               title="Chỉnh sửa">
                                                <i class="fas fa-edit " style="margin-right: 0"></i>
                                            </a>

                                            <!-- Delete -->
                                            <button type="button"
                                                    class="btn btn-sm btn-danger"
                                                    wire:sc-model="deleteBanner({{ $banner->id }})"
                                                    wire:sc-confirm.warning="Bạn có chắc chắn muốn xóa banner '{{ $banner->title }}'? Hành động này không thể hoàn tác!"
                                                    title="Xóa">
                                                <i class="fas fa-trash" style="margin-right: 0"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>Không có banner nào</p>
                                            <a href="{{ route('admin.banners.create') }}" class="btn btn-success">
                                                <i class="fas fa-plus me-1"></i>Thêm Banner đầu tiên
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($banners->hasPages())
                    <div class="mt-3 d-flex justify-content-center">
                        {{ $banners->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Auto refresh every 30 seconds to check expired banners -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto refresh every 30 seconds to check for expired banners
            setInterval(function() {
                @this.call('updateExpiredBanners');
            }, 30000);

            // Also refresh when page becomes visible again
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    @this.call('updateExpiredBanners');
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const rangeInput = document.getElementById('priorityRange');
            const valueDisplay = document.getElementById('priorityValue');

            if (rangeInput && valueDisplay) {
                rangeInput.addEventListener('input', function() {
                    const value = this.value;
                    let displayText = value;



                    valueDisplay.textContent = displayText;
                });
            }
        });
    </script>
</div>
