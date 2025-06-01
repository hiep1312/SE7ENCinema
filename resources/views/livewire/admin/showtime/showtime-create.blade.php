<div class="container-fluid">
    <div class="row">
        <div class="col-md-9" style="min-height: 80vh; width: 100%;">
            <h2 class="mb-4">Tạo Suất Chiếu</h2>

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
                    <form wire:submit.prevent="createShowtime">
                        <div class="mb-3">
                            <label for="movie" class="form-label">Phim <span class="text-danger">*</span></label>
                            <select wire:model.live="selectedMovie" class="form-select" id="movie">
                                <option value="">Chọn phim</option>
                                @foreach($movies as $movie)
                                    <option value="{{ $movie->id }}">{{ $movie->title }} ({{ $movie->format }}) - {{ $movie->status == 'showing' ? 'Đang chiếu' : 'Sắp chiếu' }}</option>
                                @endforeach
                            </select>
                            @error('selectedMovie')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="room" class="form-label">Phòng chiếu <span class="text-danger">*</span></label>
                            <select wire:model="selectedRoom" class="form-select" id="room">
                                <option value="">Chọn phòng</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}">{{ $room->name }} ({{ $room->capacity }} chỗ)</option>
                                @endforeach
                            </select>
                            @error('selectedRoom')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="startTime" class="form-label">Thời gian bắt đầu <span class="text-danger">*</span></label>
                            <input type="datetime-local" wire:model.live="startTime" class="form-control" id="startTime">
                            @error('startTime')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                            <div class="form-text text-muted">
                                Suất chiếu chỉ được tạo trong khung giờ 8:00 - 23:00 và trước ít nhất 1 tiếng.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Giá vé (VNĐ)</label>
                            <input type="number" wire:model="price" class="form-control" id="price" placeholder="Nhập giá hoặc để trống" min="0">
                            @error('price')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Ảnh phim động theo phim đã chọn --}}
                        <div class="mb-3">
                            <label class="form-label">Ảnh phim</label>
                            @php
                                $poster = 'https://png.pngtree.com/png-clipart/20190920/original/pngtree-404-robot-mechanical-vector-png-image_4627839.jpg';
                                $movieTitle = 'Chưa chọn phim';
                                foreach($movies as $movie) {
                                    if ($movie->id == $selectedMovie) {
                                        $poster = $movie->poster ?: $poster;
                                        $movieTitle = $movie->title;
                                        break;
                                    }
                                }
                            @endphp
                            <div class="text-left">
                                <img src="{{ $poster }}"
                                     class="img-thumbnail shadow-sm"
                                     style="width: 200px; height: 300px; object-fit: cover;"
                                     alt="Poster phim: {{ $movieTitle }}">
                                <div class="mt-2 text-muted small">{{ $movieTitle }}</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.manage.showtimes') }}" class="btn btn-secondary">Quay lại</a>
                            <button type="submit" class="btn btn-primary">
                                <span wire:loading.remove>Tạo suất chiếu</span>
                                <span wire:loading>Đang xử lý...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
