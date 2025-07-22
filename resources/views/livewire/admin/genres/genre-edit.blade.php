@use('App\Models\Movie')
@assets
<script>
    function updateSelected(selectedAll = true){
        const checkboxItems = document.querySelectorAll('#checkboxList input[type="checkbox"]');
        checkboxItems.forEach(item => {
            item.checked !== selectedAll && item.click();
        });
    }
</script>
@endassets
<div class="scRender">
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Chỉnh sửa thể loại: {{ $genreItem->name }}</h2>
            <a href="{{ route('admin.genres.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <!-- Form thông tin thể loại -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-dark">
                    <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="my-1">Thông tin thể loại</h5>
                    </div>
                    <div class="card-body bg-dark">
                        <form wire:submit.prevent="updateGenre" novalidate>
                            <div class="row align-items-start mb-2">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label text-light">Tên thể loại *</label>
                                        <input type="text" id="name" wire:model="name"
                                            class="form-control bg-dark text-light border-light @error('name') is-invalid @enderror"
                                            placeholder="VD: Hành động, Kinh dị, Tình cảm, Hài hước">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="description" class="form-label text-light">Mô tả </label>
                                        <textarea id="description" wire:model="description" class="form-control bg-dark text-light border-light @error('description') is-invalid @enderror" placeholder="VD: Thể loại có nhiều pha hành động nghẹt thở, phù hợp với khán giả yêu thích kịch tính."></textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="w-100 mt-2">
                                <ul class="nav nav-tabs bg-dark" role="tablist">
                                    <li class="nav-item">
                                        <button type="button" class="nav-link @if($tabCurrent === 'movies') active bg-light text-dark @else text-light @endif"
                                                wire:click="$set('tabCurrent', 'movies')"
                                                style="border-top-right-radius: 0; border-top-left-radius: 0;">
                                            <i class="fas fa-film me-1"></i>Phim liên kết
                                        </button>
                                    </li>
                                </ul>
                                <div class="tab-content tab-manager">
                                    @if ($tabCurrent === 'movies')
                                        <div class="search-box">
                                            <input type="text" wire:model.live.debounce.300ms="searchMovie" placeholder="Tìm kiếm phim..." autocomplete="off">
                                            <div class="search-icon">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <circle cx="11" cy="11" r="8"></circle>
                                                    <path d="m21 21-4.35-4.35"></path>
                                                </svg>
                                            </div>
                                        </div>

                                        <div class="filter-controls">
                                            <button type="button" id="selectAll" class="control-btn" onclick="updateSelected(true)">Chọn tất cả</button>
                                            <button type="button" id="deselectAll" class="control-btn" onclick="updateSelected(false)">Bỏ chọn tất cả</button>
                                            <button type="button" id="clearSearch" class="control-btn clear-btn" wire:click="$set('searchMovie', '')">Xóa tìm kiếm</button>
                                        </div>

                                        <div class="checkbox-container">
                                            <div class="checkbox-list" id="checkboxList">
                                                @forelse($movies as $movie)
                                                    <div class="checkbox-item" onclick="this.querySelector('input[type=checkbox]').click()" wire:key="movie-{{ $movie->id }}">
                                                        <div class="checkbox-wrapper">
                                                            <input type="checkbox" wire:model.live="moviesSelected" value="{{ $movie->id }}">
                                                            <span class="checkmark"></span>
                                                        </div>
                                                        <label class="checkbox-label">{{ $movie->title }}</label>
                                                    </div>
                                                @empty
                                                    <div class="empty-state">Không tìm thấy phim nào</div>
                                                @endforelse
                                            </div>
                                        </div>
                                        <div class="selected-items">
                                            <h3>Đã chọn <span id="selectedCount">{{ count($moviesSelected) }}</span>/{{ $movies->count() }} thể loại:</h3>
                                            <div class="selected-tags" id="selectedTags">
                                                @forelse($moviesSelected as $movieId)
                                                    <div class="tag">
                                                        {{ Movie::find($movieId)?->title }}
                                                        <span class="remove-tag" onclick="document.querySelector('input[type=checkbox][value=\'{{ $movieId }}\']').click()">×</span>
                                                    </div>
                                                @empty
                                                    <div class="empty-state" style="padding: 10px 20px;">Chưa có phim nào</div>
                                                @endforelse
                                            </div>
                                        </div>
                                        @error('moviesSelected')
                                            <div class="error-panel" style="display: block;">
                                                <button type="button" class="close-error" onclick="this.parentElement.style.display = 'none'">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                                <div class="error-title">
                                                    <i class="fa-solid fa-triangle-exclamation error-icon"></i>
                                                    Lỗi xác thực
                                                </div>
                                                <div class="error-content">
                                                    {{ $message }}
                                                </div>
                                            </div>
                                        @enderror
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Cập nhật thông tin
                                </button>
                                <a href="{{ route('admin.genres.index') }}" class="btn btn-outline-danger">
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
