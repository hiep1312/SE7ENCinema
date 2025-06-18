<div>
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
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
            <h2 class="text-light">Tạo suất chiếu mới</h2>
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
                        <form wire:submit.prevent="createShowtime">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="movie" class="form-label text-light">Phim <span class="text-danger">*</span></label>
                                                <select wire:model.live="selectedMovie" class="form-select bg-dark text-light border-light @error('selectedMovie') is-invalid @enderror" id="movie">
                                                    <option value="">Chọn phim</option>
                                                    @foreach($movies as $movie)
                                                        <option value="{{ $movie->id }}">{{ $movie->title }} ({{ $movie->format }}) - {{ $movie->status == 'showing' ? 'Đang chiếu' : 'Sắp chiếu' }}</option>
                                                    @endforeach
                                                </select>
                                                @error('selectedMovie')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="room" class="form-label text-light">Phòng chiếu <span class="text-danger">*</span></label>
                                                <select wire:model="selectedRoom" class="form-select bg-dark text-light border-light @error('selectedRoom') is-invalid @enderror" id="room">
                                                    <option value="">Chọn phòng</option>
                                                    @foreach($rooms as $room)
                                                        <option value="{{ $room->id }}">{{ $room->name }} ({{ $room->capacity }} chỗ)</option>
                                                    @endforeach
                                                </select>
                                                @error('selectedRoom')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="startTime" class="form-label text-light">Thời gian bắt đầu <span class="text-danger">*</span></label>
                                                <input type="datetime-local" wire:model.live="startTime" class="form-control bg-dark text-light border-light @error('startTime') is-invalid @enderror" id="startTime">
                                                @error('startTime')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text text-muted">
                                                    Suất chiếu chỉ được tạo trước ít nhất 1 tiếng.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="price" class="form-label text-light">Giá vé (VNĐ)</label>
                                                <input type="number" wire:model="price" class="form-control bg-dark text-light border-light @error('price') is-invalid @enderror" id="price" placeholder="Nhập giá hoặc để trống" min="0">
                                                @error('price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label text-light">Ảnh phim</label>
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
                                        <div class="text-center">
                                            <img src="{{ $poster }}"
                                                 class="img-thumbnail shadow-sm"
                                                 style="width: 100%; height: auto; max-height: 300px; object-fit: cover;"
                                                 alt="Poster phim: {{ $movieTitle }}">
                                            <div class="mt-2 text-muted small">{{ $movieTitle }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Tạo suất chiếu
                                </button>
                                <a href="{{ route('admin.showtimes.index') }}" class="btn btn-outline-danger">
                                    Hủy bỏ
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
