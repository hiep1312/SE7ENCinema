<div class="scRender">
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show mt-2 mx-2" role="alert" wire:ignore>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-2 mx-2" role="alert" wire:ignore>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Quản lý phim</h2>
            <div>
                @if(!$showDeleted)
                    <a href="{{ route('admin.movies.create') }}" class="btn btn-success me-2">
                        <i class="fas fa-plus me-1"></i>Thêm phim
                    </a>
                @endif
                <button wire:click="$toggle('showDeleted')" class="btn btn-outline-danger">
                    @if($showDeleted)
                        <i class="fas fa-eye me-1"></i>Xem phim hoạt động
                    @else
                        <i class="fas fa-trash me-1"></i>Xem phim đã xóa
                    @endif
                </button>
            </div>
        </div>

        <div class="card bg-dark" wire:poll.6s>
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="row g-3">
                    <!-- Tìm kiếm -->
                    <div class="col-md-4 col-lg-3">
                        <div class="input-group">
                            <input type="text"
                                   wire:model.live.debounce.300ms="search"
                                   class="form-control bg-dark text-light"
                                   placeholder="Tìm kiếm phim...">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Lọc theo trạng thái -->
                    @if(!$showDeleted)
                        <div class="col-md-3 col-lg-2">
                            <select wire:model.live="statusFilter" class="form-select bg-dark text-light">
                                <option value="">Tất cả trạng thái</option>
                                <option value="coming_soon">Sắp ra mắt</option>
                                <option value="showing">Đang chiếu</option>
                                <option value="ended">Đã kết thúc</option>
                            </select>
                        </div>

                        <!-- Lọc theo suất chiếu -->
                        <div class="col-md-3 col-lg-2">
                            <select wire:model.live="showtimeFilter" class="form-select bg-dark text-light">
                                <option value="">Tất cả suất chiếu</option>
                                <option value="has_showtimes">Có suất chiếu</option>
                                <option value="no_showtimes">Không có suất chiếu</option>
                            </select>
                        </div>

                        <!-- Reset filters -->
                        <div class="col-md-2">
                            <button wire:click="resetFilters" class="btn btn-outline-warning">
                                <i class="fas fa-refresh me-1"></i>Reset
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card-body bg-dark">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <tr>
                                <th class="text-center text-light">STT</th>
                                <th class="text-center text-light">Ảnh poster</th>
                                <th class="text-center text-light">Tiêu đề phim</th>
                                <th class="text-center text-light">Ngày phát hành</th>
                                <th class="text-center text-light">Trạng thái</th>
                                <th class="text-center text-light">Giá vé</th>
                                <th class="text-center text-light">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    Suất chiếu tiếp theo
                                </th>
                                @if($showDeleted)
                                    <th class="text-center text-light">Ngày xóa</th>
                                @else
                                    <th class="text-center text-light">Ngày tạo</th>
                                @endif
                                <th class="text-center text-light">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($movies as $movie)
                                <tr>
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <div class="movie-poster position-relative" style="width: 80px; height: 100px; margin: 0;">
                                                @if($movie->poster)
                                                    <img src="{{ asset('storage/' . $movie->poster) }}"
                                                        alt="Ảnh phim {{ $movie->title }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 0;">
                                                @else
                                                    <i class="fas fa-film" style="font-size: 22px;"></i>
                                                @endif
                                                <span class="position-absolute opacity-75 top-0 start-0 mt-1 ms-1 badge bg-success" style="border-radius: 50%; cursor: pointer;"
                                                    data-bs-toggle="modal" data-bs-target="#trailerPreview"
                                                    data-trailer-url="{{ getYoutubeEmbedUrl((string)$movie->trailer_url) ?: asset('storage/404.webp') }}" data-trailer-title="{{ $movie->title }}">
                                                    <i class="fas fa-play me-1" style="margin-right: 0 !important;"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="max-width: 200px;">
                                        <strong class="text-light text-wrap lh-base">{{ $movie->title }}</strong>
                                        @if($movie->trashed())
                                            <span class="badge bg-danger ms-1">Đã xóa</span>
                                        @endif
                                        <div class="movie-genre text-wrap lh-base" style="margin-bottom: 0; margin-top: 3px;">
                                            <i class="fas fa-tags me-1"></i>
                                            {{ $movie->genres->take(3)->implode('name', ', ') ?: 'Không có thể loại' }} • {{ $movie->duration }} phút
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="mb-1">
                                            <small style="color: #34c759;">
                                                <i class="fas fa-play me-1"></i>
                                                {{ $movie->release_date->format('d/m/Y') }}
                                            </small>
                                        </div>
                                        <div>
                                            <small style="color: #ff4d4f;">
                                                <i class="fas fa-stop me-1"></i>
                                                {!! $movie->end_date?->format('d/m/Y') ?? "Vĩnh viễn &nbsp;&nbsp;&nbsp;" !!}
                                            </small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if(!$showDeleted && !$movie->trashed())
                                            @switch($movie->status)
                                                @case('showing')
                                                    <span class="badge bg-success"><i class="fas fa-play me-1"></i>Đang chiếu</span>
                                                    @break
                                                @case('coming_soon')
                                                    <span class="badge" style="background-color: #2bbafc; color: #ffffff;"><i class="fa-solid fa-calendar-clock me-1"></i>Sắp ra mắt</span>
                                                    @break
                                                @case('ended')
                                                    <span class="badge bg-danger"><i class="fa-solid fa-calendar-xmark me-1"></i>Đã kết thúc</span>
                                                    @break
                                            @endswitch
                                        @else
                                            <span class="badge bg-secondary">Đã xóa</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-gradient fs-6" style="background: linear-gradient(45deg, #667eea, #764ba2);">
                                            {{ number_format($movie->price, 0, '.', '.') }}đ
                                        </span>
                                    </td>

                                    <!-- CỘT SUẤT CHIẾU TIẾP THEO -->
                                    <td class="bg-opacity-10 border-start border-3">
                                        @if(!$showDeleted && $movie->showtimes->count() > 0)
                                            @php $nextShowtime = $movie->showtimes->first(); @endphp
                                            <div>
                                                <div class="mb-1">
                                                    <i class="fa-solid fa-person-booth text-primary me-1"></i>
                                                    <strong class="text-primary">
                                                        {{ $nextShowtime->room->name ?? 'Không tìm thấy phòng chiếu' }}
                                                    </strong>
                                                </div>

                                                <!-- Thời gian chiếu -->
                                                <div class="mb-1">
                                                    <i class="fas fa-clock me-1 text-success"></i>
                                                    <span class="text-success">
                                                        {{ $nextShowtime->start_time->format('d/m/Y') }}
                                                    </span>
                                                    <br>
                                                    <small class="text-muted ms-3">
                                                        {{ $nextShowtime->start_time->format('H:i') }} -
                                                        {{ $nextShowtime->end_time->format('H:i') }}
                                                    </small>
                                                </div>

                                                <div class="mb-1">
                                                    <i class="fas fa-money-bill me-1 text-warning"></i>
                                                    <span class="text-warning">
                                                        {{ number_format($nextShowtime->price, 0, '.', '.') }}đ
                                                    </span>
                                                </div>

                                                <!-- Badge trạng thái -->
                                                @if($movie->hasActiveShowtimes())
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-play me-1"></i>Có suất chiếu
                                                    </span>
                                                @endif

                                                <!-- Thời gian còn lại -->
                                                <div class="mt-1">
                                                    <small class="text-info">
                                                        <i class="fas fa-hourglass-half me-1"></i>
                                                        {{ $nextShowtime->start_time->diffForHumans() }}
                                                    </small>
                                                </div>
                                            </div>
                                        @else
                                            <!-- Khi không có suất chiếu -->
                                            <div class="text-center py-2" style="margin: auto 0;">
                                                <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                                                <div class="text-muted">
                                                    <strong>Không có suất chiếu</strong>
                                                </div>
                                                <small class="text-muted">Chưa lên lịch chiếu</small>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($showDeleted)
                                            <span class="text-light">
                                                {{ $movie->deleted_at ? $movie->deleted_at->format('d/m/Y H:i') : 'N/A' }}
                                            </span>
                                        @else
                                            <span class="text-light">
                                                {{ $movie->created_at ? $movie->created_at->format('d/m/Y H:i') : 'N/A' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($showDeleted)
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button type="button"
                                                        wire:click.once="restoreMovie({{ $movie->id }})"
                                                        class="btn btn-sm btn-success"
                                                        title="Khôi phục">
                                                    <i class="fas fa-undo" style="margin-right: 0"></i>
                                                </button>
                                                <button type="button"
                                                        class="btn btn-sm btn-danger"
                                                        wire:sc-model="forceDeleteMovie({{ $movie->id }})"
                                                        wire:sc-confirm.warning="Bạn có chắc chắn muốn XÓA VĨNH VIỄN phim '{{ $movie->title }}'? Hành động này KHÔNG THỂ HOÀN TÁC!"
                                                        title="Xóa vĩnh viễn">
                                                    <i class="fas fa-trash-alt" style="margin-right: 0"></i>
                                                </button>
                                            </div>
                                        @else
                                            <div class="d-flex gap-2 justify-content-center">
                                                <a href="{{ route('admin.movies.detail', $movie->id) }}"
                                                   class="btn btn-sm btn-info"
                                                   title="Xem chi tiết">
                                                    <i class="fas fa-eye" style="margin-right: 0"></i>
                                                </a>
                                                <a href="{{ route('admin.movies.edit', $movie->id) }}"
                                                    class="btn btn-sm btn-warning"
                                                    title="Chỉnh sửa">
                                                    <i class="fas fa-edit" style="margin-right: 0"></i>
                                                </a>
                                                @if(!$movie->hasActiveShowtimes())
                                                    <button type="button"
                                                            class="btn btn-sm btn-danger"
                                                            wire:sc-model="deleteMovie({{ $movie->id }})"
                                                            wire:sc-confirm.warning="Bạn có chắc chắn muốn xóa phim '{{ $movie->title }}'?"
                                                            title="Xóa">
                                                        <i class="fas fa-trash" style="margin-right: 0"></i>
                                                    </button>
                                                @else
                                                    <button type="button"
                                                            class="btn btn-sm btn-danger"
                                                            wire:sc-alert.error="Không thể xóa phim có suất chiếu trong tương lai"
                                                            wire:sc-model
                                                            title="Xóa">
                                                        <i class="fas fa-trash" style="margin-right: 0"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>
                                                @if($showDeleted)
                                                    Không có phim nào đã xóa
                                                @else
                                                    Không có phim nào
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $movies->links() }}
                </div>
            </div>
        </div>
        <div class="modal fade" id="trailerPreview" wire:ignore>
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="video-container glow-effect mt-1">
                            <div class="video-header">
                                <div class="video-icon">
                                    <i class="fa-brands fa-youtube"></i>
                                </div>
                                <div>
                                    <h3 class="video-title"><span id="title"></span> | Official Trailer</h3>
                                </div>
                            </div>
                            <div class="video-wrapper">
                                <div class="responsive-iframe">
                                    <iframe title="YouTube video player" frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                        allowfullscreen></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng trailer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@script
<script>
    const trailerPreview = document.getElementById('trailerPreview');
    trailerPreview.addEventListener('show.bs.modal', function (event) {
        const itemActive = event.relatedTarget;
        const framePreview = event.target;

        framePreview.querySelector('#title').innerText = itemActive.getAttribute('data-trailer-title');
        framePreview.querySelector('iframe').src = itemActive.getAttribute('data-trailer-url');
    });

    trailerPreview.addEventListener('hidden.bs.modal', function (event) {
        const framePreview = event.target;

        framePreview.querySelector('#title').innerText = "";
        framePreview.querySelector('iframe').src = "";
    });
</script>
@endscript
