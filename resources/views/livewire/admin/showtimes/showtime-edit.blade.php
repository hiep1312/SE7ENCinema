<div class="scRender">
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Chỉnh sửa suất chiếu: {{ $showtimeItem->start_time->format('d/m/Y H:i') }} - {{ $showtimeItem->end_time->format('H:i') }}</h2>
            <a href="{{ route('admin.showtimes.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <!-- Form thông tin suất chiếu -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-dark">
                    <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="my-1">Thông tin suất chiếu</h5>
                    </div>
                    <div class="card-body bg-dark">
                        <form wire:submit.prevent="updateShowtime" novalidate>
                            <div class="row align-items-start mb-2">
                                @if ($showtimeItem->movie->exists())
                                    <div class="col-md-3 col-xxl-2 col-5 mb-3">
                                        <div class="mt-1 movie-poster w-100" style="aspect-ratio: 4 / 5; height: auto; margin: 0;">
                                            @if($poster = $showtimeItem->movie->poster)
                                                <img src="{{ asset('storage/' . $poster) }}" alt="Ảnh phim"
                                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 0;">
                                            @else
                                                <i class="fas fa-film" style="font-size: 32px;"></i>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-9 col-xxl-10 row">
                                @endif
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="movie" class="form-label text-light">Phim chiếu *</label>
                                        <div class="input-group">
                                            <input type="text" id = "movie" value="{{ $showtimeItem->movie?->title ?? '' }}"
                                                class="form-control bg-dark text-light border-light"
                                                placeholder="Chưa chọn phim chiếu" disabled>
                                            <button type="button" class="btn btn-outline-warning" disabled>Chọn</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="room" class="form-label text-light">Phòng chiếu *</label>
                                        <div class="input-group">
                                            <input type="text" id = "room" value="{{ $rooms->firstWhere('id', $room_id)?->name ?? '' }}"
                                                class="form-control bg-dark text-light border-light @error('room_id') is-invalid @enderror"
                                                placeholder="Chưa chọn phòng chiếu" disabled>
                                            <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#frameSelectionData" wire:click="$set('modalType', 'room')">Chọn</button>
                                        </div>
                                        @error("room_id")
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="start_time" class="form-label text-light">Khung giờ chiếu *</label>
                                        <input type="datetime-local"
                                            id = "start_time"
                                            wire:model.live.debounce.500ms="start_time"
                                            class="form-control bg-dark text-light border-light @error("start_time") is-invalid @enderror">
                                        @error("start_time")
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="end_time" class="form-label text-light">Khung giờ kết thúc *</label>
                                        <input type="datetime-local"
                                            id = "end_time"
                                            class="form-control bg-dark text-light border-light"
                                            readonly value="{{ date("Y-m-d\TH:i", strtotime("+ ". $showtimeItem->movie?->duration." minutes", strtotime($start_time)) ?: null) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="status" class="form-label text-light">Trạng thái *</label>
                                        <select id="status" wire:model="status"
                                            class="form-select bg-dark text-light border-light @error("status") is-invalid @enderror">
                                            <option value="active">Hoạt động</option>
                                            <option value="canceled">Hủy chiếu</option>
                                        </select>
                                        @error("status")
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            @if($showtimeItem->movie->poster) </div> @endif
                            <div class="d-flex justify-content-between mt-3">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Cập nhật thông tin
                                </button>
                                <a href="{{ route('admin.showtimes.index') }}" class="btn btn-outline-danger">
                                    Hủy bỏ
                                </a>
                            </div>
                        </form>
                        <div class="w-100 mt-4">
                            <div class="card bg-dark border-light">
                                <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <h5><i class="fas fa-calendar-alt me-2"></i>Các suất chiếu cùng loại</h5>
                                </div>
                                <div class="card-body bg-dark" style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                    <div class="table-responsive">
                                        <table class="table table-dark table-striped table-hover text-light border">
                                            <thead>
                                                <tr>
                                                    <th class="text-center text-light">Phòng chiếu</th>
                                                    <th class="text-center text-light">Khung giờ chiếu</th>
                                                    <th class="text-center text-light">Giá khung giờ</th>
                                                    <th class="text-center text-light">Trạng thái</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($relatedShowtimes ?? [] as $showtime)
                                                    <tr wire:key="{{ $showtime->id }}">
                                                        <td class="text-center">
                                                            <strong class="text-light">{{ $showtime->room->name }}</strong>
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
                                                        <td class="text-center text-warning">
                                                            {{ number_format($showtime->price, 0, ',', '.') }}đ
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
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center py-4">
                                                            <div class="text-muted">
                                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                                <p>Không có suất chiếu cùng loại</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="frameSelectionData" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @switch($modalType)
                            @case('movie') Chọn phim chiếu @break
                            @case('room') Chọn phòng chiếu @break
                            @default Đang tải...
                        @endswitch
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($modalType)
                        @php
                            $modalText = $modalType === 'movie' ? 'phim' : 'phòng';
                            $fieldName = $this->modalType === "movie" ? "title" : "name";
                        @endphp
                        <div class="search-box">
                            <input type="text" wire:model.live.debounce.300ms="searchModal" placeholder="Tìm kiếm {{ $modalText }}..." autocomplete="off">
                            <div class="search-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <path d="m21 21-4.35-4.35"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="checkbox-container" style="margin-bottom: 0;">
                            <div class="checkbox-list" id="checkboxListModal">
                                @forelse($modalData as $modalDataItem)
                                    <div class="checkbox-item" onclick="this.querySelector('input[type=radio]').click()" wire:key="{{ $fieldName . "-" . $modalDataItem->id }}">
                                        <div class="checkbox-wrapper">
                                            <input type="radio" name="{{ $modalType }}" wire:model.live="modalSelected" value="{{ $modalDataItem->id }}">
                                            <span class="checkmark"></span>
                                        </div>
                                        <label class="checkbox-label">{{ $modalDataItem->{$fieldName} }}</label>
                                    </div>
                                @empty
                                    <div class="empty-state">Không tìm thấy {{ $modalText }} nào</div>
                                @endforelse
                            </div>
                        </div>
                    @else Đang tải nội dung...
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" wire:click="setData">Cập nhật</button>
                </div>
            </div>
        </div>
    </div>
</div>
@script
<script>
    document.getElementById('frameSelectionData').addEventListener('hidden.bs.modal', $wire.resetModal);
</script>
@endscript
