@use('App\Models\Showtime')
@use('Illuminate\Http\UploadedFile')
@use('App\Models\Genre')

@assets
<script>
    function updateSelected(selectedAll = true){
        const checkboxItems = document.querySelectorAll('#checkboxList input[type="checkbox"]');
        checkboxItems.forEach(item => {
            item.checked !== selectedAll && item.click();
        });
    }

    function updateEndTime(index) {
        const $wire = Livewire.first();

        const start_time = $wire.showtimes[index].start_time && Date.parse($wire.showtimes[index].start_time);
        const duration = parseInt($wire.duration || 0) * 60000;
        const end_time = new Date((+start_time + duration) || Date.now());
        console.log(end_time.toISOString(), new Date(+start_time).toISOString(), $wire.showtimes[index].start_time);
        document.getElementById(`showtimes.${index}.end_time`).value = end_time.toISOString().slice(0, -5);
    }

    const showtimes = @json($showtimes);
</script>
@endassets
<div class="scRender">
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-2 mx-2" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Chỉnh sửa phim: {{ $movieItem->title }}</h2>
            <a href="{{ route('admin.movies.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <!-- Form thông tin phim -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-dark">
                    <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="my-1">Thông tin phim</h5>
                    </div>
                    <div class="card-body bg-dark">
                        <form wire:submit.prevent="updateMovie" enctype="multipart/form-data" novalidate>
                            <div class="row align-items-start">
                                <div class="col-md-3 col-xxl-2 col-{{ ($poster && $poster instanceof UploadedFile) ? '12' : '6' }} d-flex d-md-block gap-2 mb-3">
                                        <div class="mt-1 movie-poster w-100 position-relative" style="aspect-ratio: 4 / 5; height: auto; margin: 0;">
                                            @if($movieItem->poster)
                                                <img src="{{ asset('storage/' . $movieItem->poster) }}" alt="Ảnh poster hiện tại"
                                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 0;">
                                            @else
                                                <i class="fas fa-film" style="font-size: 32px;"></i>
                                            @endif
                                            <span class="position-absolute opacity-75 top-0 start-0 mt-2 ms-2 badge rounded bg-danger">
                                                Ảnh hiện tại
                                            </span>
                                        </div>
                                    @if ($poster && $poster instanceof UploadedFile)
                                        <div class="mt-md-2 mt-1 movie-poster w-100 position-relative" style="aspect-ratio: 4 / 5; height: auto; margin: 0;">
                                            <img src="{{ $poster->temporaryUrl() }}" alt="Ảnh poster mới"
                                                style="width: 100%; height: 100%; object-fit: cover; border-radius: 0;">
                                            <span class="position-absolute opacity-75 top-0 start-0 mt-2 ms-2 badge rounded bg-success">
                                                Ảnh mới
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-9 col-xxl-10 row align-items-start">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="title" class="form-label text-light">Tiêu đề phim *</label>
                                            <input type="text" id="title" wire:model="title"
                                                class="form-control bg-dark text-light border-light @error('title') is-invalid @enderror"
                                                placeholder="VD: Avengers Endgame">
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="duration" class="form-label text-light">Thời lượng *</label>
                                            <input type="number"
                                                id = "duration"
                                                wire:model.blur="duration"
                                                class="form-control bg-dark text-light border-light @error('duration') is-invalid @enderror"
                                                placeholder="VD: 120" min="1"
                                                {{ $movieItem->hasActiveShowtimes() ? 'readonly' : '' }}>
                                            @error('duration')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="release_date" class="form-label text-light">Ngày khởi chiếu *</label>
                                            <input type="date"
                                                id = "release_date"
                                                wire:model="release_date"
                                                @change="updateStatus"
                                                class="form-control bg-dark text-light border-light @error('release_date') is-invalid @enderror"
                                                {{ strtotime($movieItem->release_date) < time() ? 'readonly' : '' }}>
                                            @error('release_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="end_date" class="form-label text-light">Ngày kết thúc </label>
                                            <input type="date"
                                                id = "end_date"
                                                wire:model = "end_date"
                                                @change="updateStatus"
                                                class="form-control bg-dark text-light border-light @error('end_date') is-invalid @enderror"
                                                {{ $movieItem->hasActiveShowtimes() ? 'readonly' : '' }}>
                                            @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="description" class="form-label text-light">Mô tả </label>
                                            <textarea id="description" wire:model="description" class="form-control bg-dark text-light border-light @error('description') is-invalid @enderror" placeholder="VD: Một nhóm siêu anh hùng tập hợp lại để cứu thế giới khỏi mối đe dọa từ Thanos..."></textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="director" class="form-label text-light">Đạo diễn </label>
                                            <input type="text"
                                                id = "director"
                                                wire:model="director"
                                                class="form-control bg-dark text-light border-light @error('director') is-invalid @enderror"
                                                placeholder="VD: Anthony Russo, Joe Russo">
                                            @error('director')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="actors" class="form-label text-light">Diễn viên </label>
                                            <input type="text"
                                                id = "actors"
                                                wire:model="actors"
                                                class="form-control bg-dark text-light border-light @error('actors') is-invalid @enderror"
                                                placeholder="VD: Robert Downey Jr., Chris Evans, Scarlett Johansson">
                                            @error('actors')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="poster" class="form-label text-light">Ảnh poster </label>
                                            <input type="file"
                                                id = "poster"
                                                wire:model.live="poster"
                                                class="form-control bg-dark text-light border-light @error('poster') is-invalid @enderror"
                                                accept="image/*">
                                            @error('poster')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="trailer_url" class="form-label text-light">Trailer </label>
                                            <input type="url"
                                                id = "trailer_url"
                                                wire:model.live.debounce.500ms="trailer_url"
                                                class="form-control bg-dark text-light border-light @error('trailer_url') is-invalid @enderror"
                                                placeholder="VD: https://www.youtube.com/watch?v=TcMBFSGVi1c">
                                            @error('trailer_url')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="age_restriction" class="form-label text-light">Giới hạn độ tuổi *</label>
                                            <select id="age_restriction" wire:model="age_restriction" class="form-select bg-dark text-light border-light @error('age_restriction') is-invalid @enderror">
                                                <option value="P">Không giới hạn độ tuổi (P)</option>
                                                <option value="K">Dưới 13 tuổi (K)</option>
                                                <option value="T13">Trên 13+ tuổi (T13)</option>
                                                <option value="T16">Trên 16+ tuổi (T16)</option>
                                                <option value="T18">Trên 18+ tuổi (T18)</option>
                                                <option value="C">Cấm chiếu (C)</option>
                                            </select>
                                            @error('age_restriction')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="format" class="form-label text-light">Định dạng phim *</label>
                                            <select id="format" wire:model="format" class="form-select bg-dark text-light border-light @error('format') is-invalid @enderror">
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
                                            <label for="price" class="form-label text-light">Giá vé *</label>
                                            <input type="number"
                                                id = "price"
                                                wire:model="price"
                                                class="form-control bg-dark text-light border-light @error('price') is-invalid @enderror"
                                                placeholder="VD: 75000" min="0">
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status" class="form-label text-light">Trạng thái *</label>
                                            <select id="status" :value="$wire.status"
                                                class="form-select bg-dark text-light border-light @error('status') is-invalid @enderror"
                                                disabled>
                                                <option value="coming_soon">Sắp ra mắt</option>
                                                <option value="showing">Đang chiếu</option>
                                                <option value="ended">Đã kết thúc</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-100 mt-2">
                                <ul class="nav nav-tabs bg-dark" role="tablist">
                                    <li class="nav-item">
                                        <button type="button" class="nav-link @if($tabCurrent === 'showtimes') active bg-light text-dark @else text-light @endif"
                                                wire:click="$set('tabCurrent', 'showtimes')"
                                                style="border-top-right-radius: 0;">
                                            <i class="fa-solid fa-film me-1"></i>Suất chiếu
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button type="button" class="nav-link @if($tabCurrent === 'genres') active bg-light text-dark @else text-light @endif"
                                                wire:click="$set('tabCurrent', 'genres')"
                                                @if ($trailer_url) style="border-top-left-radius: 0; border-top-right-radius: 0;" @else style="border-top-left-radius: 0;" @endif>
                                            <i class="fa-solid fa-photo-film me-1"></i>Thể loại
                                        </button>
                                    </li>
                                    @if ($trailer_url)
                                        <li class="nav-item">
                                            <button type="button" class="nav-link @if($tabCurrent === 'trailer') active bg-light text-dark @else text-light @endif"
                                                    wire:click="$set('tabCurrent', 'trailer')"
                                                    style="border-top-left-radius: 0;">
                                                <i class="fas fa-video me-1"></i>Trailer phim
                                            </button>
                                        </li>
                                    @endif
                                </ul>
                                <div class="tab-content tab-manager">
                                    <!-- Overview Tab -->
                                    @if ($tabCurrent === 'showtimes')
                                        @if (session()->has('errorGeneratedShowtimes'))
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                {{ session('errorGeneratedShowtimes') }}
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                            </div>
                                        @elseif (session()->has('successGeneratedShowtimes'))
                                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                {{ session('successGeneratedShowtimes') }}
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                            </div>
                                        @endif
                                        <div class="row">
                                            <div class="col-md-6 datetime-group">
                                                <label class="datetime-label">
                                                    <span class="label-icon start-icon">
                                                        <i class="fas fa-play" style="margin-right: 0 !important;"></i>
                                                    </span>
                                                    Thời gian bắt đầu
                                                </label>
                                                <div class="datetime-input-wrapper">
                                                    <input type="datetime-local" class="datetime-input"
                                                        wire:model="baseShowtimeStart" id="startDateTime">
                                                </div>
                                                @error('baseShowtimeStart')
                                                    <small class="text-danger" style="color: var(--bs-form-invalid-color) !important; margin-left: 2px; margin-top: .25rem !important;">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 datetime-group">
                                                <label class="datetime-label">
                                                    <span class="label-icon end-icon">
                                                        <i class="fas fa-stop" style="margin-right: 0 !important;"></i>
                                                    </span>
                                                    Thời gian kết thúc
                                                </label>
                                                <div class="datetime-input-wrapper">
                                                    <input type="datetime-local" class="datetime-input"
                                                        wire:model="baseShowtimeEnd" id="endDateTime">
                                                </div>
                                                @error('baseShowtimeEnd')
                                                    <small class="text-danger" style="color: var(--bs-form-invalid-color) !important; margin-left: 2px; margin-top: .25rem !important;">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="text-center">
                                                <button type="button" class="btn-custom btn-primary-custom" wire:click="generateShowtimes">
                                                    <i class="fa-solid fa-spinner"></i>
                                                    Sinh động suất chiếu
                                                </button>
                                            </div>
                                        </div>
                                        <hr class="border-light">
                                        <div class="row mt-3">
                                            @foreach($showtimes as $index => $showtime)
                                                <div class="col-12 mb-4" wire:key="showtime-{{ $index }}">
                                                    <div class="card position-relative overflow-hidden" style="background-color: #1a1a1a; border: 1px solid #333;">
                                                        <!-- Accent line -->
                                                        <div class="position-absolute top-0 start-0 h-100" style="width: 4px; background: linear-gradient(to bottom, #6b7280, #374151);"></div>

                                                        @if(!(isset($showtime['id']) && Showtime::find($showtime['id'])?->isLockedForDeletion()))
                                                            <button type="button"
                                                                class="btn btn-sm position-absolute delete-btn"
                                                                wire:click="toggleShowtime({{ $index }})"
                                                                style="top: 1rem; right: 1rem; color: #6b7280; background: transparent; border: none; border-radius: 50%; padding: 0.5rem; transition: all 0.2s ease;">
                                                                <i class="fa-solid fa-x" style="margin-right: 0"></i></button>
                                                        @endif

                                                        <div class="card-body p-4">
                                                            <div class="mb-4">
                                                                <h3 class="text-white fw-semibold d-flex align-items-center gap-2" style="font-size: 1.125rem;">
                                                                    <span class="d-flex align-items-center justify-content-center text-white fw-bold rounded-circle"
                                                                        style="width: 2rem; height: 2rem; background: linear-gradient(to right, #6b7280, #374151); font-size: 0.875rem;">
                                                                        {{ $index + 1 }}
                                                                    </span>
                                                                    Suất chiếu {{ $index + 1 }}
                                                                </h3>
                                                            </div>

                                                            <div class="row g-3 align-items-start">
                                                                <div class="col-md-6">
                                                                    <div class="mb-2">
                                                                        <label for="showtimes.{{ $index }}.room_id" class="form-label text-light">Phòng chiếu *</label>
                                                                        <select id="showtimes.{{ $index }}.room_id" wire:model="showtimes.{{ $index }}.room_id" class="form-select bg-dark text-light border-light @error("showtimes.$index.room_id") is-invalid @enderror">
                                                                            <option value="">{{ $rooms->isEmpty() ? "Không có phòng chiếu nào đang hoạt động" : "-- Chọn phòng chiếu --" }}</option>
                                                                            @foreach($rooms as $room)
                                                                                <option value="{{ $room->id }}">{{ $room->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        @error("showtimes.$index.room_id")
                                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="mb-2">
                                                                        <label for="showtimes.{{ $index }}.status" class="form-label text-light">Trạng thái *</label>
                                                                        <select id="showtimes.{{ $index }}.status"
                                                                            wire:model="showtimes.{{ $index }}.status"
                                                                            class="form-select bg-dark text-light border-light @error("showtimes.$index.status") is-invalid @enderror"
                                                                            @if(!isset($showtimes[$index]['id'])) disabled @endif>
                                                                            <option value="active">Hoạt động</option>
                                                                            <option value="canceled">Hủy chiếu</option>
                                                                            {{-- <option value="completed">Đã hoàn thành</option> --}}
                                                                        </select>
                                                                        @error("showtimes.$index.status")
                                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="mb-2">
                                                                        <label for="showtimes.{{ $index }}.start_time" class="form-label text-light">Khung giờ chiếu *</label>
                                                                        <input type="datetime-local"
                                                                            id = "showtimes.{{ $index }}.start_time"
                                                                            wire:model.blur="showtimes.{{ $index }}.start_time"
                                                                            class="form-control bg-dark text-light border-light @error("showtimes.$index.start_time") is-invalid @enderror">
                                                                        @error("showtimes.$index.start_time")
                                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="mb-2">
                                                                        <label for="showtimes.{{ $index }}.end_time" class="form-label text-light">Khung giờ kết thúc *</label>
                                                                        <input type="datetime-local"
                                                                            id = "showtimes.{{ $index }}.end_time"
                                                                            class="form-control bg-dark text-light border-light"
                                                                            readonly value="{{ date("Y-m-d\TH:i", strtotime("+ $duration minutes", strtotime($showtimes[$index]['start_time'])) ?: null) }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="mb-2">
                                                                        <label for="showtimes.{{ $index }}.price" class="form-label text-light">Giá khung giờ *</label>
                                                                        <input type="text"
                                                                            id = "showtimes.{{ $index }}.price"
                                                                            wire:model="showtimes.{{ $index }}.price"
                                                                            class="form-control bg-dark text-light border-light @error("showtimes.$index.price") is-invalid @enderror"
                                                                            placeholder="VD: 20000đ" min="0">
                                                                        @error("showtimes.$index.price")
                                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <div class="mt-2 d-flex justify-content-center">
                                                <button type="button" wire:click="toggleShowtime" class="btn-custom btn-success-custom" style="padding: 0.7rem 1.2rem;">
                                                    <i class="fa-solid fa-circle-plus"></i> Thêm suất chiếu mới
                                                </button>
                                            </div>
                                        </div>
                                    @elseif($tabCurrent === 'genres')
                                        <div class="search-box">
                                            <input type="text" wire:model.live.debounce.300ms="searchGenre" placeholder="Tìm kiếm thể loại..." autocomplete="off">
                                            <div class="search-icon">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <circle cx="11" cy="11" r="8"></circle>
                                                    <path d="m21 21-4.35-4.35"></path>
                                                </svg>
                                            </div>
                                        </div>

                                        <div class="filter-controls">
                                            @if(!empty($searchGenre) && $genres->isEmpty())
                                                <button type="button" id="addTag" class="control-btn-green" wire:click="addGenre">Thêm thể loại</button>
                                            @else
                                                <button type="button" id="selectAll" class="control-btn" onclick="updateSelected(true)">Chọn tất cả</button>
                                                <button type="button" id="deselectAll" class="control-btn" onclick="updateSelected(false)">Bỏ chọn tất cả</button>
                                            @endif
                                            <button type="button" id="clearSearch" class="control-btn clear-btn" wire:click="$set('searchGenre', '')">Xóa tìm kiếm</button>
                                        </div>

                                        <div class="checkbox-container">
                                            <div class="checkbox-list" id="checkboxList">
                                                @forelse($genres as $genre)
                                                    <div class="checkbox-item" onclick="this.querySelector('input[type=checkbox]').click()" wire:key="genre-{{ $genre->id }}">
                                                        <div class="checkbox-wrapper">
                                                            <input type="checkbox" wire:model.live="genresSelected" value="{{ $genre->id }}">
                                                            <span class="checkmark"></span>
                                                        </div>
                                                        <label class="checkbox-label">{{ $genre->name }}</label>
                                                    </div>
                                                @empty
                                                    <div class="empty-state">Không tìm thấy thể loại nào</div>
                                                @endforelse
                                            </div>
                                        </div>
                                        <div class="selected-items">
                                            <h3>Đã chọn <span id="selectedCount">{{ count($genresSelected) }}</span>/{{ $genres->count() }} thể loại:</h3>
                                            <div class="selected-tags" id="selectedTags">
                                                @forelse($genresSelected as $genreId)
                                                    <div class="tag">
                                                        {{ Genre::find($genreId)?->name }}
                                                        <span class="remove-tag" onclick="document.querySelector('input[type=checkbox][value=\'{{ $genreId }}\']').click()">×</span>
                                                    </div>
                                                @empty
                                                    <div class="empty-state" style="padding: 10px 20px;">Chưa có thể loại nào</div>
                                                @endforelse
                                            </div>
                                        </div>
                                        @if($errors->has('searchGenre') || $errors->has('genresSelected'))
                                            <div class="error-panel" style="display: block;">
                                                <button type="button" class="close-error" onclick="this.parentElement.style.display = 'none'">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                                <div class="error-title">
                                                    <i class="fa-solid fa-triangle-exclamation error-icon"></i>
                                                    Lỗi xác thực
                                                </div>
                                                <div class="error-content">
                                                    {{ $errors->first('searchGenre') ?: $errors->first('genresSelected') }}
                                                </div>
                                            </div>
                                        @enderror
                                    @elseif($tabCurrent === 'trailer')
                                        <div class="video-container glow-effect mt-1">
                                            <div class="video-header">
                                                <div class="video-icon">
                                                    <i class="fa-brands fa-youtube"></i>
                                                </div>
                                                <div>
                                                    <h3 class="video-title">{{ $title }} | Official Trailer</h3>
                                                </div>
                                            </div>
                                            <div class="video-wrapper">
                                                <div class="responsive-iframe">
                                                    <iframe src="{{ getYoutubeEmbedUrl($trailer_url) ?: asset('storage/404.webp') }}"
                                                        title="YouTube video player" frameborder="0"
                                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                        allowfullscreen></iframe>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Cập nhật thông tin
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
@script
<script>
    window.updateStatus = function(){
        const releaseDate = $wire.release_date && Date.parse($wire.release_date);
        const endDate = $wire.end_date && Date.parse($wire.end_date);

        if(endDate && endDate < Date.now()) $wire.status = "ended";
        else if(releaseDate && releaseDate > Date.now()) $wire.status = "coming_soon";
        else $wire.status = "showing";
    }

    updateStatus();

    const IntervalUpdateShowtime = function(){
        showtimes.forEach(showtime => {
            const startTime = Date.parse(showtime['start_time']);
            if(startTime < Date.now()) $wire.$set('showtimes', $wire.showtimes.filter(st => showtime['id'] !== st['id']), true);
        });
    }
    setInterval(IntervalUpdateShowtime, 5000);
</script>
@endscript
