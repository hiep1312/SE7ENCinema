<div wire:poll.5s>
    <div class="container-lg mb-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Chi tiết bình luận: #{{ $comment->id }}</h2>
            <div>
                <a href="{{ route('admin.comments.edit', $comment) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit me-1"></i>Chỉnh sửa
                </a>
                <a href="{{ route('admin.comments.index', ['movie_id' => $comment->movie_id]) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Quay lại
                </a>
            </div>
        </div>

        <!-- Flash Messages -->
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

        <!-- Stats Cards -->
        <div class="row mb-4 g-3">
            <div class="col-lg-6 col-md-6">
                <div class="card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Tổng số trả lời</h6>
                                <h3 class="mb-0">{{ $totalReplies }}</h3>
                            </div>
                            <div class="align-self-center"><i class="fas fa-comments fa-2x opacity-75"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Trả lời hoạt động</h6>
                                <h3 class="mb-0">{{ $activeReplies }}</h3>
                            </div>
                            <div class="align-self-center"><i class="fas fa-comment-check fa-2x opacity-75"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs bg-dark" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link @if($tabCurrent === 'overview') active bg-light text-dark @else text-light @endif" id="overview-tab" type="button" role="tab" wire:click="setTab('overview')">
                    <i class="fas fa-info-circle me-1"></i> Tổng quan
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link @if($tabCurrent === 'replies') active bg-light text-dark @else text-light @endif" id="replies-tab" type="button" role="tab" wire:click="setTab('replies')">
                    <i class="fas fa-comments me-1"></i> Trả lời ({{ $totalReplies }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link @if($tabCurrent === 'add-reply') active bg-light text-dark @else text-light @endif" id="add-reply-tab" type="button" role="tab" wire:click="setTab('add-reply')">
                    <i class="fas fa-plus me-1"></i> Thêm trả lời
                </button>
            </li>
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content mt-3">
            <!-- Overview Tab -->
            <div class="tab-pane fade @if($tabCurrent === 'overview') show active @endif" id="overview" role="tabpanel">
                <div class="row">
                    <div class="col-md-7">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-comment-dots me-2"></i>Nội dung bình luận</h5>
                            </div>
                            <div class="card-body bg-dark">
                                <p class="text-light" style="white-space: pre-wrap;">{{ $comment->content }}</p>
                                <hr class="border-secondary">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="card bg-dark border-light mb-3">
                            <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-user me-2"></i>Người dùng</h5>
                            </div>
                            <div class="card-body bg-dark d-flex align-items-center">
                                @if($comment->user->avatar && !str_contains($comment->user->avatar, 'placeholder.com'))
                                    @if(filter_var($comment->user->avatar, FILTER_VALIDATE_URL))
                                        <img src="{{ $comment->user->avatar }}" alt="{{ $comment->user->name }}" class="rounded-circle me-3" width="50" height="50">
                                    @else
                                        <img src="{{ asset('storage/' . $comment->user->avatar) }}" alt="{{ $comment->user->name }}" class="rounded-circle me-3" width="50" height="50">
                                    @endif
                                @else
                                    <div class="bg-secondary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;"><i class="fas fa-user text-white"></i></div>
                                @endif
                                <div>
                                    <h6 class="mb-0 text-light">{{ $comment->user->name }}</h6>
                                    <small class="text-muted">{{ $comment->user->email }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-info-circle me-2"></i>Chi tiết</h5>
                            </div>
                            <div class="card-body bg-dark">
                                <table class="table table-borderless text-light">
                                    <tr>
                                        <td><strong class="text-warning">Trạng thái:</strong></td>
                                        <td>
                                            @php
                                                $statusClasses = [
                                                    'active' => 'badge-outline-success',
                                                    'hidden' => 'badge-outline-secondary',
                                                    'deleted' => 'badge-outline-danger',
                                                ];
                                                $statusClass = $statusClasses[$comment->status] ?? 'badge-outline-light';
                                            @endphp
                                            <span class="badge {{ $statusClass }} p-2">{{ $statusOptions[$comment->status] ?? $comment->status }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Phim:</strong></td>
                                        <td>{{ $comment->movie->title }}</td>
                                    </tr>
                                        <td style="word-wrap: break-word;">
                                            <div class="d-flex align-items-center">
                                                @if($comment->movie->poster && !str_contains($comment->movie->poster, 'placeholder.com'))
                                                @if(filter_var($comment->movie->poster, FILTER_VALIDATE_URL))
                                                    <img src="{{ $comment->movie->poster }}" alt="{{ $comment->movie->title }}" class="me-3 rounded" style="width: 50px; height: 75px; object-fit: cover; border: 2px solid #6c757d;">
                                                @else
                                                    <img src="{{ asset('storage/' . $comment->movie->poster) }}" alt="{{ $comment->movie->title }}" class="me-3 rounded" style="width: 50px; height: 75px; object-fit: cover; border: 2px solid #6c757d;">
                                                @endif
                                                @else
                                                    <div class="bg-secondary me-3 rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 75px; border: 2px solid #6c757d;">
                                                        <i class="fas fa-film text-white"></i>
                                                    </div>
                                                @endif
                                                    <div class="d-flex align-items-center">
                                                <div>
                                                </div>
                                            </div>
                                        </td>
                                    <tr>
                                        <td><strong class="text-warning">Tạo lúc:</strong></td>
                                        <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Cập nhật:</strong></td>
                                        <td>{{ $comment->updated_at->diffForHumans() }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Replies Tab -->
            <div class="tab-pane fade @if($tabCurrent === 'replies') show active @endif" id="replies" role="tabpanel">
                <div class="card bg-dark border-light">
                    <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5><i class="fas fa-comments me-2"></i>Danh sách trả lời ({{ $totalReplies }})</h5>
                        @if($replies->count() > 0)
                            <button wire:sc-confirm.warning.icon.yellow="Bạn có chắc chắn muốn xóa tất cả bình luận con?"
                                    wire:sc-html="Hành động này không thể hoàn tác!"
                                    wire:sc-model="deleteAllReplies"
                                    class="btn btn-danger btn-sm float-end">
                                <i class="fas fa-trash"></i> Xóa tất cả
                            </button>
                        @endif
                    </div>
                    <div class="card-body bg-dark">
                        @if($replies->count() > 0)
                            <div class="comment-thread-root">
                                <!-- Root comment -->
                                <div class="comment-root d-flex align-items-start mb-4">
                                    <div class="flex-shrink-0 me-3">
                                        @if($comment->user->avatar && !str_contains($comment->user->avatar, 'placeholder.com'))
                                            @if(filter_var($comment->user->avatar, FILTER_VALIDATE_URL))
                                                <img src="{{ $comment->user->avatar }}" alt="{{ $comment->user->name }}" class="rounded-circle" width="48" height="48">
                                            @else
                                                <img src="{{ asset('storage/' . $comment->user->avatar) }}" alt="{{ $comment->user->name }}" class="rounded-circle" width="48" height="48">
                                            @endif
                                        @else
                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;"><i class="fas fa-user text-white"></i></div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="mb-0 text-light">{{ $comment->user->name }}</h6>
                                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                            </div>
                                            <div>
                                                @php
                                                    $statusClass = $statusClasses[$comment->status] ?? 'badge-outline-light';
                                                @endphp
                                                <span class="badge {{ $statusClass }}">{{ $statusOptions[$comment->status] ?? $comment->status }}</span>
                                            </div>
                                        </div>
                                        <p class="mb-1 mt-2">{{ $comment->content }}</p>
                                    </div>
                                </div>
                                <!-- Replies -->
                                <div class="comment-replies ms-5">
                                    @php
                                        $maxRepliesToShow = 5;
                                    @endphp

                                    {{-- Đệ quy hiển thị replies bằng Blade --}}
                                    @once
                                        @push('components')
                                            @php
                                            /**
                                             * @param $replies
                                             * @param $parentId
                                             * @param $isRoot
                                             * @param $level
                                             */
                                                        @endphp
                                            @component('components.reply-tree', [
                                                'replies' => $replies,
                                                'parentId' => $comment->id,
                                                'isRoot' => true,
                                                'level' => 0,
                                                'showAllReplies' => $showAllReplies,
                                                'maxRepliesToShow' => $maxRepliesToShow,
                                                'statusOptions' => $statusOptions,
                                                'statusClasses' => $statusClasses,
                                            ])
                                            @endcomponent
                                        @endpush
                                    @endonce

                                    {{-- Hiển thị component reply-tree --}}
                                    @stack('components')
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5"><i class="fas fa-comment-slash fa-3x text-muted mb-3"></i><p class="text-muted">Chưa có trả lời nào.</p></div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Add Reply Tab -->
            <div class="tab-pane fade @if($tabCurrent === 'add-reply') show active @endif" id="add-reply" role="tabpanel">
                <div class="card bg-dark border-light">
                    <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="my-1"><i class="fas fa-plus me-2"></i>Thêm trả lời mới</h5>
                    </div>
                    <div class="card-body bg-dark">
                        <form wire:submit="addReply">
                            <div class="mb-3">
                                <label for="newReplyContent" class="form-label text-light">Nội dung trả lời</label>
                                <textarea wire:model="newReplyContent" class="form-control bg-dark text-light border-light @error('newReplyContent') is-invalid @enderror" id="newReplyContent" rows="4" placeholder="Nhập nội dung..."></textarea>
                                @error('newReplyContent') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-1"></i> Gửi trả lời</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
