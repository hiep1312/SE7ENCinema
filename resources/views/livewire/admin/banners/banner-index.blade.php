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
                                <th class="text-center text-light">Link liên kết</th>
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
                                             class="img-thumbnail banner-image-clickable"
                                             style="width: 80px; height: 50px; object-fit: cover; border-radius: 0; cursor: pointer;"
                                             data-bs-toggle="modal"
                                             data-bs-target="#bannerImageModal"
                                             data-banner-id="{{ $banner->id }}"
                                             onclick="showBannerModal({{ $banner->id }})">
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
                                        @if($banner->link)
                                            <a href="{{ $banner->link }}"
                                               target="_blank"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Mở link liên kết">
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
                                                    wire:click="deleteBanner({{ $banner->id }})"
                                                    wire:confirm.warning="Bạn có chắc chắn muốn xóa banner '{{ $banner->title }}'? Hành động này không thể hoàn tác!"
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

    <!-- Banner Image Modal with Carousel -->
    <div class="modal fade" id="bannerImageModal" tabindex="-1" aria-labelledby="bannerImageModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content bg-dark">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title text-light" id="bannerImageModalLabel">
                        <i class="fas fa-images me-2"></i>Xem Banner
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    @if($banners->count() > 0)
                        <div id="bannerCarousel" class="carousel slide" data-bs-ride="false">
                            <!-- Carousel Indicators -->
                            <div class="carousel-indicators">
                                @foreach($banners as $index => $banner)
                                    <button type="button"
                                            data-bs-target="#bannerCarousel"
                                            data-bs-slide-to="{{ $index }}"
                                            class="{{ $index === 0 ? 'active' : '' }}"
                                            aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                            aria-label="Banner {{ $index + 1 }}">
                                    </button>
                                @endforeach
                            </div>

                            <!-- Carousel Items -->
                            <div class="carousel-inner">
                                @foreach($banners as $index => $banner)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <div class="position-relative">
                                            <img src="{{ asset($banner->image) }}"
                                                 class="d-block w-100"
                                                 alt="{{ $banner->title }}"
                                                 style="max-height: 70vh; object-fit: contain;"
                                                 data-banner-id="{{ $banner->id }}">

                                            <!-- Banner Info Overlay -->

                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Carousel Controls -->
                            @if($banners->count() > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="modal-footer border-secondary">
                    <div class="d-flex justify-content-between w-100 align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Sử dụng mũi tên hoặc vuốt để chuyển banner
                        </small>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Đóng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Auto refresh every 30 seconds -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto refresh every 30 seconds to check for expired banners
            // The setInterval below was removed as Livewire's wire:poll.10s
            // on the main container already handles periodic refreshes,
            // and updateExpiredBanners is called in the render method.
            // This prevents redundant calls and potential conflicts with DOM updates.
            /*
            setInterval(function() {
                @this.call('updateExpiredBanners');
            }, 30000);
            */

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

        // Function to show banner modal at specific index, now using banner ID
        function showBannerModal(bannerId) {
            const carousel = document.getElementById('bannerCarousel');
            if (carousel) {
                const carouselItems = carousel.querySelectorAll('.carousel-item');
                let targetIndex = -1;

                carouselItems.forEach((item, index) => {
                    const img = item.querySelector('img');
                    if (img && img.dataset.bannerId == bannerId) {
                        targetIndex = index;
                    }
                });

                if (targetIndex !== -1) {
                    // Ensure Bootstrap Carousel is re-initialized if Livewire re-rendered the modal
                    const bsCarousel = new bootstrap.Carousel(carousel);
                    bsCarousel.to(targetIndex);
                }
            }
        }

        // Add keyboard navigation for modal
        document.addEventListener('keydown', function(e) {
            const modal = document.getElementById('bannerImageModal');
            if (modal && modal.classList.contains('show')) { // Check if modal exists and is shown
                const carousel = document.getElementById('bannerCarousel');
                const bsCarousel = bootstrap.Carousel.getInstance(carousel); // Get existing instance

                if (bsCarousel) { // Only proceed if an instance exists
                    if (e.key === 'ArrowLeft') {
                        e.preventDefault();
                        bsCarousel.prev();
                    } else if (e.key === 'ArrowRight') {
                        e.preventDefault();
                        bsCarousel.next();
                    } else if (e.key === 'Escape') {
                        const bsModal = bootstrap.Modal.getInstance(modal);
                        if (bsModal) bsModal.hide(); // Hide if instance exists
                    }
                }
            }
        });

        // Add touch/swipe support for mobile
        let startX = 0;
        let endX = 0;

        document.getElementById('bannerCarousel')?.addEventListener('touchstart', function(e) {
            startX = e.touches[0].clientX;
        });

        document.getElementById('bannerCarousel')?.addEventListener('touchend', function(e) {
            endX = e.changedTouches[0].clientX;
            handleSwipe();
        });

        function handleSwipe() {
            const carousel = document.getElementById('bannerCarousel');
            const bsCarousel = bootstrap.Carousel.getInstance(carousel);

            if (bsCarousel) { // Only proceed if an instance exists
                if (startX - endX > 50) {
                    // Swipe left - next
                    bsCarousel.next();
                } else if (endX - startX > 50) {
                    // Swipe right - prev
                    bsCarousel.prev();
                }
            }
        }
    </script>
</div>
