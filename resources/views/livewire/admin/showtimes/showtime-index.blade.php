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
            <h2 class="text-light">Quản lý suất chiếu</h2>
            <div>
                <a href="{{ route('admin.showtimes.create') }}" class="btn btn-success me-2">
                    <i class="fas fa-plus me-1"></i>Thêm suất chiếu
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
                                   placeholder="Tìm kiếm suất chiếu...">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Lọc theo trạng thái -->
                    <div class="col-md-3 col-lg-2">
                        <select wire:model.live="statusFilter" class="form-select bg-dark text-light">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active">Đang hoạt động</option>
                            <option value="canceled">Đã bị hủy</option>
                            <option value="completed">Đã hoàn thành</option>
                        </select>
                    </div>

                    <div class="col-md-3 col-lg-2">
                        <button class="btn btn-outline-secondary bg-dark text-light w-100" type="button"
                            data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-funnel" viewBox="0 0 16 16">
                                <path
                                    d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2z" />
                            </svg>
                            Bộ lọc nâng cao
                        </button>
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
                                <th class="text-center text-light">Ảnh phim</th>
                                <th class="text-center text-light">Phim chiếu</th>
                                <th class="text-center text-light">Phòng chiếu</th>
                                <th class="text-center text-light">Khung giờ chiếu</th>
                                <th class="text-center text-light">Giá khung giờ</th>
                                <th class="text-center text-light">Trạng thái</th>
                                <th class="text-center text-light">Ngày tạo</th>
                                <th class="text-center text-light">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($showtimes as $showtime)
                                <tr>
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <div class="movie-poster" style="width: 80px; height: 100px; margin: 0;">
                                                @if($showtime->movie->poster)
                                                    <img src="{{ asset('storage/' . $showtime->movie->poster) }}"
                                                        alt="Ảnh phim {{ $showtime->movie->title }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 0;">
                                                @else
                                                    <i class="fas fa-film" style="font-size: 22px;"></i>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td style="max-width: 200px;">
                                        <strong class="text-light text-wrap lh-base">{{ $showtime->movie->title }}</strong>
                                        <div class="movie-genre" style="margin-bottom: 0; margin-top: 3px;">
                                            <i class="fas fa-tags me-1"></i>
                                            {{ $showtime->movie->genres->take(1)->implode('name', ', ') ?: 'Không có thể loại' }} • {{ $showtime->movie->duration }} phút
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <strong class="text-light text-wrap lh-base">{{ $showtime->room->name }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <i class="fas fa-clock me-1" style="color: #34c759;"></i>
                                        <span style="color: #34c759;">
                                            {{ $showtime->start_time->format('d/m/Y') }}
                                        </span>
                                        <br>
                                        <small class="text-muted ms-3">
                                            {{ $showtime->start_time->format('H:i') }} -
                                            {{ $showtime->end_time->format('H:i') }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-gradient fs-6">
                                            {{ number_format($showtime->price, 0, '.', '.') }}đ
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @switch($showtime->status)
                                            @case('active')
                                                <span class="badge bg-primary">Đang hoạt động</span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-success">Đã hoàn thành</span>
                                                @break
                                            @case('canceled')
                                                <span class="badge bg-danger">Đã bị hủy</span>
                                                @break
                                        @endswitch
                                    </td>

                                    <td class="text-center">
                                        <span class="text-light">
                                            {{ $showtime->created_at ? $showtime->created_at->format('d/m/Y H:i') : 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            @if($showtime->status !== "completed" && $showtime->start_time->isFuture())
                                                <a href="{{ route('admin.showtimes.edit', $showtime->id) }}"
                                                    class="btn btn-sm btn-warning"
                                                    title="Chỉnh sửa">
                                                    <i class="fas fa-edit" style="margin-right: 0"></i>
                                                </a>
                                            @else
                                                <button type="button"
                                                        class="btn btn-sm btn-warning"
                                                        wire:sc-alert.error="Không thể chỉnh sửa suất chiếu đang chiếu hoặc đã hoàn thành!"
                                                        wire:sc-model
                                                        title="Chỉnh sửa">
                                                    <i class="fas fa-edit" style="margin-right: 0"></i>
                                                </button>
                                            @endif
                                            @if($showtime->isLockedForDeletion())
                                                <button type="button"
                                                        class="btn btn-sm btn-danger"
                                                        wire:sc-alert.error="Không thể xóa suất chiếu sẽ diễn ra trong vòng 1 giờ tới hoặc đang diễn ra, hoặc đã có người đặt vé!"
                                                        wire:sc-model
                                                        title="Xóa">
                                                    <i class="fas fa-trash" style="margin-right: 0"></i>
                                                </button>
                                            @else
                                                <button type="button"
                                                        class="btn btn-sm btn-danger"
                                                        wire:sc-model="deleteShowtime({{ $showtime->id }})"
                                                        wire:sc-confirm.warning="Bạn có chắc chắn muốn xóa suất chiếu '{{ $showtime->start_time->format('d/m/Y H:i') }} - {{ $showtime->end_time->format('H:i') }}'?"
                                                        title="Xóa">
                                                    <i class="fas fa-trash" style="margin-right: 0"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>
                                                Không có suất chiếu nào
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $showtimes->links() }}
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" data-bs-backdrop="false"
        id="filterOffcanvas" style="width: 400px;">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">
                <i class="bi bi-funnel me-2"></i>
                Bộ lọc nâng cao
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <div class="filter-section">
                <h6>
                    <i class="bi bi-currency-dollar me-2 text-warning"></i>
                    Giá khung giờ
                </h6>
                <div class="mb-3">
                    <label for="priceRange" class="form-label">
                        Tối đa: <span id="priceValue" class="text-warning fw-bold">{{ number_format($priceFilters,
                            0, '.', '.') }}đ</span>
                    </label>
                    <input wire:model.live="priceFilters" type="range" class="form-range" id="priceRange"
                        min="{{ $priceMaxMin['0'] }}" max="{{ $priceMaxMin['1'] }}" step="1000">
                    <div class="d-flex justify-content-between text-muted small">
                        <span>{{ number_format($priceMaxMin['0'],0, '.', '.') }}đ</span>
                        <span>{{ number_format($priceMaxMin['1'],0, '.', '.') }}đ</span>
                    </div>
                </div>
            </div>
            <!-- Release Year Filter -->
            <div class="filter-section">
                <h6>
                    <i class="bi bi-calendar me-2 text-primary"></i>
                    Khung giờ chiếu
                </h6>
                <div class="row g-2">
                    <div class="col-6">
                        <input wire:model.live="startTime.from" type="datetime-local" class="form-select text-white bg-dark border-secondary"
                            id="yearFrom">
                        </input>
                    </div>
                    <div class="col-6">
                        <input wire:model.live="startTime.to" type="datetime-local" class="form-select text-white bg-dark border-secondary"
                            id="yearTo">
                        </input>
                    </div>
                </div>
            </div>

            <!-- Filter Actions -->
            <div class="d-grid gap-2 mt-4">
                <button class="btn btn-success" data-bs-dismiss="offcanvas">
                    <i class="fa-solid fa-check me-2"></i>
                    Áp dụng bộ lọc
                </button>
                <button class="btn btn-outline-secondary" wire:click="resetFilters">
                    <i class="fa-solid fa-arrow-rotate-right me-2"></i>
                    Xóa bộ lọc nâng cao
                </button>
            </div>
        </div>
    </div>
</div>
