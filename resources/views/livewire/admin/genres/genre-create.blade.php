<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
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
            <h2 class="text-light">Thêm thể loại mới</h2>
            <a href="{{ route('admin.genres.index') }}" class="btn btn-outline-secondary d-flex align-items-center transition-all" style="transition: all 0.2s ease;">
                <i class="fas fa-arrow-left fa-fw me-2"></i> Quay lại
            </a>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card bg-dark border-light shadow-lg">
                    <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="my-1"><i class="fas fa-tags me-2"></i>Thông tin thể loại</h5>
                    </div>
                    <div class="card-body bg-dark p-4">
                        <form wire:submit.prevent="store" novalidate>
                            <div class="row g-3">
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

                            <hr class="border-light my-4">
                            <h5 class="text-light mb-3"><i class="fas fa-film me-2"></i>Phim áp dụng</h5>
                            <div class="mb-4">
                                <label class="form-label text-light fw-bold">Phim áp dụng <span class="text-danger">*</span></label>
                                @if ($movies->isEmpty())
                                    <div class="alert alert-info bg-dark border-info rounded-3 py-2">
                                        <i class="fas fa-info-circle text-info me-2"></i>
                                        <span class="text-light">Hiện tại không có phim nào đang chiếu hoặc sắp chiếu để gán cho thể loại.</span>
                                    </div>
                                @else
                                    <div class="row row-cols-1 row-cols-md-3 g-4">
                                        @foreach ($movies as $movie)
                                            <div class="col">
                                                <div class="card h-100 bg-dark-subtle border-0 shadow-sm rounded-3 overflow-hidden transition-all duration-300 hover:shadow-lg hover:bg-gradient hover:from-[#667eea] hover:to-[#764ba2]">
                                                    <div class="card-body p-3">
                                                        <div class="position-relative mb-3">
                                                            <img src="{{ $movie->poster ? asset('storage/' . $movie->poster) : asset('storage/404.webp') }}" alt="{{ $movie->title }} poster" class="img-fluid rounded-2 w-100" style="height: 200px; object-fit: cover;">
                                                            <div class="position-absolute top-0 end-0 mt-2 me-2">
                                                                <input wire:model="movie_ids" class="form-check-input" type="checkbox" value="{{ $movie->id }}" id="movie_{{ $movie->id }}" style="transform: scale(1.5);">
                                                            </div>
                                                        </div>
                                                        <h6 class="text-light mb-1">{{ $movie->title }}</h6>
                                                        <span class="badge bg-success text-dark">{{ $movie->status == 'showing' ? 'Đang chiếu' : 'Sắp chiếu' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <small class="text-muted mt-2 d-block">Chọn các phim thuộc thể loại này (chỉ hiển thị phim đang chiếu hoặc sắp chiếu).</small>
                                @endif
                                @error('movie_ids')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <button type="submit" class="btn btn-success btn-lg d-flex align-items-center transition-all" style="transition: all 0.2s ease;">
                                    <i class="fas fa-save fa-fw me-2"></i> Thêm thể loại
                                </button>
                                <a href="{{ route('admin.genres.index') }}" class="btn btn-outline-danger btn-lg d-flex align-items-center transition-all" style="transition: all 0.2s ease;">
                                    <i class="fas fa-times fa-fw me-2"></i> Hủy
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .transition-all:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            }
            .card-body {
                min-height: 300px;
            }
        </style>
    @endpush
</div>