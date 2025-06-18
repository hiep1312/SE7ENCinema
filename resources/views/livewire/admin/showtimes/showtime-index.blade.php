<div class="container-fluid" wire:poll.15s="refreshData">
    <div wire:loading.delay wire:target="refreshData" class="position-fixed top-0 end-0 m-3">
        <div class="alert alert-info">
            <i class="fa fa-sync fa-spin"></i> Đang cập nhật...
        </div>
    </div>
    <div class="row">
        <div class="col-md-9" style="min-height: 50vh; width: 100%;">
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

            <div class="container-lg mb-4">
                <div class="d-flex justify-content-between align-items-center my-3">
                    <h2 class="text-light">Quản lý suất chiếu</h2>
                    <div>
                        <a href="{{ route('admin.showtimes.create') }}" class="btn btn-success me-2">
                            <i class="fas fa-plus me-1"></i>Thêm suất chiếu
                        </a>
                    </div>
                </div>

                <div class="card bg-dark">
                    <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="row g-3">
                            <!-- Tìm kiếm -->
                            <div class="col-md-4 col-lg-3">
                                <div class="input-group">
                                    <input type="text" wire:model.live.debounce.300ms="searchMovie"
                                        class="form-control bg-dark text-light" placeholder="Tìm kiếm phim...">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                </div>
                            </div>

                            <!-- Lọc theo định dạng -->
                            <div class="col-md-3 col-lg-2">
                                <select wire:model.live="searchFormat" class="form-select bg-dark text-light">
                                    <option value="">Tất cả định dạng</option>
                                    <option value="2D">2D</option>
                                    <option value="3D">3D</option>
                                    <option value="4DX">4DX</option>
                                    <option value="IMAX">IMAX</option>
                                </select>
                            </div>

                            <!-- Lọc theo ngày -->
                            <div class="col-md-3 col-lg-2">
                                <div class="input-group">
                                    <input type="date" wire:model="selectedDate" class="form-control bg-dark text-light" id="dateFilter">
                                    <button type="button" class="btn btn-primary" wire:click="filterByDate">Lọc</button>
                                </div>
                            </div>

                            <!-- Reset filters -->
                            <div class="col-md-2">
                                <button wire:click="resetAllFilters" class="btn btn-outline-warning">
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
                                        <th class="text-center text-light">Phim</th>
                                        <th class="text-center text-light">Phòng</th>
                                        <th class="text-center text-light">Ảnh phim</th>
                                        <th class="text-center text-light">Giờ chiếu</th>
                                        <th class="text-center text-light">Thời lượng</th>
                                        <th class="text-center text-light">Bắt đầu</th>
                                        <th class="text-center text-light">Kết thúc</th>
                                        <th class="text-center text-light">Trạng thái</th>
                                        <th class="text-center text-light">Giá vé</th>
                                        <th class="text-center text-light">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($showtimes as $showtime)
                                        @php
                                            $displayData = $this->getShowtimeDisplayData($showtime);
                                            $realTimeStatus = $displayData['realTimeStatus'];
                                            $status = $displayData['status'];
                                            $timeRemaining = $displayData['timeRemaining'];
                                            $canEdit = $displayData['canEdit'];
                                            $canDeleteResult = $displayData['canDeleteResult'];
                                        @endphp
                                        <tr wire:key="{{ $showtime->id }}">
                                            <td class="py-5">
                                                <strong class="text-light">{{ $showtime->movie->title ?? 'N/A' }}</strong>
                                            </td>
                                            <td class="text-center">{{ $showtime->room->name ?? 'N/A' }}</td>
                                            <td>
                                                <div class="mt-1 overflow-auto d-block text-center"
                                                    style="max-height: 100px; width: 100px;">
                                                    @if($showtime->movie && $showtime->movie->poster)
                                                        <img src="{{ $showtime->movie->poster }}"
                                                             class="rounded"
                                                             style="width: 100%; height: auto;"
                                                             alt="Poster phim">
                                                    @else
                                                        <img src="https://png.pngtree.com/png-clipart/20190920/original/pngtree-404-robot-mechanical-vector-png-image_4627839.jpg"
                                                             class="rounded"
                                                             style="width: 100%; height: auto;"
                                                             alt="No image"
                                                             loading="lazy">
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $showtime->start_time->format('H:i') }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-warning text-dark">
                                                    {{ $showtime->movie->duration ?? 'N/A' }} phút
                                                </span>
                                            </td>
                                            <td class="text-center">{{ $showtime->start_time->format('d/m/Y H:i') }}</td>
                                            <td class="text-center">{{ $showtime->end_time->format('d/m/Y H:i') }}</td>
                                            <td class="text-center">
                                                <span class="badge {{ $status['class'] }}">{{ $status['text'] }}</span>
                                                @if($timeRemaining)
                                                    @if($timeRemaining['type'] === 'until_start')
                                                        <small class="text-muted d-block">({{ $timeRemaining['text'] }})</small>
                                                    @elseif($timeRemaining['type'] === 'showing')
                                                        <small class="text-success d-block">({{ $timeRemaining['text'] }})</small>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-center">{{ number_format($showtime->price) }} VNĐ</td>
                                            <td>
                                                <div class="d-flex gap-3 justify-content-center">
                                                    @if($this->canEditShowtime($showtime))
                                                        <a href="{{ route('admin.showtimes.edit', $showtime->id) }}"
                                                            class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                                            <i class="fas fa-edit" style="margin-right: 0"></i>
                                                        </a>
                                                    @else
                                                        <button class="btn btn-sm btn-secondary" disabled title="Suất chiếu đang diễn ra hoặc đã hoàn thành!">
                                                            <i class="fas fa-edit" style="margin-right: 0"></i>
                                                        </button>
                                                    @endif
                                                    @if($canDeleteResult['success'])
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            wire:sc-model="deleteShowtime({{ $showtime->id }})"
                                                            wire:sc-confirm.warning="Bạn có chắc chắn muốn xóa suất chiếu này?"
                                                            wire:sc-title="Xác nhận xóa suất chiếu"
                                                            wire:sc-html="<div class='text-center'><strong>Phim:</strong> {{ addslashes($showtime->movie->title ?? 'N/A') }}<br><strong>Thời gian:</strong> {{ $showtime->start_time->format('d/m/Y H:i') }}<br><p class='text-danger mt-2'>Hành động này không thể hoàn tác!</p></div>"
                                                            title="Xóa">
                                                            <i class="fas fa-trash" style="margin-right: 0"></i>
                                                        </button>
                                                    @else
                                                        <button class="btn btn-sm btn-secondary" disabled title="{{ $canDeleteResult['message'] }}">
                                                            <i class="fas fa-trash" style="margin-right: 0"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-film fa-3x mb-3"></i>
                                                    <p>Không có suất chiếu nào</p>
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
        </div>
    </div>
</div>
