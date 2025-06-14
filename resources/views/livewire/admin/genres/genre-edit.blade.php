<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container-lg mb-5">
        <div class="d-flex justify-content-between align-items-center my-4">
            <h2 class="text-light">Chỉnh sửa thể loại: {{ $name }}</h2>
            <a href="{{ route('admin.genres.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card bg-dark shadow-lg">
                    <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="my-2">Thông tin thể loại</h5>
                    </div>
                    <div class="card-body bg-dark p-4">
                        <form wire:submit.prevent="update" novalidate>
                            <div class="row g-4">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="name" class="form-label text-light fw-bold">Tên thể loại <span class="text-danger">*</span></label>
                                        <input wire:model="name" type="text" class="form-control bg-dark text-light border-light @error('name') is-invalid @enderror" id="name" placeholder="Nhập tên thể loại" autocomplete="off">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="description" class="form-label text-light fw-bold">Mô tả</label>
                                        <textarea wire:model="description" class="form-control bg-dark text-light border-light @error('description') is-invalid @enderror" id="description" rows="5" placeholder="Nhập mô tả chi tiết về thể loại (tối đa 50 ký tự)"></textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-light fw-bold">Phim áp dụng</label>
                                @if ($movies->isEmpty() && empty($movie_ids))
                                    <div class="alert alert-info bg-dark border-info rounded-3 py-2">
                                        <i class="fas fa-info-circle text-info me-2"></i>
                                        <span class="text-light">Hiện tại không có phim nào đang chiếu hoặc sắp chiếu để gán cho thể loại.</span>
                                    </div>
                                @else
                                    <div class="card bg-dark border-light-subtle rounded-3 p-3 mb-3">
                                        <div class="row row-cols-1 row-cols-md-3 g-3">
                                            @foreach ($movies as $movie)
                                                <div class="col">
                                                    <div class="card h-100 bg-dark-subtle rounded-2 p-2 transition-all duration-300 hover:bg-gradient hover:from-[#667eea] hover:to-[#764ba2] hover:text-white hover:shadow-lg">
                                                        <div class="form-check d-flex align-items-center h-100">
                                                            <input wire:model="movie_ids" class="form-check-input me-3" type="checkbox" value="{{ $movie->id }}" id="movie_{{ $movie->id }}" {{ in_array($movie->id, $movie_ids) ? 'checked' : '' }}>
                                                            <label class="form-check-label text-light flex-grow-1" for="movie_{{ $movie->id }}">{{ $movie->title }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <small class="text-muted mt-2 d-block">Chọn các phim thuộc thể loại này (chỉ hiển thị phim đang chiếu hoặc sắp chiếu).</small>
                                    @if (!empty($movie_ids))
                                        <div class="mt-2">
                                            <small class="text-warning">Phim đã gán trước đó (bao gồm cả phim không còn đang chiếu hoặc sắp chiếu):</small>
                                            <ul class="list-unstyled text-light">
                                                @php
                                                    try {
                                                        $existingMovies = \App\Models\Movie::whereIn('id', $movie_ids)->get();
                                                    } catch (\Exception $e) {
                                                        $existingMovies = collect();
                                                    }
                                                @endphp
                                                @foreach ($existingMovies as $movie)
                                                    <li>{{ $movie->title }} ({{ $movie->status == 'showing' ? 'Đang chiếu' : ($movie->status == 'upcoming' ? 'Sắp chiếu' : 'Ngừng chiếu') }})</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                @endif
                                @error('movie_ids')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <button type="submit" class="btn btn-warning btn-lg">
                                    <i class="fas fa-save me-2"></i> Cập nhật
                                </button>
                                <a href="{{ route('admin.genres.index') }}" class="btn btn-outline-danger btn-lg">
                                    <i class="fas fa-times me-2"></i> Hủy
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>