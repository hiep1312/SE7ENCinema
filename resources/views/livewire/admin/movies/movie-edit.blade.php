<div class="container mt-4">
    <h1>Chỉnh sửa phim: {{ $movie->title }}</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form wire:submit.prevent="update">
        <!-- Title -->
        <div class="mb-3">
            <label for="title" class="form-label">Tiêu đề</label>
            <input wire:model="title" type="text" class="form-control @error('title') is-invalid @enderror" id="title" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea wire:model="description" class="form-control @error('description') is-invalid @enderror" id="description" rows="5" placeholder="Nhập mô tả chi tiết về phim..."></textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Duration -->
        <div class="mb-3">
            <label for="duration" class="form-label">Thời lượng (phút)</label>
            <input wire:model="duration" type="number" step="1" class="form-control @error('duration') is-invalid @enderror" id="duration" min="1" required>
            @error('duration')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Release Date -->
        <div class="mb-3">
            <label for="release_date" class="form-label">Ngày phát hành</label>
            <input wire:model="release_date" type="date" class="form-control @error('release_date') is-invalid @enderror" id="release_date" required>
            @error('release_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- End Date -->
        <div class="mb-3">
            <label for="end_date" class="form-label">Ngày kết thúc (không bắt buộc)</label>
            <input wire:model="end_date" type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date">
            <small class="form-text text-muted">Ngày kết thúc phải cùng hoặc sau ngày phát hành.</small>
            @error('end_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Director -->
        <div class="mb-3">
            <label for="director" class="form-label">Đạo diễn (không bắt buộc)</label>
            <input wire:model="director" type="text" class="form-control @error('director') is-invalid @enderror" id="director" placeholder="Nhập tên đạo diễn">
            @error('director')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Actors -->
        <div class="mb-3">
            <label for="actors" class="form-label">Diễn viên (không bắt buộc)</label>
            <textarea wire:model="actors" class="form-control @error('actors') is-invalid @enderror" id="actors" rows="3" placeholder="Nhập danh sách diễn viên..."></textarea>
            @error('actors')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Age Restriction -->
        <div class="mb-3">
            <label for="age_restriction" class="form-label">Độ tuổi hạn chế</label>
            <select wire:model="age_restriction" class="form-select @error('age_restriction') is-invalid @enderror" id="age_restriction">
                <option value="P">Tất cả</option>
                <option value="K">K</option>
                <option value="T13">T13</option>
                <option value="T16">T16</option>
                <option value="T18">T18</option>
                <option value="C">C</option>
            </select>
            @error('age_restriction')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Poster -->
        <div class="mb-3">
            <label for="poster" class="form-label">Poster (ảnh)</label>
            @if($movie->poster)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $movie->poster) }}" alt="{{ $movie->title }}" style="width: 120px; height: auto;">
                    <div class="form-check mt-2">
                        <input wire:model="delete_poster" class="form-check-input" type="checkbox" id="delete_poster">
                        <label class="form-check-label" for="delete_poster">Xóa poster hiện tại</label>
                    </div>
                </div>
            @endif
            <input wire:model="poster" type="file" class="form-control @error('poster') is-invalid @enderror" id="poster" accept="image/*">
            <small class="form-text text-muted">Chọn để thay đổi ảnh poster (bỏ trống nếu không thay đổi)</small>
            @error('poster')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Trailer URL -->
        <div class="mb-3">
            <label for="trailer_url" class="form-label">Link trailer</label>
            <input wire:model="trailer_url" type="url" class="form-control @error('trailer_url') is-invalid @enderror" id="trailer_url">
            @error('trailer_url')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Format -->
        <div class="mb-3">
            <label for="format" class="form-label">Định dạng</label>
            <select wire:model="format" class="form-select @error('format') is-invalid @enderror" id="format">
                <option value="2D">2D</option>
                <option value="3D">3D</option>
                <option value="4DX">4DX</option>
                <option value="IMAX">IMAX</option>
            </select>
            @error('format')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Price -->
        <div class="mb-3">
            <label for="price" class="form-label">Giá vé (VNĐ)</label>
            <input wire:model="price" type="number" step="1" class="form-control @error('price') is-invalid @enderror" id="price" min="0" max="1000000" placeholder="Nhập giá vé">
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Genres -->
        <div class="mb-3">
            <label class="form-label">Thể loại</label>
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
            @error('genre_ids')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật phim</button>
        <a href="{{ route('admin.movies.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>