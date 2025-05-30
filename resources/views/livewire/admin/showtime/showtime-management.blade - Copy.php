<div class="container-fluid" wire:poll.15s="refreshData">
    <div wire:loading.delay wire:target="refreshData" class="position-fixed top-0 end-0 m-3">
    <div class="alert alert-info">
        <i class="fa fa-sync fa-spin"></i> Đang cập nhật...
    </div>
</div>
    <div class="row">
        @include('livewire.admin.components.sidebar')
        <div class="col-md-9" style="min-height: 100vh; width: 80%;">
            @include('livewire.admin.components.header')
            <h2 class="mb-4">Quản lý suất chiếu</h2>

            @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Lọc suất chiếu</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="dateFilter" class="form-label">Ngày chiếu</label>
                            <div class="input-group">
                                <input type="date" wire:model="selectedDate" class="form-control" id="dateFilter" value="{{ now()->format('Y-m-d') }}">
                                <button type="button" class="btn btn-primary" wire:click="filterByDate">Lọc</button>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="searchMovie" class="form-label">Tìm phim</label>
                            <input type="text" wire:model.live="searchMovie" class="form-control" id="searchMovie" placeholder="Nhập tên phim">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="searchFormat" class="form-label">Định dạng</label>
                            <select wire:model.live="searchFormat" class="form-select" id="searchFormat">
                                <option value="">Tất cả</option>
                                <option value="2D">2D</option>
                                <option value="3D">3D</option>
                                <option value="4DX">4DX</option>
                                <option value="IMAX">IMAX</option>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-outline-danger btn-sm" wire:click="resetAllFilters">
                        <i class="fa-solid fa-eraser"></i> Reset bộ lọc
                    </button>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mt-3">Danh sách suất chiếu</h5>
                        <button class="btn text-success" wire:click="openCreateModal"><i class="fa-solid fa-square-plus" style="font-size: 40px;"></i></button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover text-center align-middle">
                            <thead>
                                <tr>
                                    <th>Phim</th>
                                    <th>Phòng</th>
                                    <th>Ảnh phim</th>
                                    <th>Giờ chiếu</th>
                                    <th>Thời lượng</th>
                                    <th>Bắt đầu</th>
                                    <th>Kết thúc</th>
                                    <th>Trạng thái</th>
                                    <th>Giá vé</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                    @foreach($showtimes as $showtime)
                                    @php
                                        $displayData = $this->getShowtimeDisplayData($showtime);
                                        $realTimeStatus = $displayData['realTimeStatus'];
                                        $status = $displayData['status'];
                                        $timeRemaining = $displayData['timeRemaining'];
                                        $canEdit = $displayData['canEdit'];
                                        $canDeleteResult = $displayData['canDeleteResult'];
                                    @endphp

                                    <tr>
                                        <td>{{ $showtime->movie->title ?? 'N/A' }}</td>
                                        <td>{{ $showtime->room->name ?? 'N/A' }}</td>
                                        <td>
                                                @if($showtime->movie && $showtime->movie->poster)
                                                    {{-- <img src="{{ $showtime->movie->poster }}"
                                                         class="img-thumbnail"
                                                         width="50" height="75"
                                                         alt="{{ $showtime->movie->title }}"
                                                         loading="lazy"
                                                         style="object-fit: cover;"> --}}
                                                          <img src="https://png.pngtree.com/png-clipart/20190920/original/pngtree-404-robot-mechanical-vector-png-image_4627839.jpg"
                                                         class="img-thumbnail"
                                                         width="50" height="75"
                                                         alt="No image"
                                                         loading="lazy"
                                                         style="object-fit: cover;">
                                                @else
                                                    <img src="https://png.pngtree.com/png-clipart/20190920/original/pngtree-404-robot-mechanical-vector-png-image_4627839.jpg"
                                                         class="img-thumbnail"
                                                         width="50" height="75"
                                                         alt="No image"
                                                         loading="lazy"
                                                         style="object-fit: cover;">
                                                @endif
                                            </td>
                                        <td>{{ $showtime->start_time->format('H:i') }}</td>
                                        <td>
                                                <span class="badge bg-warning text-dark">
                                                    {{ $showtime->movie->duration ?? 'N/A' }} phút
                                                </span>
                                        </td>
                                        {{-- <td>{{ $showtime->duration }} phут</td> --}}
                                        <td>{{ $showtime->start_time->format('d/m/Y H:i') }}</td>
                                        <td>{{ $showtime->end_time->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <span class="badge {{ $status['class'] }}">{{ $status['text'] }}</span>

                                            @if($timeRemaining)
                                                @if($timeRemaining['type'] === 'until_start')
                                                    <small class="text-muted d-block">({{ $timeRemaining['text'] }})</small>
                                                @elseif($timeRemaining['type'] === 'showing')
                                                    <small class="text-success d-block">({{ $timeRemaining['text'] }})</small>
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{ number_format($showtime->price) }} VNĐ</td>
                                        <td>
                                        @php
                                            $canEdit = $this->canEditShowtime($showtime);
                                            $canDelete = $this->canDeleteShowtime($showtime->id);
                                        @endphp

                                        <div class="btn-group" role="group">
                                            @if($canEdit['can_edit'])
                                                {{-- <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#showtimeModal" title="Chỉnh sửa suất chiếu">
                                                    <i class="fas fa-edit"></i> Sửa
                                                </button> --}}
                                               <button type="button" class="btn btn-primary" onclick="openShowtimeModal()">
                                                    Launch static backdrop modal
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-secondary" disabled title="{{ $canEdit['message'] }}">
                                                    <i class="fas fa-edit"></i> Sửa
                                                </button>
                                            @endif

                                            @if($canDelete['success'])
                                                <button class="btn btn-sm btn-danger" wire:click="openDeleteModal({{ $showtime->id }})" title="Xóa suất chiếu">
                                                    <i class="fas fa-trash"></i> Xóa
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-secondary" disabled title="{{ $canDelete['message'] }}">
                                                    <i class="fas fa-trash"></i> Xóa
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                    </tr>
                                @endforeach
                                </tbody>
                        </table>
                    </div>
                    {{ $showtimes->links() }}
                </div>
            </div>

            <div class="modal fade" id="showtimeModal" tabindex="-1" aria-labelledby="showtimeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <h1>fUCK</h1>
                {{-- <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="showtimeModalLabel">
                            {{ $editShowtimeId ? 'Chỉnh sửa Suất Chiếu' : 'Tạo Suất Chiếu' }}
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="{{ $editShowtimeId ? 'updateShowtime' : 'createShowtime' }}">
                            <div class="mb-3">
                                <label for="movie" class="form-label">Phim <span class="text-danger">*</span></label>
                                <select wire:model="{{ $editShowtimeId ? 'editMovie' : 'selectedMovie' }}" class="form-select" id="movie">
                                    <option value="">Chọn phim</option>
                                    @foreach($movies as $movie)
                                        <option value="{{ $movie->id }}">{{ $movie->title }} ({{ $movie->format }}) - {{ $movie->status == 'showing' ? 'Đang chiếu' : 'Sắp chiếu' }}</option>
                                    @endforeach
                                </select>
                                @error($editShowtimeId ? 'editMovie' : 'selectedMovie')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="room" class="form-label">Phòng chiếu <span class="text-danger">*</span></label>
                                <select wire:model="{{ $editShowtimeId ? 'editRoom' : 'selectedRoom' }}" class="form-select" id="room">
                                    <option value="">Chọn phòng</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}">{{ $room->name }} ({{ $room->capacity }} chỗ)</option>
                                    @endforeach
                                </select>
                                @error($editShowtimeId ? 'editRoom' : 'selectedRoom')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="startTime" class="form-label">Thời gian bắt đầu <span class="text-danger">*</span></label>
                                <input type="datetime-local" wire:model="{{ $editShowtimeId ? 'editStartTime' : 'startTime' }}" class="form-control" id="startTime">
                                @error($editShowtimeId ? 'editStartTime' : 'startTime')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                                <div class="form-text text-muted">
                                    Suất chiếu chỉ được tạo trong khung giờ 8:00 - 23:00 và trước ít nhất 1 tiếng.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Giá vé (VNĐ)</label>
                                <input type="number" wire:model="{{ $editShowtimeId ? 'editPrice' : 'price' }}" class="form-control" id="price" placeholder="Nhập giá hoặc để trống" min="0">
                                @error($editShowtimeId ? 'editPrice' : 'price')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="closeModal">Hủy</button>
                                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                    <span wire:loading.remove>
                                        {{ $editShowtimeId ? 'Cập nhật' : 'Tạo suất chiếu' }}
                                    </span>
                                    <span wire:loading>
                                        Đang xử lý...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div> --}}
            </div>
        </div>
        <div class="modal fade" id="showtimeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Understood</button>
            </div>
        </div>
    </div>
