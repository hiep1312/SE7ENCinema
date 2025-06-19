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
            <h2 class="text-light">Quản lý banner</h2>
            <div>
                <a href="{{ route('admin.banners.create') }}" class="btn btn-success me-2">
                    <i class="fas fa-plus me-1"></i>Thêm banner
                </a>
            </div>
        </div>

        <div class="card bg-dark" wire:poll.6s>
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="row g-3">
                    <!-- Tìm kiếm -->
                    <div class="col-md-4 col-lg-3">
                        <div class="input-group">
                            <input type="text" wire:model.live.debounce.300ms="search"
                                class="form-control bg-dark text-light" placeholder="Tìm kiếm banner...">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2">
                        <select wire:model.live="statusFilter" class="form-select bg-dark text-light">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active">Hoạt động</option>
                            <option value="inactive">Ngừng hoạt động</option>
                        </select>
                    </div>

                    <!-- Lọc theo độ ưu tiên -->
                    <div class="col-md-5 col-lg-4 mb-2 mb-md-0 d-flex align-items-center gap-2">
                        <span id="lowerValue" x-text="$wire.priorityFilter[0]"></span>
                        <div class="dual-range">
                            <div class="range-track"></div>
                            <div class="range-fill" id="rangeFill" wire:ignore.self></div>
                            <input type="range" class="range-input lower" id="lowerRange" min="0" max="100" value="{{ $priorityFilter[0] }}" wire:input="$js.updateSlider">
                            <input type="range" class="range-input upper" id="upperRange" min="0" max="100" value="{{ $priorityFilter[1] }}" wire:input="$js.updateSlider">
                        </div>
                        <span id="upperValue" x-text="$wire.priorityFilter[1]"></span>
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
                                <th class="text-center text-light">Ảnh</th>
                                <th class="text-center text-light">Tiêu đề</th>
                                <th class="text-center text-light">Thời gian hiển thị</th>
                                <th class="text-center text-light">Độ ưu tiên</th>
                                <th class="text-center text-light">Trạng thái</th>
                                <th class="text-center text-light">Đường dẫn liên kết</th>
                                <th class="text-center text-light">Ngày tạo</th>
                                <th class="text-center text-light">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($banners as $banner)
                                <tr wire:key="{{ $banner->id }}">
                                    <td class="text-center fw-bold text-light">{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        <div class="mt-1 overflow-auto d-block text-center"
                                            style="max-height: 70px; width: 100px;" data-bs-toggle="modal" data-bs-target="#bannerPreview" data-banner-id="{{ $banner->id }}">
                                            <img src="{{ asset('storage/' . ($banner->image ?? '404.webp')) }}"
                                                class="img-thumbnail" loading = "lazy"
                                                alt="Ảnh banner{{ $banner->title }}"
                                                style="width: 100px; height: 70px; object-fit: cover; border-radius: 0; cursor: pointer;">
                                        </div>
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
                                                {{ $banner->end_date?->format('d/m/Y H:i') ?? 'Vĩnh viễn' }}
                                            </small>
                                        </div>
                                        @switch($displayStatuses[$loop->index]['displayStatus'])
                                            @case('expired')
                                                <div class="mt-1">
                                                    <span class="badge" style="background-color: #ffd700; color: #212529;">
                                                        <i class="fas fa-clock me-1"></i>Đã hết hạn
                                                    </span>
                                                </div>
                                                @break
                                            @case('upcoming')
                                                <div class="mt-1">
                                                    <span class="badge" style="background-color: #40c4ff; color: #ffffff;">
                                                        <i class="fas fa-clock me-1"></i>Chưa bắt đầu
                                                    </span>
                                                </div>
                                                @break
                                            @case('active')
                                                <div class="mt-1">
                                                    <span class="badge" style="background-color: #28a745; color: #ffffff;">
                                                        <i class="fas fa-play me-1"></i>Đang hiển thị
                                                    </span>
                                                </div>
                                                @break
                                        @endswitch
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary fs-6">
                                            {{ $banner->priority }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($banner->status === 'active')
                                            <span class="badge bg-success">Hoạt động</span>
                                        @else
                                            <span class="badge bg-danger">Ngừng hoạt động</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($banner->link)
                                            <a href="{{ $banner->link }}"
                                               target="_blank"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Mở đường dẫn liên kết">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">Không có liên kết</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="text-light">
                                            {{ $banner->created_at->format('d/m/Y H:i') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <!-- Toggle Status -->
                                            @php $checkActive = ($banner->status === 'active' && $displayStatuses[$loop->index]['displayStatus'] !== 'expired') @endphp
                                            <button type="button"
                                                    wire:click="toggleStatus({{ $banner->id }})"
                                                    class="btn btn-sm {{ $checkActive ? 'btn-info' : 'btn-success' }}"
                                                    title="{{ $checkActive ? 'Tắt' : 'Bật' }}"
                                                    @if($displayStatuses[$loop->index]['displayStatus'] === 'expired' || $banner->status === 'inactive') disabled @endif>
                                                <i class="fas {{ $checkActive ? 'fa-pause' : 'fa-play' }}" style="margin-right: 0"></i>
                                            </button>

                                            <a href="{{ route('admin.banners.edit', $banner->id) }}"
                                                class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                                <i class="fas fa-edit" style="margin-right: 0"></i>
                                            </a>

                                            <button type="button" class="btn btn-sm btn-danger"
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
                                            <p>
                                                Không có banner nào
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $banners->links() }}
                </div>
            </div>
        </div>
        <div class="modal fade" id="bannerPreview" wire:ignore>
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-light">
                            <i class="fas fa-images me-2"></i>Xem trước banner
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="slideshowBanner" class="carousel slide">
                            <div class="carousel-inner">
                                @foreach($banners as $banner)
                                    <div class="carousel-item" data-banner-id="{{ $banner->id }}">
                                        <img src="{{ asset('storage/' . ($banner->image ?? '404.webp')) }}" class="d-block w-100" alt="Ảnh banner {{ $banner->title }}" style="object-fit: cover; aspect-ratio: 16 / 8;">
                                        <div class="carousel-caption d-none d-md-block text-light">
                                            <h5>{{ $banner->title }}</h5>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#slideshowBanner" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Trước đó</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#slideshowBanner" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Tiếp theo</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@script
<script>
    const bannerPreview = document.querySelector('#bannerPreview');
    bannerPreview.addEventListener('show.bs.modal', function (event) {
        const idActive = event.relatedTarget.getAttribute('data-banner-id');
        const framePreview = event.target;

        const itemActive = framePreview.querySelector(`[data-banner-id="${idActive}"]`);
        itemActive.classList.add('active');
    });

    bannerPreview.addEventListener('hidden.bs.modal', function (event) {
        const framePreview = event.target;

        const itemActive = framePreview.querySelector('.active');
        itemActive.classList.remove('active');
    });

    $js('resetSlider', function() {
        document.getElementById('lowerRange').value = 0;
        document.getElementById('upperRange').value = 100;
        document.getElementById('rangeFill').style = "left: 0%; width: 100%;";
    })

    $js('updateSlider', function() {
        // Lấy element
        const lowerRange = document.getElementById('lowerRange');
        const upperRange = document.getElementById('upperRange');
        const rangeFill = document.getElementById('rangeFill');

        // Lấy và phân tích giá trị
        const lower = lowerRange?.valueAsNumber ?? parseInt(lowerRange.value);
        const upper = upperRange?.valueAsNumber ?? parseInt(upperRange.value);

        // Kiểm tra logic tránh lower >= upper && upper <= lower
        lower >= upper && (lowerRange.value = upper - 1);
        upper <= lower && (upperRange.value = lower + 1);

        // Tính phần trăm
        const lowerPercent = (lowerRange.value / lowerRange.max) * 100;
        const upperPercent = (upperRange.value / upperRange.max) * 100;

        // Cập nhật thanh fill
        rangeFill.style.left = lowerPercent + '%';
        rangeFill.style.width = (upperPercent - lowerPercent) + '%';

        $wire.$set('priorityFilter', [lower, upper], true);
    })
</script>
@endscript
