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

                    <!-- Lọc theo phương thức thanh toán -->
                    <div class="col-md-3 col-lg-2">
                        <select wire:model.live="sortByDate" class="form-select bg-dark text-light">
                            <option disabled>Sắp xếp theo thời gian</option>
                            <option value="">Mới nhất</option>
                            <option value="7">7 ngày trước</option>
                            <option value="30">30 ngày trước</option>
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
                {{-- <div class="date-info fw-bold mb-3">
                    Thứ Hai, 11 tháng 8, 2025 (4 suất chiếu - 2 phim)
                </div>
                <div class="movie-card border rounded p-3">
                    <div class="row">
                        <div class="col-lg-4" data-bs-toggle="collapse" data-bs-target="#showtimes1" style="cursor: pointer;">
                            <div class="d-flex">
                                <div class="movie-poster">
                                    <img src="/placeholder.svg?height=160&width=120" alt="Avengers: Endgame" style="width: 100%; height: 100%; object-fit: cover; border-radius: 0;">
                                    {{-- @if($showtime->movie->poster)
                                        <img src="{{ asset('storage/' . $showtime->movie->poster) }}"
                                            alt="Ảnh phim {{ $showtime->movie->title }}" >
                                    @else
                                        <i class="fas fa-film" style="font-size: 22px;"></i>
                                    @endif -}}
                                </div>
                                {{-- <img src="/placeholder.svg?height=160&width=120" alt="Avengers: Endgame" class="movie-poster me-3"> -}}
                                <div class="flex-grow-1">
                                    <h3 class="movie-title">Avengers: Endgame</h3>
                                    <div class="movie-info mb-2">Hành động, Phiêu lưu • 181 phút</div>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <span class="badge-custom">3 suất chiếu</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8 collapse" id="showtimes1">
                            <div class="table-responsive">
                                <table class="table table-dark table-hover mb-0">
                                    <thead>
                                        <tr style="border-color: #3a3d4a;">
                                            <th scope="col" class="text-center" style="width: 60px;">#</th>
                                            <th scope="col">Phòng chiếu</th>
                                            <th scope="col" >Thời gian</th>
                                            <th scope="col" class="text-center">Trạng thái</th>
                                            <th scope="col" class="text-center" style="width: 100px;">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr style="border-color: #3a3d4a;">
                                            <td class="text-center"><span class="showtime-number">1</span></td>
                                            <td><div class="room-name">Room 1</div></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="status-indicator"></span>
                                                    <span class="time-slot">09:00 - 12:01</span>
                                                </div>
                                            </td>
                                            <td class="text-center"><span class="status-active">Đang hoạt động</span></td>
                                            <td class="text-center">
                                                <div class="d-flex gap-1 justify-content-center">
                                                    <button class="btn btn-edit"><i class="fas fa-edit"></i></button>
                                                    <button class="btn btn-delete"><i class="fas fa-trash"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="border-color: #3a3d4a;">
                                            <td class="text-center"><span class="showtime-number">2</span></td>
                                            <td><div class="room-name">Room 2</div></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="status-indicator"></span>
                                                    <span class="time-slot">14:30 - 17:31</span>
                                                </div>
                                            </td>
                                            <td class="text-center"><span class="status-active">Đang hoạt động</span></td>
                                            <td class="text-center">
                                                <div class="d-flex gap-1 justify-content-center">
                                                    <button class="btn btn-edit"><i class="fas fa-edit"></i></button>
                                                    <button class="btn btn-delete"><i class="fas fa-trash"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="border-color: #3a3d4a;">
                                            <td class="text-center"><span class="showtime-number">3</span></td>
                                            <td><div class="room-name">Room 1</div></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="status-indicator"></span>
                                                    <span class="time-slot">19:00 - 22:01</span>
                                                </div>
                                            </td>
                                            <td class="text-center"><span class="status-active">Đang hoạt động</span></td>
                                            <td class="text-center">
                                                <div class="d-flex gap-1 justify-content-center">
                                                    <button class="btn btn-edit"><i class="fas fa-edit"></i></button>
                                                    <button class="btn btn-delete"><i class="fas fa-trash"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <style>
                        .time-slot::before {
                            content: "●";
                            color: #10b981;
                            margin-right: 8px;
                        }
                    </style>
                </div> --}}
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <tr>
                                <th class="text-center text-light">STT</th>
                                <th class="text-center text-light">Ảnh phim</th>
                                <th class="text-center text-light">Phim chiếu</th>
                                <th class="text-center text-light">Phòng chiếu</th>
                                <th class="text-center text-light">Khung giờ chiếu</th>
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
                                    <td colspan="8" class="text-center py-4">
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
                    {{-- {{ $showtimes->links() }} --}}
                </div>
            </div>
        </div>
    </div>
</div>
