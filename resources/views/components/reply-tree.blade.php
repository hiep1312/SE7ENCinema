{{--
    $replies: Collection các reply con
    $parentId: id comment cha
    $isRoot: bool, true nếu là root
    $level: cấp lồng
    $showAllReplies: biến Livewire mảng
    $maxRepliesToShow: số lượng tối đa hiển thị mặc định
    $statusOptions, $statusClasses: badge trạng thái
--}}
@php
    $replyCount = $replies->count();
    $showAll = $showAllReplies[$parentId] ?? false;
    $repliesToShow = $showAll ? $replies : $replies->take($maxRepliesToShow);
@endphp
@foreach($repliesToShow as $reply)
    <div class="d-flex align-items-start mb-3 @if(!$isRoot) border-start border-2 border-secondary ps-3 @endif">
        <div class="flex-shrink-0 me-3">
            @if($reply->user->avatar && !str_contains($reply->user->avatar, 'placeholder.com'))
                @if(filter_var($reply->user->avatar, FILTER_VALIDATE_URL))
                    <img src="{{ $reply->user->avatar }}" alt="{{ $reply->user->name }}" class="rounded-circle" width="40" height="40">
                @else
                    <img src="{{ asset('storage/'.$reply->user->avatar) }}" alt="{{ $reply->user->name }}" class="rounded-circle" width="40" height="40">
                @endif
            @else
                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="fas fa-user text-white"></i></div>
            @endif
        </div>
        <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 text-light">{{ $reply->user->name }}</h6>
                    <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                </div>
                @php
                    $statusClass = $statusClasses[$reply->status] ?? 'bg-secondary';
                    $statusLabel = $statusOptions[$reply->status] ?? $reply->status;
                @endphp
                <span class="badge {{ $statusClass }} ms-2">{{ $statusLabel }}</span>
            </div>
            <p class="mb-1 mt-2">{{ $reply->content }}</p>
            <div class="mt-2 d-flex gap-2">
                <a href="{{ route('admin.comments.edit', $reply) }}" class="btn btn-outline-warning btn-sm" title="Sửa"><i class="fas fa-edit"></i> Sửa</a>
                <button wire:key="delete-reply-{{ $reply->id }}" wire:click="deleteComment({{ $reply->id }})" class="btn btn-outline-danger btn-sm" title="Xóa"><i class="fas fa-trash"></i> Xóa</button>
            </div>
            @if($reply->children && $reply->children->count() > 0)
                <div class="mt-2">
                    @component('components.reply-tree', [
                        'replies' => $reply->children,
                        'parentId' => $reply->id,
                        'isRoot' => false,
                        'level' => $level+1,
                        'showAllReplies' => $showAllReplies,
                        'maxRepliesToShow' => $maxRepliesToShow,
                        'statusOptions' => $statusOptions,
                        'statusClasses' => $statusClasses,
                    ])
                    @endcomponent
                </div>
            @endif
        </div>
    </div>
@endforeach
@if($replyCount > $maxRepliesToShow)
    <div class="d-flex align-items-center my-2 ms-3">
        <hr class="flex-grow-1 border-secondary me-2" style="height:2px;">
        <button wire:click="toggleShowAllReplies({{ $parentId }})" class="btn btn-link text-info p-0" style="font-weight: 600;">
            @if($showAll)
                Ẩn bớt <i class="fas fa-chevron-up"></i>
            @else
                Xem thêm {{ $replyCount - $maxRepliesToShow }} câu trả lời <i class="fas fa-chevron-down"></i>
            @endif
        </button>
    </div>
@endif
