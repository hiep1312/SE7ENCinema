<div class="container-fluid">
    <div class="row">
        @include('livewire.admin.components.sidebar')
        <div class="col-md-9" style="min-height: 100vh; width: 80%;">
            @include('livewire.admin.components.header')
            <h2 class="mb-4">Chỉnh sửa Suất Chiếu</h2>

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
                    <form wire:submit.prevent="updateShowtime">
                        <div class="mb-3">
                            <label for="movie" class="form-label">Phim <span class="text-danger">*</span></label>
                            <select wire:model="editMovie" class="form-select" id="movie">
                                <option value="">Chọn phim</option>
                                @foreach($movies as $movie)
                                    <option value="{{ $movie->id }}">{{ $movie->title }} ({{ $movie->format }}) - {{ $movie->status == 'showing' ? 'Đang chiếu' : 'Sắp chiếu' }}</option>
                                @endforeach
                            </select>
                            @error('editMovie')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="room" class="form-label">Phòng chiếu <span class="text-danger">*</span></label>
                            <select wire:model="editRoom" class="form-select" id="room">
                                <option value="">Chọn phòng</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}">{{ $room->name }} ({{ $room->capacity }} chỗ)</option>
                                @endforeach
                            </select>
                            @error('editRoom')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="startTime" class="form-label">Thời gian bắt đầu <span class="text-danger">*</span></label>
                            <input type="datetime-local" wire:model="editStartTime" class="form-control" id="startTime">
                            @error('editStartTime')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                            <div class="form-text text-muted">
                                Suất chiếu chỉ được tạo trong khung giờ 8:00 - 23:00 và trước ít nhất 1 tiếng.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Giá vé (VNĐ)</label>
                            <input type="number" wire:model="editPrice" class="form-control" id="price" placeholder="Nhập giá hoặc để trống" min="0">
                            @error('editPrice')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('manage.showtimes') }}" class="btn btn-secondary">Quay lại</a>
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove>Cập nhật</span>
                                <span wire:loading>Đang xử lý...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('livewire.admin.components.footer')
</div>
