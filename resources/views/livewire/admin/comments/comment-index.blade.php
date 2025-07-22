<div class="scRender">
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
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Quản lý bình luận</h2>
            <a href="{{ route('admin.comments.create', ['movie_id' => $movieId]) }}" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Thêm bình luận
            </a>
        </div>

        <div class="card bg-dark" wire:poll.5s>
            <!-- Filters -->
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="row g-3">
                    <div class="col-md-4 col-lg-3">
                        <div class="input-group">
                            <input type="text" wire:model.live.debounce.300ms="search"
                                   class="form-control bg-dark text-light"
                                   placeholder="Tìm kiếm bình luận...">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <select wire:model.live="movieId" class="form-select bg-dark text-light">
                            <option value="">Tất cả phim</option>
                            @foreach($movies as $movie)
                                <option value="{{ $movie->id }}">{{ $movie->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <select wire:model.live="statusFilter" class="form-select bg-dark text-light">
                            <option value="">Tất cả trạng thái</option>
                            @foreach($statusOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
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
                                <th class="text-center text-light">Người dùng</th>
                                <th class="text-center text-light">Nội dung bình luận</th>
                                <th class="text-center text-light">Phim</th>
                                <th class="text-center text-light">Trạng thái</th>
                                <th class="text-center text-light">Ngày tạo</th>
                                <th class="text-center text-light">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($comments as $comment)
                                <tr wire:key="{{ $comment->id }}">
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>

                                    <!-- NGƯỜI DÙNG -->
                                    <td class="col-2 bg-opacity-10 border-start border-3">
                                        <div class="comment-showtime-info">
                                            <div class="d-flex align-items-center">
                                                @if($comment->user->avatar && !str_contains($comment->user->avatar, 'placeholder.com'))
                                                    @if(filter_var($comment->user->avatar, FILTER_VALIDATE_URL))
                                                        <img src="{{ $comment->user->avatar }}"
                                                             alt="{{ $comment->user->name }}"
                                                             class="rounded-circle me-2" width="40" height="40">
                                                    @else
                                                        <img src="{{ asset('storage/' . $comment->user->avatar) }}"
                                                             alt="{{ $comment->user->name }}"
                                                             class="rounded-circle me-2" width="40" height="40">
                                                    @endif
                                                @else
                                                    <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-user text-white"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="comment-movie-title mb-1">
                                                        <i class="fas fa-user me-1 text-primary"></i>
                                                        <strong class="text-primary text-wrap">
                                                            {{ $comment->user->name }}
                                                        </strong>
                                                    </div>
                                                    <div class="showtime-price mb-1">
                                                        <i class="fas fa-envelope me-1 text-warning"></i>
                                                        <span class="text-warning">
                                                            {{ Str::limit($comment->user->email, 20, '...') }}
                                                        </span>
                                                    </div>
                                                    {{-- <div class="time-until">
                                                        <small class="text-info">
                                                            <i class="fas fa-clock me-1"></i>
                                                            Thành viên từ {{ $comment->user->created_at->format('Y/m/d H:i') }}
                                                        </small>
                                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- NỘI DUNG BÌNH LUẬN -->
                                    <td class="col-3 bg-opacity-10 border-start border-3">
                                        @if ($comment->content)
                                            <span class="text-light text-wrap">{{ Str::limit($comment->content, 35, '...') }}</span>
                                            <div class="mt-1">
                                                <small class="text-info">
                                                    <i class="fas fa-comments me-1"></i>
                                                    {{ $comment->replies_count }} phản hồi
                                                </small>
                                            </div>
                                        @else
                                            <span class="text-muted">Không có nội dung</span>
                                        @endif
                                    </td>

                                    <!-- PHIM -->
                                    <td class="col-2 bg-opacity-10 border-start border-3">
                                        <div class="comment-showtime-info">
                                            <div class="d-flex align-items-center">
                                                @if($comment->movie->poster && !str_contains($comment->movie->poster, 'placeholder.com'))
                                                    @if(filter_var($comment->movie->poster, FILTER_VALIDATE_URL))
                                                        <img src="{{ $comment->movie->poster }}"
                                                             alt="{{ $comment->movie->title }}"
                                                             class="me-2 rounded"
                                                             style="width: 40px; height: 60px; object-fit: cover;">
                                                    @else
                                                        <img src="{{ asset('storage/' . $comment->movie->poster) }}"
                                                             alt="{{ $comment->movie->title }}"
                                                             class="me-2 rounded"
                                                             style="width: 40px; height: 60px; object-fit: cover;">
                                                    @endif
                                                @else
                                                    <div class="bg-secondary me-2 rounded d-flex align-items-center justify-content-center"
                                                         style="width: 40px; height: 60px;">
                                                        <i class="fas fa-film text-white"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="comment-movie-title mb-1">
                                                        <i class="fas fa-film me-1 text-primary"></i>
                                                        <strong class="text-primary text-wrap">
                                                            {{ Str::limit($comment->movie->title, 20, '...') }}
                                                        </strong>
                                                    </div>
                                                    <div class="showtime-price mb-1">
                                                        <i class="fas fa-calendar me-1 text-warning"></i>
                                                        <span class="text-warning">
                                                            {{ $comment->movie->release_date ? $comment->movie->release_date->format('d/m/Y') : 'Chưa có' }}
                                                        </span>
                                                    </div>
                                                    <div class="time-until">
                                                        <small class="text-info">
                                                            <i class="fas fa-star me-1"></i>
                                                            {{ $comment->movie->rating ?? 'Chưa có' }} ⭐
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- TRẠNG THÁI -->
                                    <td class="text-center">
                                        @switch($comment->status)
                                            @case('active')
                                                <span class="badge bg-success">Hoạt động</span>
                                                @break
                                            @case('hidden')
                                                <span class="badge bg-warning text-dark">Ẩn</span>
                                                @break
                                            @case('deleted')
                                                <span class="badge bg-danger">Đã xóa</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ $statusOptions[$comment->status] ?? $comment->status }}</span>
                                        @endswitch
                                    </td>

                                    <!-- NGÀY TẠO -->
                                    <td class="text-center">
                                        <span class="text-light">
                                            {{ $comment->created_at ? $comment->created_at->format('d/m/Y H:i') : 'N/A' }}
                                        </span>
                                    </td>

                                    <!-- HÀNH ĐỘNG -->
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('admin.comments.detail', $comment) }}"
                                               class="btn btn-sm btn-info"
                                               title="Xem chi tiết">
                                                <i class="fas fa-eye" style="margin-right: 0"></i>
                                            </a>
                                            <a href="{{ route('admin.comments.edit', $comment) }}"
                                               class="btn btn-sm btn-warning"
                                               title="Chỉnh sửa">
                                                <i class="fas fa-edit" style="margin-right: 0"></i>
                                            </a>
                                            @php
                                                $canDelete = $this->checkDelete($comment);
                                            @endphp
                                            <button type="button"
                                                    class="btn btn-sm @if($canDelete) btn-danger @else btn-secondary @endif"
                                                    title="Xóa"
                                                    wire:sc-model="deleteComment({{ $comment->id }})"
                                                    wire:sc-confirm.warning="Bạn có chắc chắn muốn xóa bình luận này? Hành động này KHÔNG THỂ HOÀN TÁC!"
                                                    @if(!$canDelete) disabled @endif>
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
                                            <p>Không có bình luận nào</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $comments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
