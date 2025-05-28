<div>
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-solid.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/all.css">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2>Quản lý phòng chiếu</h2>
            <div>
                @if(!$showDeleted)
                    <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary me-2">
                        <i class="fas fa-plus me-1"></i>Thêm phòng chiếu
                    </a>
                @endif
                <button wire:click="$toggle('showDeleted')" class="btn btn-outline-secondary">
                    @if($showDeleted)
                        <i class="fas fa-eye me-1"></i>Xem phòng hoạt động
                    @else
                        <i class="fas fa-trash me-1"></i>Xem phòng đã xóa
                    @endif
                </button>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text"
                                   wire:model.live.debounce.300ms="search"
                                   class="form-control"
                                   placeholder="Tìm kiếm phòng chiếu...">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <select wire:model.live="perPage" class="form-select">
                            <option value="5">5 / trang</option>
                            <option value="20">20 / trang</option>
                            <option value="50">50 / trang</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Tên phòng</th>
                                <th>Sức chứa</th>
                                <th>Số ghế hiện tại</th>
                                <th>Trạng thái</th>
                                <th>Suất chiếu tiếp theo</th>
                                @if($showDeleted)
                                    <th>Ngày xóa</th>
                                @else
                                    <th>Ngày tạo</th>
                                @endif
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rooms as $room)
                                <tr class="@if($showDeleted) table-secondary @endif">
                                    <td>{{ $room->id }}</td>
                                    <td>
                                        <strong>{{ $room->name }}</strong>
                                        @if($room->trashed())
                                            <span class="badge bg-danger ms-1">Đã xóa</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $room->capacity }} ghế</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $room->seats_count }} ghế</span>
                                    </td>
                                    <td>
                                        @if(!$showDeleted)
                                            @switch($room->status)
                                                @case('active')
                                                    <span class="badge bg-success">Hoạt động</span>
                                                    @break
                                                @case('maintenance')
                                                    <span class="badge bg-warning">Bảo trì</span>
                                                    @break
                                                @case('inactive')
                                                    <span class="badge bg-danger">Ngừng hoạt động</span>
                                                    @break
                                            @endswitch
                                        @else
                                            <span class="badge bg-secondary">Đã xóa</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$showDeleted && $room->showtimes->count() > 0)
                                            @php $nextShowtime = $room->showtimes->first(); @endphp
                                            <small class="text-primary">
                                                {{ $nextShowtime->start_time->format('d/m/Y H:i') }}
                                            </small>
                                            @if($room->hasActiveShowtimes())
                                                <br><span class="badge bg-success">Có suất chiếu</span>
                                            @endif
                                        @else
                                            <span class="text-muted">Không có</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($showDeleted)
                                            {{ $room->deleted_at->format('d/m/Y H:i') }}
                                        @else
                                            {{ $room->created_at->format('d/m/Y H:i') }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($showDeleted)
                                            <!-- Actions for deleted rooms -->
                                            <div class="btn-group" role="group">
                                                <button type="button"
                                                        wire:click="restoreRoom({{ $room->id }})"
                                                        class="btn btn-sm btn-success d-flex align-items-center"
                                                        title="Khôi phục">
                                                    <i class="fas fa-undo me-1"></i>
                                                    <span>Khôi phục</span>
                                                </button>
                                                <button type="button"
                                                        class="btn btn-sm btn-danger d-flex align-items-center"
                                                        wire:click="forceDeleteRoom({{ $room->id }})"
                                                        wire:confirm="Bạn có chắc chắn muốn XÓA VĨNH VIỄN phòng '{{ $room->name }}'? Hành động này KHÔNG THỂ HOÀN TÁC!"
                                                        title="Xóa vĩnh viễn">
                                                    <i class="fas fa-trash-alt me-1"></i>
                                                    <span>Xóa vĩnh viễn</span>
                                                </button>
                                            </div>
                                        @else
                                            <!-- Actions for active rooms -->
                                            <div class="btn-group" role="group">
                                                @if($room->canEdit())
                                                    <a href="{{ route('admin.rooms.edit', $room->id) }}"
                                                       class="btn btn-sm btn-warning d-flex align-items-center"
                                                       title="Chỉnh sửa">
                                                        <i class="fas fa-edit me-1"></i>
                                                        <span>Sửa</span>
                                                    </a>
                                                @else
                                                    <button type="button"
                                                            class="btn btn-sm btn-warning disabled d-flex align-items-center"
                                                            title="Không thể sửa - có suất chiếu đang hoạt động"
                                                            disabled>
                                                        <i class="fas fa-edit me-1"></i>
                                                        <span>Sửa</span>
                                                    </button>
                                                @endif
                                                @if($room->canDelete())
                                                    <button type="button"
                                                            class="btn btn-sm btn-danger d-flex align-items-center"
                                                            wire:click="deleteRoom({{ $room->id }})"
                                                            wire:confirm="Bạn có chắc chắn muốn xóa phòng '{{ $room->name }}'?"
                                                            title="Xóa">
                                                        <i class="fas fa-trash me-1"></i>
                                                        <span>Xóa</span>
                                                    </button>
                                                @else
                                                    <button type="button"
                                                            class="btn btn-sm btn-danger disabled d-flex align-items-center"
                                                            title="Không thể xóa - có suất chiếu trong tương lai"
                                                            disabled>
                                                        <i class="fas fa-trash me-1"></i>
                                                        <span>Xóa</span>
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

                {{ $rooms->links() }}
            </div>
        </div>
    </div>

    <style>
        /* Đảm bảo các nút có thể click được */
        .btn-group .btn {
            pointer-events: auto;
            cursor: pointer;
        }

        .btn-group .btn.disabled {
            pointer-events: none;
            cursor: not-allowed;
        }

        .btn i {
            pointer-events: none;
        }

        .btn span {
            pointer-events: none;
        }

        /* Cải thiện hiển thị nút */
        .btn-group .btn {
            white-space: nowrap;
            min-width: auto;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
</div>
