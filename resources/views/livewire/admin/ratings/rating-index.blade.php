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
                                <option value="{{ $movie->id }}" wire:key="movie-{{ $movie->id }}">{{ $movie->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Lọc theo trạng thái -->
                    <div class="col-md-3 col-lg-2">
                        <select wire:model.live="starFilter" class="form-select bg-dark">
                            <option value="">Tất cả đánh giá</option>
                            @foreach($countsStar as $index => $countStar)
                                @if ($countStar > 0)
                                    <option value="{{ $index }}" wire:key="star-{{ $index }}">{{ Str::repeat('⭐', $index) }}<span>{{ $countStar }}</span></option>
                                @endif
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
                                        <div class="d-flex align-items-center justify-content-center flex-wrap flex-xl-nowrap p-3 compact-dark rounded">
                                            <div class="user-avatar-clean me-3" style="width: 35px; height: 35px;">
                                                @if($rating->user->avatar)
                                                    <img src="{{ asset('storage/' . $rating->user->avatar) }}" alt style="width: 35px; height: 35px; object-fit: cover;">
                                                @else
                                                    <i class="fas fa-user icon-white" style="font-size: 14px;"></i>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <a class="user-name-link-dark d-block mb-1" href="{{ route('admin.users.detail', $rating->user->id) }}">
                                                    {{ $rating->user->name }}
                                                </a>
                                                <small class="text-muted">
                                                    <i class="fas fa-star me-1 icon-blue"></i>Người đánh giá
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <strong class="text-light">{{ $rating->movie->title }}</strong>
                                    </td>
                                    <td class="text-center text-wrap lh-base">
                                        @if($rating->trashed())
                                            <span class="badge bg-danger">Bài đánh giá đã bị xóa</span>
                                        @else
                                            @if ($rating->review)
                                                <span>{{ Str::limit($rating->review, 200, '...') }}</span>
                                            @else
                                                <span class="text-muted">Không có đánh giá</span>
                                            @endif
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
                                        <div class="d-flex gap-2 justify-content-center">
                                            @if($rating->trashed())
                                                <button type="button"
                                                        wire:sc-confirm.info="Bạn có chắc chắn muốn khôi phục bài đánh giá '{{ Str::limit($rating->review, 50, '...') }}' không?"
                                                        wire:sc-model="restoreRating({{ $rating->id }})"
                                                        class="btn btn-sm btn-success"
                                                        title="Khôi phục">
                                                    <i class="fas fa-undo" style="margin-right: 0"></i>
                                                </button>
                                            @else
                                                <button class='btn btn-sm btn-danger' wire:sc-model="softDeleteRating({{ $rating->id }})"
                                                    wire:sc-confirm.warning="Bạn có chắc chắn muốn xóa bài đánh giá '{{ Str::limit($rating->review, 50, '...') }}' không?">
                                                    <i class="fas fa-trash" style="margin-right: 0"></i>
                                                </button>
                                            @endif
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
