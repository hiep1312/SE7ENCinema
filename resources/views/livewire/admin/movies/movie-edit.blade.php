<div>
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Chỉnh sửa phim: {{ $movie->title }}</h2>
            <a href="{{ route('admin.movies.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card bg-dark">
                    <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="my-1">Thông tin phim</h5>
                    </div>
                    <div class="card-body bg-dark">
                        <form wire:submit.prevent="update">
                            <div class="row align-items-start">
                                <div class="col-md-3 mb-3">
                                    @if($movie->poster)
                                        <div class="mt-1 overflow-auto position-relative" style="max-height: 230px;">
                                            <img src="{{ asset('storage/' . $movie->poster) }}" alt="Poster hiện tại" class="img-thumbnail" style="width: 100%;">
                                            <span class="position-absolute opacity-75 top-0 start-0 mt-2 ms-2 badge rounded bg-danger">Poster hiện tại</span>
                                        </div>
                                        <div class="form-check mt-2">
                                            <input wire:model="delete_poster" class="form-check-input" type="checkbox" id="delete_poster">
                                            <label class="form-check-label text-light" for="delete_poster">Xóa poster hiện tại</label>
                                        </div>
                                    @endif
                                    @if ($poster && $poster instanceof Illuminate\Http\UploadedFile)
                                        <div class="mt-2 overflow-auto position-relative" style="max-height: 230px;">
                                            <img src="{{ $poster->temporaryUrl() }}" alt="Poster mới" class="img-thumbnail" style="width: 100%;">
                                            <span class="position-absolute opacity-75 top-0 start-0 mt-2 ms-2 badge rounded bg-success">Poster mới</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-9 row align-items-start">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="title" class="form-label text-light">Tiêu đề</label>
                                            <input wire:model="title" type="text" class="form-control bg-dark text-light border-light @error('title') is-invalid @enderror" id="title" required>
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="duration" class="form-label text-light">Thời lượng (phút)</label>
                                            <input wire:model="duration" type="number" step="1" class="form-control bg-dark text-light border-light @error('duration') is-invalid @enderror" id="duration" min="1" required>
                                            @error('duration')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="release_date" class="form-label text-light">Ngày phát hành</label>
                                            <input wire:model="release_date" type="date" class="form-control bg-dark text-light border-light @error('release_date') is-invalid @enderror" id="release_date" required>
                                            @error('release_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="end_date" class="form-label text-light">Ngày kết thúc (không bắt buộc)</label>
                                            <input wire:model="end_date" type="date" class="form-control bg-dark text-light border-light @error('end_date') is-invalid @enderror" id="end_date">
                                            <small class="form-text text-muted">Ngày kết thúc phải cùng hoặc sau ngày phát hành.</small>
                                            @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="age_restriction" class="form-label text-light">Độ tuổi hạn chế</label>
                                            <select wire:model="age_restriction" class="form-select bg-dark text-light border-light @error('age_restriction') is-invalid @enderror" id="age_restriction">
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
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="format" class="form-label text-light">Định dạng</label>
                                            <select wire:model="format" class="form-select bg-dark text-light border-light @error('format') is-invalid @enderror" id="format">
                                                <option value="2D">2D</option>
                                                <option value="3D">3D</option>
                                                <option value="4DX">4DX</option>
                                                <option value="IMAX">IMAX</option>
                                            </select>
                                            @error('format')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="price" class="form-label text-light">Giá vé (VNĐ)</label>
                                            <input wire:model="price" type="number" step="1" class="form-control bg-dark text-light border-light @error('price') is-invalid @enderror" id="price" min="0" max="1000000" placeholder="Nhập giá vé">
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="director" class="form-label text-light">Đạo diễn (không bắt buộc)</label>
                                            <input wire:model="director" type="text" class="form-control bg-dark text-light border-light @error('director') is-invalid @enderror" id="director" placeholder="Nhập tên đạo diễn">
                                            @error('director')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="poster" class="form-label text-light">Poster (ảnh)</label>
                                            <input wire:model="poster" type="file" class="form-control bg-dark text-light border-light @error('poster') is-invalid @enderror" id="poster" accept="image/*">
                                            <small class="form-text text-muted">Chọn để thay đổi ảnh poster (bỏ trống nếu không thay đổi)</small>
                                            @error('poster')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="trailer_url" class="form-label text-light">Link trailer</label>
                                            <input wire:model="trailer_url" type="url" class="form-control bg-dark text-light border-light @error('trailer_url') is-invalid @enderror" id="trailer_url">
                                            @error('trailer_url')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="actors" class="form-label text-light">Diễn viên (không bắt buộc)</label>
                                            <textarea wire:model="actors" class="form-control bg-dark text-light border-light @error('actors') is-invalid @enderror" id="actors" rows="3" placeholder="Nhập danh sách diễn viên..."></textarea>
                                            @error('actors')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label text-light">Thể loại</label>
                                            <div class="row">
                                                @foreach ($genres as $genre)
                                                    <div class="col-md-4">
                                                        <div class="form-check">
                                                            <input wire:model="genre_ids" class="form-check-input @error('genre_ids') is-invalid @enderror" type="checkbox" value="{{ $genre->id }}" id="genre_{{ $genre->id }}">
                                                            <label class="form-check-label text-light" for="genre_{{ $genre->id }}">{{ $genre->name }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @error('genre_ids')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="description" class="form-label text-light">Mô tả</label>
                                            <textarea wire:model="description" class="form-control bg-dark text-light border-light @error('description') is-invalid @enderror" id="description" rows="5" placeholder="Nhập mô tả chi tiết về phim..."></textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Cập nhật phim
                                </button>
                                <a href="{{ route('admin.movies.index') }}" class="btn btn-outline-danger">
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