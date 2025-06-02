<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Quản lý phòng chiếu</h2>
            <div>
                @if(!$showDeleted)
                    <a href="{{ route('admin.rooms.create') }}" class="btn btn-success me-2">
                        <i class="fas fa-plus me-1"></i>Thêm phòng chiếu
                    </a>
                @endif
                <button wire:click="$toggle('showDeleted')" class="btn btn-outline-danger">
                    @if($showDeleted)
                        <i class="fas fa-eye me-1"></i>Xem phòng hoạt động
                    @else
                        <i class="fas fa-trash me-1"></i>Xem phòng đã xóa
                    @endif
                </button>
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
                                   class="form-control bg-dark text-light"
                                   placeholder="Tìm kiếm phòng chiếu...">
                        </div>
                    </div>

                    <!-- Lọc theo trạng thái -->
                    @if(!$showDeleted)
                        <div class="col-md-3 col-lg-2">
                            <select wire:model.live="statusFilter" class="form-select bg-dark text-light">
                                <option value="">Tất cả trạng thái</option>
                                <option value="active">Hoạt động</option>
                                <option value="maintenance">Bảo trì</option>
                                <option value="inactive">Ngừng hoạt động</option>
                            </select>
                        </div>

                        <!-- Lọc theo suất chiếu -->
                        <div class="col-md-3 col-lg-2">
                            <select wire:model.live="showtimeFilter" class="form-select bg-dark text-light">
                                <option value="">Tất cả suất chiếu</option>
                                <option value="has_showtimes">Có suất chiếu</option>
                                <option value="no_showtimes">Không có suất chiếu</option>
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
                                <th class="text-light">Tên phòng</th>
                                <th class="text-center text-light">Sức chứa/Ghế hiện tại</th>
                                <th class="text-center text-light">Trạng thái</th>
                                <th class="text-center text-light">Bảo trì lần cuối</th>
                                <th class="text-center  text-light">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    Suất chiếu tiếp theo
                                </th>
                                @if($showDeleted)
                                    <th class="text-center text-light">Ngày xóa</th>
                                @else
                                    <th class="text-center text-light">Ngày tạo</th>
                                @endif
                                <th class="text-center text-light">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rooms as $room)
                                <tr>
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                    <td>
                                        <strong class="text-light">{{ $room->name }}</strong>
                                        @if($room->trashed())
                                            <span class="badge bg-danger ms-1">Đã xóa</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-gradient fs-6" style="background: linear-gradient(45deg, #667eea, #764ba2);">
                                            {{ $room->capacity }}/{{ $room->seats_count }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if(!$showDeleted && !$room->trashed())
                                            @switch($room->status)
                                                @case('active')
                                                    <span class="badge bg-success">Hoạt động</span>
                                                    @break
                                                @case('maintenance')
                                                    <span class="badge bg-warning text-dark">Bảo trì</span>
                                                    @break
                                                @case('inactive')
                                                    <span class="badge bg-danger">Ngừng hoạt động</span>
                                                    @break
                                            @endswitch
                                        @else
                                            <span class="badge bg-secondary">Đã xóa</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($room->last_maintenance_date)
                                            <span class="text-light">{{ $room->last_maintenance_date->format('d/m/Y') }}</span>
                                        @else
                                            <span class="text-muted">Chưa có</span>
                                        @endif
                                    </td>

                                    <!-- CỘT SUẤT CHIẾU TIẾP THEO -->
                                    <td class=" bg-opacity-10 border-start border-3">
                                        @if(!$showDeleted && $room->showtimes->count() > 0)
                                            @php $nextShowtime = $room->showtimes->first(); @endphp
                                            <div class="showtime-info">
                                                <!-- Tên phim -->
                                                <div class="movie-title mb-1">
                                                    <i class="fas fa-film me-1 text-primary"></i>
                                                    <strong class="text-primary">
                                                        {{ $nextShowtime->movie->title ?? 'Phim đã xóa' }}
                                                    </strong>
                                                </div>

                                                <!-- Thời gian chiếu -->
                                                <div class="showtime-schedule mb-1">
                                                    <i class="fas fa-clock me-1 text-success"></i>
                                                    <span class="text-success">
                                                        {{ $nextShowtime->start_time->format('d/m/Y') }}
                                                    </span>
                                                    <br>
                                                    <small class="text-muted ms-3">
                                                        {{ $nextShowtime->start_time->format('H:i') }} -
                                                        {{ $nextShowtime->end_time->format('H:i') }}
                                                    </small>
                                                </div>

                                                <!-- Giá vé -->
                                                <div class="showtime-price mb-1">
                                                    <i class="fas fa-money-bill me-1 text-warning"></i>
                                                    <span class="text-warning">
                                                        {{ number_format($nextShowtime->price,0, '.', '.') }}đ
                                                    </span>
                                                </div>

                                                <!-- Badge trạng thái -->
                                                @if($room->hasActiveShowtimes())
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-play me-1"></i>Có suất chiếu
                                                    </span>
                                                @endif

                                                <!-- Thời gian còn lại -->
                                                <div class="time-until mt-1">
                                                    <small class="text-info">
                                                        <i class="fas fa-hourglass-half me-1"></i>
                                                        {{ $nextShowtime->start_time->diffForHumans() }}
                                                    </small>
                                                </div>
                                            </div>
                                        @else
                                            <!-- Khi không có suất chiếu -->
                                            <div class="text-center py-2">
                                                <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                                                <div class="text-muted">
                                                    <strong>Không có suất chiếu</strong>
                                                </div>
                                                <small class="text-muted">Chưa lên lịch chiếu</small>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($showDeleted)
                                            <span class="text-light">
                                                {{ $room->deleted_at ? $room->deleted_at->format('d/m/Y H:i') : 'N/A' }}
                                            </span>
                                        @else
                                            <span class="text-light">
                                                {{ $room->created_at ? $room->created_at->format('d/m/Y H:i') : 'N/A' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($showDeleted)
                                            <!-- Actions for deleted rooms -->
                                            <div class="d-flex gap-3 justify-content-center">
                                                <button type="button"
                                                        wire:click.once="restoreRoom({{ $room->id }})"
                                                        class="btn btn-sm btn-success"
                                                        title="Khôi phục">
                                                    <i class="fas fa-undo" style="margin-right: 0"></i>
                                                </button>
                                                <button type="button"
                                                        class="btn btn-sm btn-danger"
                                                        wire:sc-model="forceDeleteRoom({{ $room->id }})"
                                                        wire:sc-confirm.warning="Bạn có chắc chắn muốn XÓA VĨNH VIỄN phòng '{{ $room->name }}'? Hành động này KHÔNG THỂ HOÀN TÁC!"
                                                        title="Xóa vĩnh viễn">
                                                    <i class="fas fa-trash-alt" style="margin-right: 0"></i>
                                                </button>
                                            </div>
                                        @else
                                            <!-- Actions for active rooms -->
                                            <div class="d-flex gap-3 justify-content-center">
                                                <a href="{{ route('admin.rooms.detail', $room->id) }}"
                                                   class="btn btn-sm btn-info"
                                                   title="Xem chi tiết">
                                                    <i class="fas fa-eye" style="margin-right: 0"></i>
                                                </a>
                                                @if(!$room->hasActiveShowtimes())
                                                    <a href="{{ route('admin.rooms.edit', $room->id) }}"
                                                       class="btn btn-sm btn-warning"
                                                       title="Chỉnh sửa">
                                                       <i class="fas fa-edit" style="margin-right: 0"></i>
                                                    </a>
                                                @else
                                                    <button type="button"
                                                            class="btn btn-sm btn-warning"
                                                            wire:sc-alert.error="Không thể sửa phòng có suất chiếu đang hoạt động"
                                                            wire:sc-model
                                                            title="Chỉnh sửa">
                                                        <i class="fas fa-edit" style="margin-right: 0"></i>
                                                    </button>
                                                @endif
                                                @if(!$room->hasActiveShowtimes())
                                                    <button type="button"
                                                            class="btn btn-sm btn-danger"
                                                            wire:sc-model="deleteRoom({{ $room->id }})"
                                                            wire:sc-confirm.warning="Bạn có chắc chắn muốn xóa phòng '{{ $room->name }}'?"
                                                            title="Xóa">
                                                        <i class="fas fa-trash" style="margin-right: 0"></i>
                                                    </button>
                                                @else
                                                    <button type="button"
                                                            class="btn btn-sm btn-danger"
                                                            wire:sc-alert.error="Không thể xóa phòng có suất chiếu trong tương lai"
                                                            wire:sc-model
                                                            title="Xóa">
                                                        <i class="fas fa-trash" style="margin-right: 0"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>
                                                @if($showDeleted)
                                                    Không có phòng chiếu nào đã xóa
                                                @else
                                                    Không có phòng chiếu nào
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
                    {{ $rooms->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
