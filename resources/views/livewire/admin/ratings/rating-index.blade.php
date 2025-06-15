<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" wire:ignore>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" wire:ignore>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Quản lý đánh giá</h2>
        </div>

        <div class="card bg-dark" wire:poll.6s>
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="row g-3">
                    <!-- Tìm kiếm -->
                    <div class="col-md-4 col-lg-3">
                        <div class="input-group">
                            <input type="text" wire:model.live.debounce.300ms="search"
                                class="form-control bg-dark text-light" placeholder="Tìm kiếm người đánh giá...">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <select wire:model.live="movieFilter" class="form-select bg-dark">
                            <option value="">Tất cả phim</option>
                            @foreach ($movies as $movie)
                                <option value="{{ $movie->id }}">{{ $movie->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Lọc theo trạng thái -->
                    <div class="col-md-3 col-lg-2">
                        <select wire:model.live="starFilter" class="form-select bg-dark">
                            <option value="">Tất cả đánh giá</option>
                            <option value="1">⭐<span>{{$counts[1]}}</span></option>
                            <option value="2">⭐⭐<span>{{$counts[2]}}</span></option>
                            <option value="3">⭐⭐⭐<span>{{$counts[3]}}</span></option>
                            <option value="4">⭐⭐⭐⭐<span>{{$counts[4]}}</span></option>
                            <option value="5">⭐⭐⭐⭐⭐<span>{{$counts[5]}}</span></option>
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
                                <th class="text-center text-light">Người đánh giá</th>
                                <th class="text-center text-light">Phim</th>
                                <th class="text-center text-light">Nội dung</th>
                                <th class="text-center text-light">Đánh giá</th>
                                <th class='text-center text-light'>Ngày tạo</th>
                                <th class="text-center text-light">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ratings as $rating)
                                <tr wire:key="{{ $rating->id }}">
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                    <td class="text-center text-wrap">
                                        <div class="mt-1 d-block">
                                            <a class="text-decoration-none text-white link-primary"
                                                href="{{ route('admin.users.detail', $rating->user->id) }}">{{ $rating->user->name }}</a>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <strong class="text-light">{{ $rating->movie->title }}</strong>
                                    </td>
                                    <td class="text-center">
                                        @if ($rating->review)
                                            <span>{{ Str::limit($rating->review, 50, '...') }}</span>
                                        @else
                                            <span class="text-muted">Không có đánh giá</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $rating->score > 3 ? 'success' : ($rating->score === 3 ? 'primary' : 'danger') }} d-flex gap-1 justify-content-center align-items-center">
                                            {!! Str::repeat('<i class="fas fa-star"></i>', $rating->score) !!}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        {{ $rating->created_at ? $rating->created_at->format('d/m/Y H:i') : 'N/A' }}
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <button class='btn btn-sm btn-danger' wire:sc-model="softDeleteRating({{ $rating->id }})"
                                                wire:sc-confirm.warning="Bạn có chắc chắn muốn xóa đánh giá này không?">
                                                <i class="fas fa-trash" style="margin-right: 0"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>
                                                Không có đánh giá nào
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $ratings->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
