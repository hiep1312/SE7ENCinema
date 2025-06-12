<div class="container mt-4">
    <h1>Thùng rác - Danh sách phim đã xóa</h1>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <a href="{{ route('admin.movies.index') }}" class="btn btn-primary">← Quay lại danh sách phim</a>
        
        <div class="d-flex gap-2 align-items-center">
            <input wire:model.live.debounce.500ms="title" type="text" class="form-control form-control-sm" placeholder="Tiêu đề">
            <select wire:model.live="genre_id" class="form-select form-select-sm">
                <option value="">-- Thể loại --</option>
                @foreach($genres as $genre)
                    <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                @endforeach
            </select>
            <input wire:model.live="deleted_at_from" type="date" class="form-control form-control-sm" title="Ngày xóa từ">
            <input wire:model.live="deleted_at_to" type="date" class="form-control form-control-sm" title="Ngày xóa đến">
            <a href="{{ route('admin.movies.test') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
        </div>
    </div>

    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th>STT</th>
                <th>Poster</th>
                <th>Tiêu đề</th>
                <th>Thể loại</th>
                <th>Đạo diễn</th>
                <th>Diễn viên</th>
                <th>Độ tuổi hạn chế</th>
                <th>Định dạng</th>
                <th>Giá vé</th>
                <th>Thời lượng (phút)</th>
                <th>Ngày xóa</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($movies as $movie)
                <tr>
                    <td>{{ ($movies->currentPage() - 1) * $movies->perPage() + $loop->iteration }}</td>
                    <td style="width: 100px;">
                        @if($movie->poster)
                            <img src="{{ asset('storage/' . $movie->poster) }}" alt="{{ $movie->title }}" class="img-fluid rounded shadow" style="max-width: 100px;">
                        @else
                            <span>Chưa có poster</span>
                        @endif
                    </td>
                    <td>{{ $movie->title }}</td>
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
                    <td>{{ $movie->deleted_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-success mb-1" title="Khôi phục" data-bs-toggle="modal" data-bs-target="#restoreModal{{ $movie->id }}">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger mb-1" title="Xóa vĩnh viễn" data-bs-toggle="modal" data-bs-target="#forceDeleteModal{{ $movie->id }}">
                            <i class="bi bi-trash"></i>
                        </button>

                        <!-- Restore Confirmation Modal -->
                        <div class="modal fade" id="restoreModal{{ $movie->id }}" tabindex="-1" aria-labelledby="restoreModalLabel{{ $movie->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="restoreModalLabel{{ $movie->id }}">Xác nhận khôi phục phim</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                    </div>
                                    <div class="modal-body">
                                        Bạn có muốn khôi phục phim <strong>{{ $movie->title }}</strong> từ thùng rác?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                        <button type="button" class="btn btn-success" wire:click="restore({{ $movie->id }})" data-bs-dismiss="modal">Khôi phục</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Force Delete Confirmation Modal -->
                        <div class="modal fade" id="forceDeleteModal{{ $movie->id }}" tabindex="-1" aria-labelledby="forceDeleteModalLabel{{ $movie->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="forceDeleteModalLabel{{ $movie->id }}">Xác nhận xóa vĩnh viễn</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                    </div>
                                    <div class="modal-body">
                                        Bạn có chắc muốn xóa vĩnh viễn phim <strong>{{ $movie->title }}</strong>?.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                        <button type="button" class="btn btn-danger" wire:click="forceDelete({{ $movie->id }})" data-bs-dismiss="modal">Xóa vĩnh viễn</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center">Không có phim nào trong thùng rác.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $movies->links('pagination::bootstrap-5') }}
    </div>
</div>