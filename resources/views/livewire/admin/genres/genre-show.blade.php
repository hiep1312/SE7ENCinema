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

    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Chi tiết thể loại: {{ $genre->name }}</h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card bg-dark mb-4">
                    <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="my-1">Thông tin thể loại</h5>
                    </div>
                    <div class="card-body bg-dark">
                        <table class="table table-borderless text-light">
                            <tr>
                                <td><strong class="text-warning">Tên thể loại:</strong></td>
                                <td>{{ $genre->name }}</td>
                            </tr>
                            <tr>
                                <td><strong class="text-warning">Mô tả:</strong></td>
                                <td>{{ $genre->description ?: 'Không có mô tả' }}</td>
                            </tr>
                            <tr>
                                <td><strong class="text-warning">Ngày tạo:</strong></td>
                                <td>{{ $genre->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong class="text-warning">Ngày cập nhật:</strong></td>
                                <td>{{ $genre->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card bg-dark">
                    <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="my-1">Danh sách phim áp dụng</h5>
                    </div>
                    <div class="card-body bg-dark">
                        @if ($genre->movies->isEmpty())
                            <p class="text-muted text-center py-3">Chưa có phim nào thuộc thể loại này.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-dark table-striped table-hover">
                                    <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        <tr>
                                            <th class="text-light">Ảnh</th>
                                            <th class="text-light">Tiêu đề</th>
                                            <th class="text-light">Thời lượng</th>
                                            <th class="text-light">Mô tả</th>
                                            <th class="text-light">Giá vé</th>
                                            <th class="text-light">Ngày phát hành</th>
                                            <th class="text-light">Ngày kết thúc</th>
                                            <th class="text-light">Trạng thái</th>
                                            <th class="text-light">Trailer</th>
                                            <th class="text-light">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($genre->movies as $movie)
                                            <tr>
                                                <td>
                                                    @if ($movie->poster)
                                                        <img src="{{ asset('storage/' . $movie->poster) }}" alt="{{ $movie->title }}" class="img-thumbnail" style="width: 80px; height: auto;">
                                                    @else
                                                        <img src="https://via.placeholder.com/80x120?text=No+Image" alt="No Image" class="img-thumbnail" style="width: 80px; height: auto;">
                                                    @endif
                                                </td>
                                                <td>{{ $movie->title }}</td>
                                                <td>{{ $movie->duration ? $movie->duration . ' phút' : 'Chưa xác định' }}</td>
                                                <td>{{ $movie->description ? Str::limit($movie->description, 50) : 'Không có mô tả' }}</td>
                                                <td>{{ !is_null($movie->price) ? number_format($movie->price, 0, ',', '.') . ' VND' : 'Chưa xác định' }}</td>
                                                <td>{{ $movie->release_date ? \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') : 'Chưa xác định' }}</td>
                                                <td>{{ $movie->end_date ? \Carbon\Carbon::parse($movie->end_date)->format('d/m/Y') : 'Chưa xác định' }}</td>
                                                <td>
                                                    @if ($movie->status == 'upcoming')
                                                        <span class="badge bg-warning">Sắp chiếu</span>
                                                    @elseif ($movie->status == 'showing')
                                                        <span class="badge bg-success">Đang chiếu</span>
                                                    @else
                                                        <span class="badge bg-danger">Ngừng chiếu</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($movie->trailer_url)
                                                        <a href="#" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#trailerModal{{ $movie->id }}" title="Xem trailer">
                                                            <i class="fas fa-play"></i>
                                                        </a>
                                                    @else
                                                        Không có trailer
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.movies.show', $movie->id) }}" class="btn btn-sm btn-info" title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.movies.edit', $movie->id) }}" class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal cho mỗi trailer -->
        @foreach ($genre->movies as $movie)
            @if ($movie->trailer_url)
                @php
                    $trailerUrl = $movie->trailer_url;
                    if (strpos($trailerUrl, 'watch?v=') !== false) {
                        $trailerUrl = str_replace('watch?v=', 'embed/', $trailerUrl);
                        $trailerUrl = strtok($trailerUrl, '&');
                    }
                @endphp
                <div class="modal fade" id="trailerModal{{ $movie->id }}" tabindex="-1" aria-labelledby="trailerModalLabel{{ $movie->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content bg-dark">
                            <div class="modal-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5 class="modal-title" id="trailerModalLabel{{ $movie->id }}">Trailer: {{ $movie->title }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="ratio ratio-16x9">
                                    <iframe id="trailerIframe{{ $movie->id }}" src="{{ $trailerUrl }}" title="Trailer {{ $movie->title }}" allowfullscreen></iframe>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="#" class="btn btn-outline-secondary" data-bs-dismiss="modal" title="Đóng">
                                    <i class="fas fa-times"></i> Đóng
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

        <br>
        <a href="{{ route('admin.genres.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('show.bs.modal', function(event) {
                console.log('Modal opened: ', event.target.id);
            });
            document.addEventListener('hidden.bs.modal', function(event) {
                const modal = event.target;
                const iframe = modal.querySelector('iframe');
                if (iframe) {
                    const src = iframe.src;
                    iframe.src = '';
                    setTimeout(() => {
                        iframe.src = src;
                    }, 100);
                    console.log('Video stopped for modal: ', modal.id);
                }
            });
        </script>
    @endpush
</div>