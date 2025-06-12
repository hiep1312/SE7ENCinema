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
            
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card bg-dark">
                    <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="my-1">Thông tin thể loại</h5>
                    </div>
                    <div class="card-body bg-dark">
                        <form wire:submit.prevent="store" novalidate>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label text-light">Tên thể loại <span class="text-danger">*</span></label>
                                        <input wire:model="name" type="text" class="form-control bg-dark text-light border-light @error('name') is-invalid @enderror" id="name" placeholder="Nhập tên thể loại" autocomplete="off">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="description" class="form-label text-light">Mô tả</label>
                                        <textarea wire:model="description" class="form-control bg-dark text-light border-light @error('description') is-invalid @enderror" id="description" rows="5" placeholder="Nhập mô tả chi tiết về thể loại (tối đa 1000 ký tự)"></textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-light">Phim áp dụng</label>
                                @if ($movies->isEmpty())
                                    <div class="alert alert-info bg-dark border-info">
                                        <i class="fas fa-info-circle text-info"></i>
                                        <span class="text-light">Hiện tại không có phim nào đang chiếu hoặc sắp chiếu để gán cho thể loại.</span>
                                    </div>
                                @else
                                    <div class="row">
                                        @foreach ($movies as $movie)
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input wire:model="movie_ids" class="form-check-input @error('movie_ids') is-invalid @enderror" type="checkbox" value="{{ $movie->id }}" id="movie_{{ $movie->id }}">
                                                    <label class="form-check-label text-light" for="movie_{{ $movie->id }}">{{ $movie->title }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <small class="text-muted">Chọn các phim thuộc thể loại này (chỉ hiển thị phim đang chiếu hoặc sắp chiếu).</small>
                                @endif
                                @error('movie_ids')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Thêm thể loại
                                </button>
                                <a href="{{ route('admin.genres.index') }}" class="btn btn-outline-danger">
                                    <i class="fas fa-times"></i> Hủy
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>