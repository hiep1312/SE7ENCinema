<div class="container-fluid" wire:poll.15s="refreshData">
    <div wire:loading.delay wire:target="refreshData" class="position-fixed top-0 end-0 m-3">
        <div class="alert alert-info">
            <i class="fa fa-sync fa-spin"></i> Đang cập nhật...
        </div>
    </div>
    <div class="row">
        <div class="col-md-9" style="min-height: 50vh; width: 100%;">
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
                            <input type="text" wire:model.live="searchMovie" class="form-control" id="searchMovie" placeholder="Nhập tên phim" value="{{ $searchMovie }}" autocomplete="on">
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
                        <a href="{{ route('admin.showtime.create') }}" class="btn text-success">
                            <i class="fa-solid fa-square-plus" style="font-size: 40px;"></i>
                        </a>
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
                                                <img src="{{ $showtime->movie->poster }}"
                                                     class="img-thumbnail"
                                                     width="150" height="225"
                                                     alt="No image"
                                                     >
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
                                                $canDelete = $this->canDeleteShowtime($showtime->id);
                                            @endphp
                                            <div class="btn-group" role="group">
                                                @if($this->canEditShowtime($showtime))
                                                    <a href="{{ route('admin.showtime.update', $showtime->id) }}" class="btn btn-sm btn-warning" title="Chỉnh sửa suất chiếu">
                                                        <i class="fas fa-edit"></i> Sửa
                                                    </a>
                                                @else
                                                    <button class="btn btn-sm btn-secondary" disabled title="Suất chiếu đang diễn ra hoặc đã hoàn thành!">
                                                        <i class="fas fa-edit"></i> Sửa
                                                    </button>
                                                @endif
                                                @if($canDelete['success'])
                                                    <button class="btn btn-sm btn-danger"
                                                            wire:sc-confirm.warning.close="Bạn có chắc chắn muốn xóa suất chiếu này?"
                                                            wire:sc-title="Xác nhận xóa suất chiếu"
                                                            wire:sc-html="<div class='text-center'><strong>Phim:</strong> {{ addslashes($showtime->movie->title ?? 'N/A') }}<br><strong>Thời gian:</strong> {{ $showtime->start_time->format('d/m/Y H:i') }}<br><p class='text-danger mt-2'>Hành động này không thể hoàn tác!</p></div>"
                                                            wire:sc-model="deleteShowtime({{ $showtime->id }})"
                                                            title="Xóa suất chiếu">
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
        </div>
    </div>
</div>
