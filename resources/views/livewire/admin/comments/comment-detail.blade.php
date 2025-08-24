<div class="scRender" wire:poll.6s="refreshData">
    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Chi tiết bình luận: #{{ $comment->id }}</h2>
            <div>
                <a href="{{ route('admin.comments.edit', $comment) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit me-1"></i>Chỉnh sửa
                </a>
                <a href="{{ route('admin.comments.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Quay lại
                </a>
            </div>
        </div>

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

        <ul class="nav nav-tabs bg-dark" role="tablist">
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link @if ($tabCurrent === 'overview') active bg-light text-dark @else text-light @endif"
                    id="overview-tab" type="button" role="tab" wire:click="$set('tabCurrent', 'overview')">
                    <i class="fas fa-info-circle me-1"></i> Tổng quan
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link @if ($tabCurrent === 'replies') active bg-light text-dark @else text-light @endif"
                    id="replies-tab" type="button" role="tab" wire:click="$set('tabCurrent', 'replies')">
                    <i class="fas fa-comments me-1"></i> Trả lời ({{ $totalReplies }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link @if ($tabCurrent === 'add-reply') active bg-light text-dark @else text-light @endif"
                    id="add-reply-tab" type="button" role="tab" wire:click="$set('tabCurrent', 'add-reply')">
                    <i class="fas fa-plus me-1"></i> Thêm trả lời
                </button>
            </li>
        </ul>

        <div class="tab-content mt-1">
            @if ($tabCurrent === 'overview')
                <div class="tab-pane fade show active" id="overview" role="tabpanel">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card bg-dark border-light">
                                <div class="card-header bg-gradient text-light"
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <h5><i class="fas fa-comment-dots me-2"></i>Nội dung bình luận</h5>
                                </div>
                                <div class="card-body bg-dark">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0 me-3">
                                            @if ($comment->user->avatar && !str_contains($comment->user->avatar, 'placeholder.com'))
                                                @if (filter_var($comment->user->avatar, FILTER_VALIDATE_URL))
                                                    <img src="{{ $comment->user->avatar }}"
                                                        alt="{{ $comment->user->name }}" class="rounded-circle"
                                                        width="60" height="60">
                                                @else
                                                    <img src="{{ asset('storage/' . $comment->user->avatar) }}"
                                                        alt="{{ $comment->user->name }}" class="rounded-circle"
                                                        width="60" height="60">
                                                @endif
                                            @else
                                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width: 60px; height: 60px;">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h6 class="mb-0 text-light">{{ $comment->user->name }}</h6>
                                                    <small class="text-muted">{{ $comment->user->email }}</small>
                                                </div>
                                                <div>
                                                    @php
                                                        $statusClasses = [
                                                            'active' => 'badge bg-success',
                                                            'hidden' => 'badge bg-secondary',
                                                            'deleted' => 'badge bg-danger',
                                                        ];
                                                        $statusClass = $statusClasses[$comment->status];
                                                    @endphp
                                                </div>
                                            </div>

                                            <div class="bg-light bg-opacity-10 rounded p-3 mb-3">
                                                <p class="text-light mb-0" style="white-space: pre-wrap;">
                                                    {{ $comment->content }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($comment->parent_comment_id && $comment->parent)
                                <div class="card bg-dark border-light mt-3">
                                    <div class="card-header bg-gradient text-light"
                                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        <h6><i class="fas fa-reply me-2"></i>Bình luận cha</h6>
                                    </div>
                                    <div class="card-body bg-dark">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-shrink-0 me-3">
                                                @if ($comment->parent->user->avatar && !str_contains($comment->parent->user->avatar, 'placeholder.com'))
                                                    @if (filter_var($comment->parent->user->avatar, FILTER_VALIDATE_URL))
                                                        <img src="{{ $comment->parent->user->avatar }}"
                                                            alt="{{ $comment->parent->user->name }}"
                                                            class="rounded-circle" width="40" height="40">
                                                    @else
                                                        <img src="{{ asset('storage/' . $comment->parent->user->avatar) }}"
                                                            alt="{{ $comment->parent->user->name }}"
                                                            class="rounded-circle" width="40" height="40">
                                                    @endif
                                                @else
                                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                                        style="width: 40px; height: 40px;">
                                                        <i class="fas fa-user text-white"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <div>
                                                        <h6 class="mb-0 text-light">{{ $comment->parent->user->name }}
                                                        </h6>
                                                        <small
                                                            class="text-muted">{{ $comment->parent->created_at->diffForHumans() }}</small>
                                                    </div>
                                                    <div>
                                                        @php
                                                            $parentStatusClass =
                                                                $statusClasses[$comment->parent->status] ??
                                                                'badge-outline-light';
                                                        @endphp
                                                        <span
                                                            class="badge {{ $parentStatusClass }}">{{ $statusOptions[$comment->parent->status] ?? $comment->parent->status }}</span>
                                                    </div>
                                                </div>
                                                <div class="bg-light bg-opacity-10 rounded p-2">
                                                    <p class="text-light mb-0">
                                                        {{ Str::limit($comment->parent->content, 150) }}</p>
                                                </div>
                                                <div class="mt-2">
                                                    <a href="{{ route('admin.comments.detail', $comment->parent) }}"
                                                        class="btn btn-outline-info btn-sm">
                                                        <i class="fas fa-eye me-1"></i> Xem chi tiết
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <div class="card bg-dark border-light">
                                <div class="card-header bg-gradient text-light"
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <h5><i class="fas fa-info-circle me-2"></i>Thông tin chi tiết</h5>
                                </div>
                                <div class="card-body bg-dark">
                                    <div class="mb-3">
                                        <div>
                                            <label class="form-label text-light fw-bold me-3">Trạng thái:</label>
                                            @php
                                                $statusClass =
                                                    $statusClasses[$comment->status] ?? 'badge-outline-light';
                                            @endphp
                                            <span
                                                class="badge {{ $statusClass }}">{{ $statusOptions[$comment->status] ?? $comment->status }}</span>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label text-light fw-bold mb-3">
                                            Thông tin phim
                                        </label>
                                        <div class="d-flex align-items-start bg-light bg-opacity-10 rounded-3 p-3">
                                            <div class="flex-shrink-0 me-3">
                                                @if ($comment->movie->poster && !str_contains($comment->movie->poster, 'placeholder.com'))
                                                    @if (filter_var($comment->movie->poster, FILTER_VALIDATE_URL))
                                                        <img src="{{ $comment->movie->poster }}"
                                                            alt="{{ $comment->movie->title }}" class="rounded"
                                                            style="width: 60px; height: 80px; object-fit: cover;">
                                                    @else
                                                        <img src="{{ asset('storage/' . $comment->movie->poster) }}"
                                                            alt="{{ $comment->movie->title }}" class="rounded"
                                                            style="width: 60px; height: 80px; object-fit: cover;">
                                                    @endif
                                                @else
                                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                                        style="width: 60px; height: 80px;">
                                                        <i class="fas fa-film text-white fa-2x"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="text-light fw-bold mb-1">{{ $comment->movie->title }}</h6>
                                                <div class="text-muted small">
                                                    <i class="fas fa-tags me-1"></i>
                                                    @if ($comment->movie->genres->count() > 0)
                                                        {{ $comment->movie->genres->pluck('name')->implode(', ') }}
                                                    @else
                                                        Không có thể loại
                                                    @endif
                                                </div>
                                                <div class="text-muted small mt-1">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ $comment->movie->duration ?? 'N/A' }} phút
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3 mt-2">
                                        <label class="form-label text-light fw-bold">Ngày tạo:</label>
                                        <div class="text-light">{{ $comment->created_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-light fw-bold">Cập nhật lần cuối:</label>
                                        <div class="text-light">{{ $comment->updated_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($tabCurrent === 'replies')
                <div class="card bg-dark border-light">
                    <div class="card-header bg-gradient text-light"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <span class="fs-5"><i class="fas fa-comments me-2"></i>Danh sách trả lời
                            ({{ $totalReplies }})</span>
                        @if ($replies->count() > 0)
                            <button wire:sc-confirm.warning.icon="Bạn có chắc chắn muốn xóa tất cả bình luận con?"
                                wire:sc-html="Hành động này không thể hoàn tác!" wire:sc-model="deleteAllReplies"
                                class="btn btn-danger btn-sm float-end">
                                <i class="fas fa-trash"></i> Xóa tất cả
                            </button>
                        @endif
                    </div>
                    <div class="card-body bg-dark">
                        <div class="comment-thread-root">
                            <div class="comment-root d-flex align-items-start mb-4">
                                <div class="flex-shrink-0 me-3">
                                    @if ($comment->user->avatar && !str_contains($comment->user->avatar, 'placeholder.com'))
                                        @if (filter_var($comment->user->avatar, FILTER_VALIDATE_URL))
                                            <img src="{{ $comment->user->avatar }}" alt="{{ $comment->user->name }}"
                                                class="rounded-circle" width="48" height="48">
                                        @else
                                            <img src="{{ asset('storage/' . $comment->user->avatar) }}"
                                                alt="{{ $comment->user->name }}" class="rounded-circle"
                                                width="48" height="48">
                                        @endif
                                    @else
                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 48px; height: 48px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="mb-0 text-light">{{ $comment->user->name }}</h6>
                                            <small
                                                class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                        </div>
                                        <div>
                                            @php
                                                $statusClass = $statusClasses[$comment->status] ?? 'bg-success';
                                            @endphp
                                            <span
                                                class="badge {{ $statusClass }}">{{ $statusOptions[$comment->status] ?? $comment->status }}</span>
                                        </div>
                                    </div>
                                    <p class="mb-1 mt-2">{{ $comment->content }}</p>
                                </div>
                            </div>
                            @if ($replies->count() > 0)
                                <div class="comment-replies ms-5">
                                    @php
                                        $replyCount = $replies->count();
                                        $showAll = $showAllReplies[$comment->id] ?? false;
                                        $repliesToShow = $showAll ? $replies : $replies->take($maxRepliesToShow);
                                    @endphp
                                    @foreach ($repliesToShow as $reply)
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="flex-shrink-0 me-3">
                                                @if ($reply->user->avatar && !str_contains($reply->user->avatar, 'placeholder.com'))
                                                    @if (filter_var($reply->user->avatar, FILTER_VALIDATE_URL))
                                                        <img src="{{ $reply->user->avatar }}"
                                                            alt="{{ $reply->user->name }}" class="rounded-circle"
                                                            width="40" height="40">
                                                    @else
                                                        <img src="{{ asset('storage/' . $reply->user->avatar) }}"
                                                            alt="{{ $reply->user->name }}" class="rounded-circle"
                                                            width="40" height="40">
                                                    @endif
                                                @else
                                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                                        style="width: 40px; height: 40px;"><i
                                                            class="fas fa-user text-white"></i></div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-0 text-light">{{ $reply->user->name }}</h6>
                                                        <small
                                                            class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                                    </div>
                                                    @switch($reply->status)
                                                        @case('active')
                                                            <span class="badge bg-success ms-2">Hoạt động</span>
                                                        @break

                                                        @case('hidden')
                                                            <span class="badge bg-warning text-dark ms-2">Ẩn</span>
                                                        @break

                                                        @case('deleted')
                                                            <span class="badge bg-danger ms-2">Đã xóa</span>
                                                        @break

                                                        @default
                                                            <span
                                                                class="badge bg-secondary ms-2">{{ $statusOptions[$reply->status] ?? $reply->status }}</span>
                                                    @endswitch
                                                </div>
                                                <p class="mb-1 mt-2">{{ $reply->content }}</p>
                                                <div class="mt-2 d-flex gap-2">
                                                    <a href="{{ route('admin.comments.edit', $reply) }}"
                                                        class="btn btn-outline-warning btn-sm" title="Sửa"><i
                                                            class="fas fa-edit"></i> Sửa</a>
                                                    <button wire:key="delete-reply-{{ $reply->id }}"
                                                        wire:click="deleteComment({{ $reply->id }})"
                                                        class="btn btn-outline-danger btn-sm" title="Xóa"><i
                                                            class="fas fa-trash"></i> Xóa</button>
                                                </div>
                                                @if ($reply->children && $reply->children->count() > 0)
                                                    <div class="mt-2">
                                                        @php
                                                            $childReplyCount = $reply->children->count();
                                                            $showAllChildren = $showAllReplies[$reply->id] ?? false;
                                                            $childRepliesToShow = $showAllChildren
                                                                ? $reply->children
                                                                : $reply->children->take($maxRepliesToShow);
                                                        @endphp
                                                        @foreach ($childRepliesToShow as $childReply)
                                                            <div
                                                                class="d-flex align-items-start mb-3 border-start border-2 border-secondary ps-3">
                                                                <div class="flex-shrink-0 me-3">
                                                                    @if ($childReply->user->avatar && !str_contains($childReply->user->avatar, 'placeholder.com'))
                                                                        @if (filter_var($childReply->user->avatar, FILTER_VALIDATE_URL))
                                                                            <img src="{{ $childReply->user->avatar }}"
                                                                                alt="{{ $childReply->user->name }}"
                                                                                class="rounded-circle" width="40"
                                                                                height="40">
                                                                        @else
                                                                            <img src="{{ asset('storage/' . $childReply->user->avatar) }}"
                                                                                alt="{{ $childReply->user->name }}"
                                                                                class="rounded-circle" width="40"
                                                                                height="40">
                                                                        @endif
                                                                    @else
                                                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                                                            style="width: 40px; height: 40px;"><i
                                                                                class="fas fa-user text-white"></i>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-center">
                                                                        <div>
                                                                            <h6 class="mb-0 text-light">
                                                                                {{ $childReply->user->name }}</h6>
                                                                            <small
                                                                                class="text-muted">{{ $childReply->created_at->diffForHumans() }}</small>
                                                                        </div>
                                                                        @switch($childReply->status)
                                                                            @case('active')
                                                                                <span class="badge bg-success ms-2">Hoạt
                                                                                    động</span>
                                                                            @break

                                                                            @case('hidden')
                                                                                <span
                                                                                    class="badge bg-warning text-dark ms-2">Ẩn</span>
                                                                            @break

                                                                            @case('deleted')
                                                                                <span class="badge bg-danger ms-2">Đã
                                                                                    xóa</span>
                                                                            @break

                                                                            @default
                                                                                <span
                                                                                    class="badge bg-secondary ms-2">{{ $statusOptions[$childReply->status] ?? $childReply->status }}</span>
                                                                        @endswitch
                                                                    </div>
                                                                    <p class="mb-1 mt-2">{{ $childReply->content }}
                                                                    </p>
                                                                    <div class="mt-2 d-flex gap-2">
                                                                        <a href="{{ route('admin.comments.edit', $childReply) }}"
                                                                            class="btn btn-outline-warning btn-sm"
                                                                            title="Sửa"><i class="fas fa-edit"></i>
                                                                            Sửa</a>
                                                                        <button
                                                                            wire:key="delete-reply-{{ $childReply->id }}"
                                                                            wire:click="deleteComment({{ $childReply->id }})"
                                                                            class="btn btn-outline-danger btn-sm"
                                                                            title="Xóa"><i
                                                                                class="fas fa-trash"></i> Xóa</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        @if ($childReplyCount > $maxRepliesToShow)
                                                            <div class="d-flex align-items-center my-3 ms-3">
                                                                <hr class="flex-grow-1 border-secondary me-3"
                                                                    style="height:1px; opacity: 0.5;">
                                                                <button
                                                                    wire:click="toggleShowAllReplies({{ $reply->id }})"
                                                                    class="btn btn-outline-info btn-sm rounded-pill px-3 py-2 fw-semibold text-nowrap shadow-sm border-2 position-relative overflow-hidden"
                                                                    style="transition: all 0.3s ease; backdrop-filter: blur(10px);"
                                                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(13, 202, 240, 0.3)';"
                                                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 10px rgba(0,0,0,0.1)';">
                                                                    <i
                                                                        class="fas fa-{{ $showAllChildren ? 'chevron-up' : 'chevron-down' }} me-2"></i>
                                                                    @if ($showAllChildren)
                                                                        <span class="fw-bold">Ẩn bớt</span>
                                                                    @else
                                                                        <span class="fw-bold">Xem thêm
                                                                            {{ $childReplyCount - $maxRepliesToShow }}
                                                                            câu trả lời</span>
                                                                    @endif
                                                                </button>
                                                                <hr class="flex-grow-1 border-secondary ms-3"
                                                                    style="height:1px; opacity: 0.5;">
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    @if ($replyCount > $maxRepliesToShow)
                                        <div class="d-flex align-items-center my-4 ms-3">
                                            <hr class="flex-grow-1 border-secondary me-3"
                                                style="height:2px; opacity: 0.6;">
                                            <button wire:click="toggleShowAllReplies({{ $comment->id }})"
                                                class="btn btn-info btn-lg rounded-pill px-4 py-3 fw-bold text-white shadow-lg border-0 position-relative overflow-hidden"
                                                style="background: linear-gradient(135deg, #0dcaf0 0%, #0a58ca 100%); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); backdrop-filter: blur(15px);"
                                                onmouseover="this.style.transform='translateY(-3px) scale(1.05)'; this.style.boxShadow='0 15px 35px rgba(13, 202, 240, 0.4)'; this.style.background='linear-gradient(135deg, #17a2b8 0%, #0056b3 100%)';"
                                                onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 8px 25px rgba(13, 202, 240, 0.3)'; this.style.background='linear-gradient(135deg, #0dcaf0 0%, #0a58ca 100%)';">
                                                <i
                                                    class="fas fa-{{ $showAll ? 'chevron-up' : 'chevron-down' }} me-2 fs-5"></i>
                                                @if ($showAll)
                                                    <span class="fs-6">Ẩn bớt</span>
                                                @else
                                                    <span class="fs-6">Xem thêm
                                                        {{ $replyCount - $maxRepliesToShow }} câu trả lời</span>
                                                @endif
                                                <div class="position-absolute top-0 start-0 w-100 h-100 bg-white opacity-0"
                                                    style="transition: opacity 0.3s ease;"></div>
                                            </button>
                                            <hr class="flex-grow-1 border-secondary ms-3"
                                                style="height:2px; opacity: 0.6;">
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="text-center pb-5"><i
                                        class="fas fa-comment-slash fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Chưa có trả lời nào.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @elseif($tabCurrent === 'add-reply')
                    <div class="tab-pane fade show active" id="add-reply" role="tabpanel">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5 class="my-1"><i class="fas fa-plus me-2"></i>Thêm trả lời mới</h5>
                            </div>
                            <div class="card-body bg-dark">
                                <form wire:submit="addReply">
                                    <div class="mb-3">
                                        <label for="newReplyContent" class="form-label text-light">Nội dung trả
                                            lời</label>
                                        <textarea wire:model="newReplyContent"
                                            class="form-control bg-dark text-light border-light @error('newReplyContent') is-invalid @enderror"
                                            id="newReplyContent" rows="4" placeholder="Nhập nội dung..."></textarea>
                                        @error('newReplyContent')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary"><i
                                            class="fas fa-paper-plane me-1"></i> Gửi trả lời</button>
                                </form>
                            </div>
                        </div>
                    </div>
            @endif
        </div>
    </div>
</div>
