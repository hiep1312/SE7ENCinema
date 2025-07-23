<div class="scRender">
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Thêm suất chiếu mới</h2>
            <a href="{{ route('admin.showtimes.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <!-- Form thông tin suất chiếu -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-dark">
                    <div class="card-header bg-gradient text-light"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="my-1">Thông tin suất chiếu</h5>
                    </div>
                    <div class="card-body bg-dark">
                        <form wire:submit.prevent="createShowtime" novalidate>
                            <div class="row align-items-start mb-2">
                                @if ($movie_id)
                                    <div class="col-md-3 col-xxl-2 col-6 mb-3">
                                        <div class="mt-1 movie-poster w-100" style="aspect-ratio: 4 / 5; height: auto; margin: 0;">
                                            @if($poster = $movies->find($movie_id)?->poster)
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
                                    <div class="mb-2">
                                        <label for="movie_id" class="form-label text-light">Phim chiếu *</label>
                                        <select id="movie_id" wire:model.live.debounce.500ms="movie_id" class="form-select bg-dark text-light border-light @error("movie_id") is-invalid @enderror">
                                            <option value="">{{ $movies->isEmpty() ? "Không có phim chiếu nào đang hoạt động" : "-- Chọn phim chiếu --" }}</option>
                                            @foreach($movies as $movie)
                                                <option value="{{ $movie->id }}">{{ $movie->title }}</option>
                                            @endforeach
                                        </select>
                                        @error("movie_id")
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label for="room_id" class="form-label text-light">Phòng chiếu *</label>
                                        <select id="room_id" wire:model="room_id" class="form-select bg-dark text-light border-light @error("room_id") is-invalid @enderror">
                                            <option value="">{{ $rooms->isEmpty() ? "Không có phòng chiếu nào đang hoạt động" : "-- Chọn phòng chiếu --" }}</option>
                                            @foreach($rooms as $room)
                                                <option value="{{ $room->id }}">{{ $room->name }}</option>
                                            @endforeach
                                        </select>
                                        @error("room_id")
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label for="start_time" class="form-label text-light">Khung giờ chiếu *</label>
                                        <input type="datetime-local"
                                            id = "start_time"
                                            wire:model.blur="start_time"
                                            class="form-control bg-dark text-light border-light @error("start_time") is-invalid @enderror">
                                        @error("start_time")
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label for="end_time" class="form-label text-light">Khung giờ kết thúc *</label>
                                        <input type="datetime-local"
                                            id = "end_time"
                                            class="form-control bg-dark text-light border-light"
                                            readonly value="{{ date("Y-m-d\TH:i", strtotime("+ ". $movies->find($movie_id)?->duration." minutes", strtotime($start_time)) ?: null) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label for="price" class="form-label text-light">Giá khung giờ *</label>
                                        <input type="text"
                                            id = "price"
                                            wire:model="price"
                                            class="form-control bg-dark text-light border-light @error("price") is-invalid @enderror"
                                            placeholder="VD: 20000đ" min="0">
                                        @error("price")
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label for="status" class="form-label text-light">Trạng thái *</label>
                                        <select id="status"
                                            class="form-select bg-dark text-light border-light"
                                            disabled>
                                            <option value="active" selected>Hoạt động</option>
                                            <option value="canceled">Hủy chiếu</option>
                                            {{-- <option value="completed">Đã hoàn thành</option> --}}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @if($movie_id) </div> @endif
                            <div class="d-flex justify-content-between mt-3">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Tạo suất chiếu
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
</div>
