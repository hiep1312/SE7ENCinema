<div class="container mt-4">
    <h1>Danh sách phim</h1>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <a href="{{ route('admin.test') }}" class="btn btn-outline-secondary mb-3">🗑️ Xem thùng rác</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="mb-3">
        <div class="row g-2">
            <div class="col-md-4">
                <input wire:model.live.debounce.500ms="search" type="text" class="form-control" placeholder="Tìm kiếm theo tiêu đề...">
            </div>
            <div class="col-md-3">
                <select wire:model.live="genreId" class="form-select">
                    <option value="">-- Tất cả thể loại --</option>
                    @foreach($genres as $genre)
                        <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select wire:model.live="status" class="form-select">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="coming_soon">Sắp chiếu</option>
                    <option value="showing">Đang chiếu</option>
                    <option value="ended">Đã kết thúc</option>
                </select>
            </div>
        </div>
    </div>

    <a href="{{ route('admin.create') }}" class="btn btn-success mb-3">Thêm phim mới</a>

    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th>STT</th>
                <th>Poster & Trailer</th>
                <th>Tiêu đề</th>
                <th>Mô tả</th>
                <th>Thể loại</th>
                <th>Đạo diễn</th>
                <th>Diễn viên</th>
                <th>Độ tuổi hạn chế</th>
                <th>Định dạng</th>
                <th>Giá vé</th>
                <th>Thời lượng (phút)</th>
                <th>Ngày phát hành</th>
                <th>Ngày kết thúc</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($movies as $movie)
                <tr>
                    <td>{{ ($movies->currentPage() - 1) * $movies->perPage() + $loop->iteration }}</td>
                    <td>
                        @if($movie->poster)
                            <div class="poster-wrapper">
                                <img src="{{ asset('storage/' . $movie->poster) }}" alt="{{ $movie->title }}" class="img-fluid rounded shadow">
                                @if($movie->trailer_url)
                                    <button type="button" class="btn-play" data-bs-toggle="modal" data-bs-target="#trailerModal{{ $movie->id }}">
                                        <i class="bi bi-play-circle-fill"></i>
                                    </button>
                                @endif
                            </div>
                            @if($movie->trailer_url)
                                <div class="modal fade" id="trailerModal{{ $movie->id }}" tabindex="-1" aria-labelledby="trailerModalLabel{{ $movie->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Trailer: {{ $movie->title }}</h5>
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
                                                    <a href="{{ $movie->trailer_url }}" target="_blank">Xem trailer</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            <span>Chưa có poster</span>
                        @endif
                    </td>
                    <td>{{ $movie->title }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($movie->description, 100) }}</td>
                    <td>
                        @if($movie->genres && $movie->genres->count())
                            {{ $movie->genres->pluck('name')->implode(', ') }}
                        @else
                            <span>Không có</span>
                        @endif
                    </td>
                    <td>{{ $movie->director ?? 'Chưa có' }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($movie->actors ?? 'Chưa có', 50) }}</td>
                    <td>
                        @switch($movie->age_restriction)
                            @case('P') Tất cả @break
                            @case('K') K @break
                            @case('T13') T13 @break
                            @case('T16') T16 @break
                            @case('T18') T18 @break
                            @case('C') C @break
                            @default Không xác định
                        @endswitch
                    </td>
                    <td>{{ $movie->format }}</td>
                    <td>{{ number_format($movie->price, 0, ',', '.') }} VNĐ</td>
                    <td>{{ $movie->duration }}</td>
                    <td>{{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}</td>
                    <td>
                        @if($movie->end_date)
                            {{ \Carbon\Carbon::parse($movie->end_date)->format('d/m/Y') }}
                        @else
                            <span>Chưa có</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.show', $movie->id) }}" class="btn mb-1" title="Xem chi tiết">
                            <i class="bi bi-eye" style="color: #00f;"></i>
                        </a>
                        <a href="{{ route('admin.edit', $movie->id) }}" class="btn mb-1" title="Sửa">
                            <i class="bi bi-pencil-square" style="color: #ff0;"></i>
                        </a>
                        <button type="button" class="btn mb-1" title="Xóa mềm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $movie->id }}">
                            <i class="bi bi-trash" style="color: #f00;"></i>
                        </button>

                        <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="deleteModal{{ $movie->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $movie->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel{{ $movie->id }}">Xác nhận xóa phim</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                    </div>
                                    <div class="modal-body">
                                        Bạn có chắc muốn chuyển phim <strong>{{ $movie->title }}</strong> vào thùng rác?.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                        <button type="button" class="btn btn-danger" wire:click="delete({{ $movie->id }})" data-bs-dismiss="modal">Xóa</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="14" class="text-center">Không có phim nào</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $movies->links('pagination::bootstrap-5') }}
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