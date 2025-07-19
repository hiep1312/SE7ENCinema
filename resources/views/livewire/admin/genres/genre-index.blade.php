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
            <h2 class="text-light">Quản lý thể loại</h2>
            <div>
                <a href="{{ route('admin.genres.create') }}" class="btn btn-success me-2">
                    <i class="fas fa-plus me-1"></i>Thêm thể loại
                </a>
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
                                   placeholder="Tìm kiếm thể loại...">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2">
                        <select wire:model.live="movieFilter" class="form-select bg-dark text-light">
                            <option value="">Tất cả phim</option>
                            @foreach ($movies as $movie)
                                <option value="{{ $movie->id }}" wire:key="movie-{{ $movie->id }}">{{ $movie->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Reset filters -->
                    <div class="col-md-2">
                        <button wire:click="resetFilters" class="btn btn-outline-warning">
                            <i class="fas fa-refresh me-1"></i>Reset
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body bg-dark">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <tr>
                                <th class="text-center text-light">STT</th>
                                <th class="text-center text-light">Tên thể loại</th>
                                <th class="text-center text-light">Mô tả</th>
                                <th class="text-center text-light">Phim liên kết</th>
                                <th class="text-center text-light">Ngày tạo</th>
                                <th class="text-center text-light">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($genres as $genre)
                                <tr>
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        <strong class="text-light">{{ $genre->name }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-wrap text-muted lh-base" style="margin-bottom: 0;">{{ $genre->description ?? 'Không có mô tả' }}</p>
                                    </td>
                                    <td class="text-center">
                                        @if($genre->movies->isNotEmpty())
                                            @php $showAll = (isset($showAllMovies[$genre->id]) && $showAllMovies[$genre->id]); @endphp
                                            <div class="text-wrap" style="max-width: 250px;">
                                                @foreach ($showAll ? $genre->movies : $genre->movies->take(3) as $movie)
                                                    <span class="cast-member">{{ $movie->title }}</span>
                                                @endforeach
                                            </div>
                                            @if($genre->movies->count() > 3)
                                                <button class="btn btn-expand {{ !$showAll ?: 'btn-collapse' }}" type="button" wire:click="$toggle('showAllMovies.{{ $genre->id }}')">
                                                    <i class="fas fa-chevron-{{ $showAll ? 'up' : 'down' }} me-1"></i>{{ $showAll ? 'Thu gọn' : 'Xem thêm' }}
                                                </button>
                                            @endif
                                        @else
                                            <span class="text-muted">Không liên kết phim nào</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="text-light">
                                            {{ $genre->created_at ? $genre->created_at->format('d/m/Y H:i') : 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('admin.genres.edit', $genre->id) }}"
                                                class="btn btn-sm btn-warning"
                                                title="Chỉnh sửa">
                                                <i class="fas fa-edit" style="margin-right: 0"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-sm btn-danger"
                                                    wire:sc-model="deleteGenre({{ $genre->id }})"
                                                    wire:sc-confirm.warning="Bạn có chắc chắn muốn XÓA VĨNH VIỄN thể loại '{{ $genre->name }}'? Hành động này KHÔNG THỂ HOÀN TÁC!"
                                                    title="Xóa">
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
                                            <p>
                                                Không có thể loại nào
                                            </p>
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
    </div>
</div>
