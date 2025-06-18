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
            <h2 class="text-light fw-bold mb-0">Thùng rác - Danh sách phim đã xóa</h2>
            <a href="{{ route('admin.movies.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách phim
            </a>
        </div>

        <div class="card bg-dark border-0 shadow-sm">
            <div class="card-header bg-gradient text-light p-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 0.5rem 0.5rem 0 0;">
                <div class="row g-3 align-items-center">
                    <div class="col-md-3 col-lg-2">
                        <input wire:model.live.debounce.500ms="title" type="text" class="form-control bg-dark text-light border-0" placeholder="Tiêu đề..." style="border-radius: 0.375rem;">
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <select wire:model.live="genre_id" class="form-select bg-dark text-light border-0" style="border-radius: 0.375rem;">
                            <option value="">-- Thể loại --</option>
                            @foreach($genres as $genre)
                                <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <input wire:model.live="deleted_at_from" type="date" class="form-control bg-dark text-light border-0" title="Ngày xóa từ" style="border-radius: 0.375rem;">
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <input wire:model.live="deleted_at_to" type="date" class="form-control bg-dark text-light border-0" title="Ngày xóa đến" style="border-radius: 0.375rem;">
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
                                <th class="text-center text-light fw-bold" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">Poster</th>
                                <th class="text-center text-light fw-bold" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">Tiêu đề</th>
                                <th class="text-center text-light fw-bold" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">Thể loại</th>
                                <th class="text-center text-light fw-bold" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">Đạo diễn</th>
                                <th class="text-center text-light fw-bold" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">Diễn viên</th>
                                <th class="text-center text-light fw-bold" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">Độ tuổi hạn chế</th>
                                <th class="text-center text-light fw-bold" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">Định dạng</th>
                                <th class="text-center text-light fw-bold" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">Giá vé</th>
                                <th class="text-center text-light fw-bold" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">Thời lượng (phút)</th>
                                <th class="text-center text-light fw-bold" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">Ngày xóa</th>
                                <th class="text-center text-light fw-bold" style="border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($movies as $movie)
                                <tr wire:key="{{ $movie->id }}" style="transition: background-color 0.2s ease;">
                                    <td class="text-center fw-bold" style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">{{ ($movies->currentPage() - 1) * $movies->perPage() + $loop->iteration }}</td>
                                    <td style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">
                                        <div class="mt-1 overflow-auto d-block text-center" style="max-height: 100px; width: 100px;">
                                            <img src="{{ asset('storage/' . ($movie->poster ?? '404.webp')) }}" alt="{{ $movie->title }}" class="rounded shadow" style="width: 100%; height: auto; object-fit: cover;">
                                        </div>
                                    </td>
                                    <td style="border-right: 1px solid #444; border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">
                                        <strong class="text-light">{{ $movie->title }}</strong>
                                        <span class="badge bg-danger ms-1" style="font-size: 0.85rem; padding: 0.4rem 0.6rem;">Đã xóa</span>
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
                                        <span class="text-light">{{ $movie->deleted_at->format('d/m/Y H:i') }}</span>
                                    </td>
                                    <td style="border-bottom: 1px solid #444; vertical-align: middle; padding: 0.75rem;">
                                        <div class="d-flex gap-3 justify-content-center">
                                            <button type="button" class="btn btn-sm btn-success" title="Khôi phục" data-bs-toggle="modal" data-bs-target="#restoreModal{{ $movie->id }}" style="padding: 0.4rem 0.6rem;">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" title="Xóa vĩnh viễn" data-bs-toggle="modal" data-bs-target="#forceDeleteModal{{ $movie->id }}" style="padding: 0.4rem 0.6rem;">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>

                                        <!-- Restore Confirmation Modal -->
                                        <div class="modal fade" id="restoreModal{{ $movie->id }}" tabindex="-1" aria-labelledby="restoreModalLabel{{ $movie->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content bg-dark" style="border-radius: 10px; overflow: hidden;">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title text-light" id="restoreModalLabel{{ $movie->id }}">Xác nhận khôi phục phim</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                                    </div>
                                                    <div class="modal-body text-light">
                                                        Bạn có muốn khôi phục phim <strong>{{ $movie->title }}</strong> từ thùng rác?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                                                        <button type="button" class="btn btn-success" wire:click="restore({{ $movie->id }})" data-bs-dismiss="modal">Khôi phục</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Force Delete Confirmation Modal -->
                                        <div class="modal fade" id="forceDeleteModal{{ $movie->id }}" tabindex="-1" aria-labelledby="forceDeleteModalLabel{{ $movie->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content bg-dark" style="border-radius: 10px; overflow: hidden;">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title text-light" id="forceDeleteModalLabel{{ $movie->id }}">Xác nhận xóa vĩnh viễn</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                                    </div>
                                                    <div class="modal-body text-light">
                                                        Bạn có chắc muốn xóa vĩnh viễn phim <strong>{{ $movie->title }}</strong>?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                                                        <button type="button" class="btn btn-danger" wire:click="forceDelete({{ $movie->id }})" data-bs-dismiss="modal">Xóa vĩnh viễn</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center py-4" style="vertical-align: middle; padding: 0.75rem;">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>Không có phim nào trong thùng rác.</p>
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
</div>