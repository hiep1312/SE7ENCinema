<div class="container mt-4">
    <h1>Thêm phim mới</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="store" novalidate enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
            <input wire:model="title" type="text" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="Nhập tiêu đề phim" autocomplete="off">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea wire:model="description" class="form-control @error('description') is-invalid @enderror" id="description" rows="5" placeholder="Nhập mô tả chi tiết về phim (tối đa 1000 ký tự)"></textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="duration" class="form-label">Thời lượng (phút) <span class="text-danger">*</span></label>
            <input wire:model="duration" type="number" class="form-control @error('duration') is-invalid @enderror" id="duration" min="1" max="300" placeholder="Nhập thời lượng (1-300 phút)" autocomplete="off">
            @error('duration')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="release_date" class="form-label">Ngày phát hành <span class="text-danger">*</span></label>
            <input wire:model="release_date" type="date" class="form-control @error('release_date') is-invalid @enderror" id="release_date" min="{{ now()->format('Y-m-d') }}">
            @error('release_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="end_date" class="form-label">Ngày kết thúc</label>
            <input wire:model="end_date" type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date">
            <small class="form-text text-muted">Ngày kết thúc phải sau hoặc bằng ngày phát hành.</small>
            @error('end_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="director" class="form-label">Đạo diễn</label>
            <input wire:model="director" type="text" class="form-control @error('director') is-invalid @enderror" id="director" placeholder="Nhập tên đạo diễn">
            @error('director')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="actors" class="form-label">Diễn viên</label>
            <textarea wire:model="actors" class="form-control @error('actors') is-invalid @enderror" id="actors" rows="3" placeholder="Nhập danh sách diễn viên, cách nhau bằng dấu phẩy"></textarea>
            @error('actors')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="age_restriction" class="form-label">Độ tuổi hạn chế <span class="text-danger">*</span></label>
            <select wire:model="age_restriction" class="form-select @error('age_restriction') is-invalid @enderror" id="age_restriction">
                <option value="">-- Chọn độ tuổi hạn chế --</option>
                <option value="P">Tất cả (P)</option>
                <option value="K">K</option>
                <option value="T13">T13</option>
                <option value="T16">T16</option>
                <option value="T18">T18</option>
                <option value="C">Cấm (C)</option>
            </select>
            @error('age_restriction')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="poster" class="form-label">Poster</label>
            <input wire:model="poster" type="file" class="form-control @error('poster') is-invalid @enderror" id="poster" accept="image/*">
            @error('poster')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="trailer_url" class="form-label">Link trailer</label>
            <input wire:model="trailer_url" type="url" class="form-control @error('trailer_url') is-invalid @enderror" id="trailer_url" placeholder="Nhập URL trailer (ví dụ: https://youtube.com/watch?v=...)">
            @error('trailer_url')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="format" class="form-label">Định dạng <span class="text-danger">*</span></label>
            <select wire:model="format" class="form-select @error('format') is-invalid @enderror" id="format">
                <option value="">-- Chọn định dạng --</option>
                <option value="2D">2D</option>
                <option value="3D">3D</option>
                <option value="4DX">4DX</option>
                <option value="IMAX">IMAX</option>
            </select>
            @error('format')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Giá vé (VNĐ) <span class="text-danger">*</span></label>
            <input wire:model="price" type="number" step="1000" class="form-control @error('price') is-invalid @enderror" id="price" min="0" max="1000000" placeholder="Nhập giá vé (0 - 1,000,000 VNĐ)">
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Thể loại <span class="text-danger">*</span></label>
            <div class="row">
                @foreach ($genres as $genre)
                    <div class="col-md-4">
                        <div class="form-check">
                            <input wire:model="genre_ids" class="form-check-input @error('genre_ids') is-invalid @enderror" type="checkbox" value="{{ $genre->id }}" id="genre_{{ $genre->id }}">
                            <label class="form-check-label" for="genre_{{ $genre->id }}">{{ $genre->name }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
            <small class="form-text text-muted">Giữ Ctrl (Windows) hoặc Command (Mac) để chọn nhiều thể loại.</small>
            @error('genre_ids')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Thêm phim</button>
        <a href="{{ route('admin.movies.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>

    @push('scripts')
        <script>
            document.getElementById('release_date').addEventListener('change', function() {
                const releaseDate = this.value;
                const endDateInput = document.getElementById('end_date');
                if (releaseDate) {
                    endDateInput.setAttribute('min', releaseDate);
                } else {
                    endDateInput.removeAttribute('min');
                }
            });
        </script>
    @endpush
</div>