</div>


            <div class="modal fade show" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" style="display: none;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Xác nhận xóa</h5>
                            <button type="button" class="btn-close" wire:click="closeDeleteModal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center">
                                <h5>Bạn có chắc chắn muốn xóa suất chiếu này?</h5>
                                <p class="text-muted">Hành động này không thể hoàn tác!</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeDeleteModal">Hủy</button>
                            <button type="button" class="btn btn-danger" wire:click="confirmDeleteShowtime" wire:loading.attr="disabled">
                                <span wire:loading.remove > <span wire:sc-Alert.error="Xóa thành công!!!">Xóa</span></span>
                                <span wire:loading>Đang loading...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('livewire.admin.components.footer')

    <style>
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            width: 100vw;
            height: 100vh;
            background-color: #000;
            opacity: 0.5;
        }

        .modal {
            z-index: 1050;
        }

        .badge {
            font-size: 0.8rem;
            padding: 0.4em 0.8em;
        }

        .table-secondary {
            background-color: rgba(108, 117, 125, 0.1) !important;
        }

        .alert {
            border-radius: 0.5rem;
        }

        .alert-success {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .table th, .table td {
            vertical-align: middle;
            padding: 12px;
        }

        .card {
            border-radius: 0.5rem;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .badge-primary {
            background-color: #007bff !important;
        }

        .badge-success {
            background-color: #28a745 !important;
        }

        .badge-danger {
            background-color: #dc3545 !important;
        }

        .mb-3 {
            margin-bottom: 1rem !important;
        }

        .mb-4 {
            margin-bottom: 1.5rem !important;
        }
    </style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <script>

    function openShowtimeModal() {
        const modal = new bootstrap.Modal(document.getElementById('showtimeModal'), {
            keyboard: true,
            backdrop: 'static'
        });
        modal.show();
    }
    </script>
</div>
