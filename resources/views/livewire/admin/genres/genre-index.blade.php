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
            <h2 class="text-light">Quản lý thể loại</h2>
            <a href="{{ route('admin.genres.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Thêm thể loại mới
            </a>
        </div>
        <div class="card bg-dark">
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="row g-3 align-items-center">
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text" wire:model.live.debounce.300ms="search" class="form-control bg-dark text-light" placeholder="Nhập chữ cái đầu (VD: a, A) để tìm thể loại..." maxlength="1">
                            <span class="input-group-text bg-dark text-light">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select wire:model.live="genreFilter" class="form-select bg-dark text-light">
                            <option value="">Tất cả thể loại</option>
                            @foreach ($genres as $genre)
                                <option value="{{ $genre->name }}">{{ $genre->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select wire:model.live="sortOrder" class="form-select bg-dark text-light">
                            <option value="">Sắp xếp theo</option>
                            <option value="newest">Thể loại mới nhất</option>
                            <option value="oldest">Thể loại cũ nhất</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button wire:click="resetFilters" class="btn btn-outline-warning w-100" wire:loading.attr="disabled">
                            <i class="fas fa-refresh"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body bg-dark">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover" style="border-collapse: separate; border-spacing: 0;">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <tr>
                                <th class="text-light" style="border-right: 1px solid #444; padding: 8px;">STT</th>
                                <th class="text-light" style="border-right: 1px solid #444; padding: 8px;">Tên thể loại</th>
                                <th class="text-light" style="border-right: 1px solid #444; padding: 8px;">Số lượng phim</th>
                                <th class="text-light" style="border-right: 1px solid #444; padding: 8px;">Ngày tạo</th>
                                <th class="text-light" style="border-right: 1px solid #444; padding: 8px;">Mô tả</th>
                                <th class="text-light text-center" style="padding: 8px;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($genres as $genre)
                                <tr wire:key="genre-{{ $genre->id }}">
                                    <td style="border-right: 1px solid #444; padding: 8px;">{{ (($genres->currentPage() - 1) * $genres->perPage()) + $loop->iteration }}</td>
                                    <td style="border-right: 1px solid #444; padding: 8px;">{{ $genre->name }}</td>
                                    <td style="border-right: 1px solid #444; padding: 8px;">{{ $genre->movies_count }}</td>
                                    <td style="border-right: 1px solid #444; padding: 8px;">{{ $genre->created_at ? $genre->created_at->format('d/m/Y H:i') : 'Chưa xác định' }}</td>
                                    <td style="border-right: 1px solid #444; padding: 8px;">{{ Str::limit($genre->description ?? 'Không có mô tả', 50) }}</td>
                                    <td class="text-center" style="padding: 8px;">
                                        <div class="d-flex gap-3 justify-content-center">
                                            <a href="{{ route('admin.genres.show', $genre->id) }}" class="btn btn-sm btn-info" title="Xem chi tiết">
                                                <i class="fas fa-eye" style="margin-right: 0"></i>
                                            </a>
                                            <a href="{{ route('admin.genres.edit', $genre->id) }}" class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                                <i class="fas fa-edit" style="margin-right: 0"></i>
                                            </a>
                                            <button type="button" wire:sc-model="deleteGenre({{ $genre->id }})" wire:sc-confirm.warning="Bạn có chắc chắn muốn xóa thể loại này? Hành động này không thể hoàn tác." class="btn btn-sm btn-danger" title="Xóa" wire:loading.attr="disabled" wire:key="delete-{{ $genre->id }}">
                                                <i class="fas fa-trash" style="margin-right: 0"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>Không có thể loại nào.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $genres->links() }}
                </div>
            </div>
        </div>
        <!-- Modal xác nhận xóa -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content bg-dark text-light">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa thể loại</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Bạn có chắc chắn muốn xóa thể loại này? Hành động này không thể hoàn tác.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" onclick="">
                            <i class="fas fa-times"></i> Hủy
                        </button>
                        <button type="button" class="btn btn-danger" wire:click="deleteGenre" wire:loading.attr="disabled">
                            <span wire:loading wire:target="deleteGenre">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang xóa...
                            </span>
                            <span wire:loading.remove wire:target="deleteGenre">
                                <i class="fas fa-trash"></i> Xóa
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>