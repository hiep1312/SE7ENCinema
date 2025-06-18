<div>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light fw-bold mb-0">Danh sách phim</h2>
            <div>
                <a href="{{ route('admin.movies.create') }}" class="btn btn-success me-2">
                    <i class="fas fa-plus me-1"></i> Thêm phim mới
                </a>
                <a href="{{ route('admin.movies.test') }}" class="btn btn-outline-danger">
                    <i class="fas fa-trash me-1"></i> Xem thùng rác
                </a>
            </div>
        </div>

        <div class="card bg-dark border-0 shadow-sm">
            <div class="card-header bg-gradient text-light p-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 0.5rem 0.5rem 0 0;">
                <div class="row g-3 align-items-center">
                    <div class="col-md-4 col-lg-3">
                        <div class="input-group">
                            <input wire:model.live.debounce.500ms="search" type="text" class="form-control bg-dark text-light border-0" placeholder="Tìm kiếm theo tiêu đề..." style="border-radius: 0.375rem;">
                            <span class="input-group-text text-light" style="background-color: #2a2a2a; border-color: #444; border-radius: 0.375rem;">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <select wire:model.live="genreId" class="form-select bg-dark text-light border-0" style="border-radius: 0.375rem;">
                            <option value="">-- Tất cả thể loại --</option>
                            @foreach($genres as $genre)
                                <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <select wire:model.live="status" class="form-select bg-dark text-light border-0" style="border-radius: 0.375rem;">
                            <option value="">-- Tất cả trạng thái --</option>
                            <option value="coming_soon">Sắp chiếu</option>
                            <option value="showing">Đang chiếu</option>
                            <option value="ended">Đã kết thúc</option>
                        </select>
                    </div>
                    <div class="col-md-2 col-lg-2">
                        <button wire:click="resetFilters" class="btn btn-outline-warning w-100" style="border-radius: 0.375rem;">
                            <i class="fas fa-refresh me-1"></i> Reset
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body bg-dark p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover mb-0" style="border-collapse: separate; border-spacing: 0;">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <tr>
                                <th class="text-center text-light fw-bold" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">STT</th>
                                <th class="text-center text-light fw-bold" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">Poster & Trailer</th>
                                <th class="text-center text-light fw-bold" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">Tiêu đề</th>
                                <th class="text-center text-light fw-bold" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">Mô tả</th>
                                <th class="text-center text-light fw-bold" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">Thể loại</th>
                                <th class="text-center text-light fw-bold" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">Đạo diễn</th>
                                <th class="text-center text-light fw-bold" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">Diễn viên</th>
                                <th class="text-center text-light fw-bold" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">Độ tuổi hạn chế</th>
                                <th class="text-center text-light fw-bold" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">Định dạng</th>
                                <th class="text-center text-light fw-bold" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">Giá vé</th>
                                <th class="text-center text-light fw-bold" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">Thời lượng (phút)</th>
                                <th class="text-center text-light fw-bold" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">Ngày phát hành</th>
                                <th class="text-center text-light fw-bold" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">Ngày kết thúc</th>
                                <th class="text-center text-light fw-bold" style="border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($movies as $movie)
                                <tr wire:key="{{ $movie->id }}" style="transition: background-color 0.2s ease;">
                                    <td class="text-center fw-bold" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">{{ ($movies->currentPage() - 1) * $movies->perPage() + $loop->iteration }}</td>
                                    <td style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">
                                        @if($movie->poster)
                                            <div class="mt-1 overflow-auto d-block text-center" style="max-height: 100px; width: 100px; position: relative;">
                                                <img src="{{ asset('storage/' . $movie->poster) }}" alt="{{ $movie->title }}" class="img-fluid rounded shadow" style="width: 100%; height: auto; object-fit: cover;">
                                                @if($movie->trailer_url)
                                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#trailerModal{{ $movie->id }}" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); padding: 0.4rem 0.6rem; background: rgba(0, 0, 0, 0.5); border: none; border-radius: 50%;">
                                                        <i class="bi bi-play-circle-fill"></i>
                                                    </button>
                                                @endif
                                            </div>
                                            @if($movie->trailer_url)
                                                <div class="modal fade" id="trailerModal{{ $movie->id }}" tabindex="-1" aria-labelledby="trailerModalLabel{{ $movie->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                                        <div class="modal-content bg-dark" style="border-radius: 10px; overflow: hidden;">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title text-light">Trailer: {{ $movie->title }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                                            </div>
                                                            <div class="modal-body ratio ratio-16x9">
                                                                @php
                                                                    $youtubeId = null;
                                                                    if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^\&\?\/]+)/', $movie->trailer_url, $matches)) {
                                                                        $youtubeId = $matches[1];
                                                                    }
                                                                @endphp
                                                                @if($youtubeId)
                                                                    <iframe id="trailerFrame{{ $movie->id }}" src="https://www.youtube.com/embed/{{ $youtubeId }}?rel=0" allowfullscreen></iframe>
                                                                @elseif(Str::endsWith($movie->trailer_url, ['.mp4']))
                                                                    <video id="trailerVideo{{ $movie->id }}" controls>
                                                                        <source src="{{ $movie->trailer_url }}" type="video/mp4">
                                                                    </video>
                                                                @else
                                                                    <a href="{{ $movie->trailer_url }}" target="_blank" class="btn btn-primary">Xem trailer</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-muted">Chưa có poster</span>
                                        @endif
                                    </td>
                                    <td style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">
                                        <strong class="text-light">{{ $movie->title }}</strong>
                                    </td>
                                    <td class="text-center" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">
                                        <span class="text-light text-wrap">{{ \Illuminate\Support\Str::limit($movie->description, 100) }}</span>
                                    </td>
                                    <td class="text-center" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">
                                        @if($movie->genres && $movie->genres->count())
                                            <span class="text-light">{{ $movie->genres->pluck('name')->implode(', ') }}</span>
                                        @else
                                            <span class="text-muted">Không có</span>
                                        @endif
                                    </td>
                                    <td class="text-center" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">
                                        <span class="text-light">{{ $movie->director ?? 'Chưa có' }}</span>
                                    </td>
                                    <td class="text-center" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">
                                        <span class="text-light text-wrap">{{ \Illuminate\Support\Str::limit($movie->actors ?? 'Chưa có', 50) }}</span>
                                    </td>
                                    <td class="text-center" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">
                                        @switch($movie->age_restriction)
                                            @case('P') <span class="badge bg-success" style="font-size: 0.85rem; padding: 0.4rem 0.6rem;">Tất cả</span> @break
                                            @case('K') <span class="badge bg-info" style="font-size: 0.85rem; padding: 0.4rem 0.6rem;">K</span> @break
                                            @case('T13') <span class="badge bg-warning" style="font-size: 0.85rem; padding: 0.4rem 0.6rem;">T13</span> @break
                                            @case('T16') <span class="badge bg-warning" style="font-size: 0.85rem; padding: 0.4rem 0.6rem;">T16</span> @break
                                            @case('T18') <span class="badge bg-danger" style="font-size: 0.85rem; padding: 0.4rem 0.6rem;">T18</span> @break
                                            @case('C') <span class="badge bg-danger" style="font-size: 0.85rem; padding: 0.4rem 0.6rem;">C</span> @break
                                            @default <span class="badge bg-secondary" style="font-size: 0.85rem; padding: 0.4rem 0.6rem;">Không xác định</span>
                                        @endswitch
                                    </td>
                                    <td class="text-center" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">
                                        <span class="badge bg-info" style="font-size: 0.85rem; padding: 0.4rem 0.6rem;">{{ $movie->format }}</span>
                                    </td>
                                    <td class="text-center" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">
                                        {{ number_format($movie->price, 0, ',', '.') }} VNĐ
                                    </td>
                                    <td class="text-center" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">
                                        {{ $movie->duration }}
                                    </td>
                                    <td class="text-center" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">
                                        {{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}
                                    </td>
                                    <td class="text-center" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">
                                        @if($movie->end_date)
                                            {{ \Carbon\Carbon::parse($movie->end_date)->format('d/m/Y') }}
                                        @else
                                            <span class="text-muted">Chưa có</span>
                                        @endif
                                    </td>
                                    <td style="border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">
                                        <div class="d-flex gap-3 justify-content-center">
                                            <a href="{{ route('admin.movies.show', $movie->id) }}" class="btn btn-sm btn-info" title="Xem chi tiết" style="padding: 0.4rem 0.6rem;">
                                                <i class="fas fa-eye" style="margin-right: 0"></i>
                                            </a>
                                            <a href="{{ route('admin.movies.edit', $movie->id) }}" class="btn btn-sm btn-warning" title="Sửa" style="padding: 0.4rem 0.6rem;">
                                                <i class="fas fa-edit" style="margin-right: 0"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $movie->id }}" title="Xóa" style="padding: 0.4rem 0.6rem;">
                                                <i class="fas fa-trash" style="margin-right: 0"></i>
                                            </button>
                                        </div>
                                        <!-- Delete Confirmation Modal -->
                                        <div class="modal fade" id="deleteModal{{ $movie->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $movie->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content bg-dark" style="border-radius: 10px; overflow: hidden;">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title text-light" id="deleteModalLabel{{ $movie->id }}">Xác nhận xóa phim</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                                    </div>
                                                    <div class="modal-body text-light">
                                                        Bạn có chắc muốn chuyển phim <strong>{{ $movie->title }}</strong> vào thùng rác?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                                                        <button type="button" class="btn btn-danger" wire:click="delete({{ $movie->id }})" data-bs-dismiss="modal">Xóa</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="text-center py-4" style="vertical-align: middle; padding: 0.75rem;">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>Không có phim nào</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 px-3 pb-3">
                    {{ $movies->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                modal.addEventListener('hidden.bs.modal', function() {
                    const iframe = modal.querySelector('iframe');
                    if (iframe) {
                        const src = iframe.src;
                        iframe.src = '';
                        iframe.src = src;
                    }
                    const video = modal.querySelector('video');
                    if (video) {
                        video.pause();
                        video.currentTime = 0;
                    }
                });
            });
        });
    </script>
</div>
