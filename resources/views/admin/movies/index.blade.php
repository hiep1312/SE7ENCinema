@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h1>Danh sách phim</h1>

    <a href="{{ route('admin.movies.trash') }}" class="btn btn-outline-secondary mb-3">🗑️ Xem thùng rác</a>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Lọc phim --}}
    <form method="GET" action="{{ route('admin.movies.index') }}" class="mb-3">
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tiêu đề..." value="{{ old('search', $search) }}">
            </div>

            <div class="col-md-3">
                <select name="genre_id" class="form-select">
                    <option value="">-- Tất cả thể loại --</option>
                    @foreach($genres as $genre)
                    <option value="{{ $genre->id }}" {{ $genreId == $genre->id ? 'selected' : '' }}>
                        {{ $genre->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="coming_soon" {{ $status == 'coming_soon' ? 'selected' : '' }}>Sắp chiếu</option>
                    <option value="showing" {{ $status == 'showing' ? 'selected' : '' }}>Đang chiếu</option>
                    <option value="ended" {{ $status == 'ended' ? 'selected' : '' }}>Đã kết thúc</option>
                </select>
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary w-100">Lọc</button>
            </div>
        </div>
    </form>

    <a href="{{ route('admin.movies.create') }}" class="btn btn-success mb-3">Thêm phim mới</a>

    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th>STT</th>
                <th>Poster & Trailer</th>
                <th>Tiêu đề</th>
                <th>Mô tả</th>
                <th>Thể loại</th>
                <th>Thời lượng (phút)</th>
                <th>Ngày phát hành</th>
                <th>Ngày kết thúc</th>
                <th>Trạng thái</th>
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

                    {{-- Modal Trailer --}}
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
                                    $url = $movie->trailer_url;
                                    $youtubeId = null;
                                    if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^\&\?\/]+)/', $url, $matches)) {
                                    $youtubeId = $matches[1];
                                    }
                                    @endphp

                                    @if($youtubeId)
                                    <iframe id="trailerFrame{{ $movie->id }}" src="https://www.youtube.com/embed/{{ $youtubeId }}?rel=0" allowfullscreen></iframe>
                                    @elseif(Str::endsWith($url, ['.mp4']))
                                    <video id="trailerVideo{{ $movie->id }}" controls>
                                        <source src="{{ $url }}" type="video/mp4">
                                    </video>
                                    @else
                                    <a href="{{ $url }}" target="_blank">Xem trailer</a>
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
                    <form action="{{ route('admin.movies.updateStatus', $movie->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="coming_soon" {{ $movie->status == 'coming_soon' ? 'selected' : '' }}>Sắp chiếu</option>
                            <option value="showing" {{ $movie->status == 'showing' ? 'selected' : '' }}>Đang chiếu</option>
                            <option value="ended" {{ $movie->status == 'ended' ? 'selected' : '' }}>Đã kết thúc</option>
                        </select>
                    </form>
                </td>
                <td>
                    <a href="{{ route('admin.movies.show', $movie->id) }}" class="btn btn-sm btn-info mb-1" title="Xem chi tiết">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('admin.movies.edit', $movie->id) }}" class="btn btn-sm btn-warning mb-1" title="Sửa">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <form action="{{ route('admin.movies.destroy', $movie->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc muốn xóa phim này?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" type="submit" title="Xóa">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">Không có phim nào</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $movies->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>

{{-- JS dừng trailer khi đóng modal --}}
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
@endsection
