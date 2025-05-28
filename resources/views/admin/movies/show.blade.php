@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">🎬 Chi tiết phim: {{ $movie->title }}</h2>
        <a href="{{ route('admin.movies.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại danh sách
        </a>
    </div>

    <div class="row g-4">
        {{-- Poster --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-bold">Poster phim</div>
                <div class="card-body text-center">
                    @if($movie->poster)
                        <img src="{{ asset('storage/' . $movie->poster) }}" alt="{{ $movie->title }}" class="img-fluid rounded">
                    @else
                        <div class="text-muted">Chưa có poster</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Thông tin --}}
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-bold">Thông tin phim</div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="w-25">Tiêu đề</th>
                            <td>{{ $movie->title }}</td>
                        </tr>
                        <tr>
                            <th>Mô tả</th>
                            <td>{{ $movie->description }}</td>
                        </tr>
                        <tr>
                            <th>Thể loại</th>
                            <td>
                                @if($movie->genres->count())
                                    {{ $movie->genres->pluck('name')->implode(', ') }}
                                @else
                                    <span class="text-muted">Không có</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Thời lượng</th>
                            <td>{{ $movie->duration }} phút</td>
                        </tr>
                        <tr>
                            <th>Ngày phát hành</th>
                            <td>{{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Ngày kết thúc</th>
                            <td>{{ $movie->end_date ? \Carbon\Carbon::parse($movie->end_date)->format('d/m/Y') : 'Chưa có' }}</td>
                        </tr>
                        <tr>
                            <th>Trạng thái</th>
                            <td>
                                @switch($movie->status)
                                    @case('coming_soon') <span class="badge bg-warning">Sắp chiếu</span> @break
                                    @case('showing') <span class="badge bg-success">Đang chiếu</span> @break
                                    @case('ended') <span class="badge bg-secondary">Đã kết thúc</span> @break
                                    @default <span class="badge bg-light text-dark">Không xác định</span>
                                @endswitch
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Trailer --}}
    @if($movie->trailer_url)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header fw-bold">Trailer</div>
                    <div class="card-body text-center">
                        @php
                            $url = $movie->trailer_url;
                            $youtubeId = null;
                            if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^\&\?\/]+)/', $url, $matches)) {
                                $youtubeId = $matches[1];
                            }
                        @endphp

                        @if($youtubeId)
                            <div class="ratio ratio-16x9">
                                <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}" frameborder="0" allowfullscreen></iframe>
                            </div>
                        @elseif(Str::endsWith($url, ['.mp4']))
                            <video width="100%" controls>
                                <source src="{{ $url }}" type="video/mp4">
                                Trình duyệt không hỗ trợ video.
                            </video>
                        @else
                            <a href="{{ $url }}" target="_blank">Xem trailer</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
