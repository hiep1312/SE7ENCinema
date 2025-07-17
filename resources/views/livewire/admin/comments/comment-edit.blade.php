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
            <h2 class="text-light">Chỉnh sửa bình luận: #{{ $comment->id }}</h2>
            <a href="{{ route('admin.comments.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <!-- Main Comment Edit Form -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-dark">
                    <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="my-1">Thông tin bình luận</h5>
                    </div>
                    <div class="card-body bg-dark">
                        <form wire:submit.prevent="updateComment">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="movieId" class="form-label text-light">Phim <span class="text-danger">*</span></label>
                                        <select wire:model.live="movieId" id="movieId"
                                                class="form-select bg-dark text-light border-light @error('movieId') is-invalid @enderror">
                                            <option value="">Chọn phim...</option>
                                            @foreach($movies as $movie)
                                                <option value="{{ $movie->id }}">{{ $movie->title }}</option>
                                            @endforeach
                                        </select>
                                        @error('movieId')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @if(isset($selectedMovie) && $selectedMovie && $selectedMovie->poster)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $selectedMovie->poster) }}" alt="{{ $selectedMovie->title }}" style="max-width: 80px; max-height: 120px; border-radius: 4px; border: 1px solid #444;">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label text-light">Trạng thái <span class="text-danger">*</span></label>
                                        <select wire:model="status" id="status"
                                                class="form-select bg-dark text-light border-light @error('status') is-invalid @enderror">
                                            <option value="active">Hoạt động</option>
                                            <option value="hidden">Đã ẩn</option>
                                            @php
                                                $activeChildren = \App\Models\Comment::where('parent_comment_id', $comment->id)->where('status', 'active')->count();
                                            @endphp
                                            <option value="deleted" @if($activeChildren > 0) disabled @endif>Đã xóa</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @if($activeChildren > 0 && $status === 'deleted')
                                            <small class="text-warning">Không thể xóa vì còn {{ $activeChildren }} bình luận con đang hoạt động</small>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Parent Comment Selection -->
                            <div class="mb-3">
                                <label for="parentCommentId" class="form-label text-light">Bình luận cha (tùy chọn)</label>
                                <select wire:model.live="parentCommentId" id="parentCommentId"
                                        class="form-select bg-dark text-light border-light @error('parentCommentId') is-invalid @enderror">
                                    <option value="">Không có bình luận cha</option>
                                    @foreach($parentComments as $parentComment)
                                        <option value="{{ $parentComment->id }}">
                                            {{ $parentComment->user->name }} - {{ Str::limit($parentComment->content, 50) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('parentCommentId')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Reply Comment Selection -->
                            @if($parentCommentId && $replyComments->count() > 0)
                                <div class="mb-3">
                                    <label for="replyCommentId" class="form-label text-light">Trả lời bình luận (tùy chọn)</label>
                                    <select wire:model="replyCommentId" id="replyCommentId"
                                            class="form-select bg-dark text-light border-light @error('replyCommentId') is-invalid @enderror">
                                        <option value="">Không trả lời bình luận cụ thể</option>
                                        @foreach($replyComments as $replyComment)
                                            <option value="{{ $replyComment->id }}">
                                                {{ $replyComment->user->name }} - {{ Str::limit($replyComment->content, 50) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('replyCommentId')
                                        <div class="invalid-feedback">{{ $message ?? '' }}</div>
                                    @enderror
                                </div>
                            @endif

                            <!-- Content -->
                            <div class="mb-3">
                                <label for="content" class="form-label text-light">Nội dung bình luận <span class="text-danger">*</span></label>
                                <textarea wire:model="content" id="content" rows="6"
                                          class="form-control bg-dark text-light border-light @error('content') is-invalid @enderror"
                                          placeholder="Nhập nội dung bình luận..."></textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Tối đa 1000 ký tự</small>
                            </div>

                            <!-- Bình luận con (child comments) -->
                            @if(!$comment->parent_comment_id)
                                <div class="mb-3">
                                    <label for="childCommentCount" class="form-label text-light">Số lượng bình luận con muốn thêm</label>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <input type="number"
                                                   wire:model.live="childCommentCount"
                                                   wire:change="generateChildComments"
                                                   class="form-control bg-dark text-light border-light"
                                                   min="0"
                                                   max="10"
                                                   placeholder="0">
                                        </div>
                                        <div class="col-md-9 d-flex align-items-center">
                                            <small class="text-muted">
                                                Nhập số từ 0-10. Sẽ tạo các form bình luận con tương ứng.
                                                <span class="text-info">Đang tạo bình luận con cho bình luận chính này.</span>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                @if(count($childComments) > 0)
                                    <hr class="border-light">
                                    <div class="row g-3 p-3">
                                        @foreach ($childComments as $index => $child)
                                            <div class="col-12 mb-4">
                                                <div class="card position-relative overflow-hidden" style="background-color: #1a1a1a; border: 1px solid #333;">
                                                    <div class="position-absolute top-0 start-0 h-100" style="width: 4px; background: linear-gradient(to bottom, #6b7280, #374151);"></div>
                                                    <button type="button"
                                                            class="btn btn-sm position-absolute delete-btn"
                                                            wire:click="removeChildComment({{ $index }})"
                                                            style="top: 1rem; right: 1rem; color: #6b7280; background: transparent; border: none; border-radius: 50%; padding: 0.5rem; transition: all 0.2s ease;">
                                                        <i class="fa-solid fa-x" style="margin-right: 0"></i></button>
                                                    <div class="card-body p-4">
                                                        <div class="mb-4">
                                                            <h3 class="text-white fw-semibold d-flex align-items-center gap-2" style="font-size: 1.125rem;">
                                                                <span class="d-flex align-items-center justify-content-center text-white fw-bold rounded-circle"
                                                                    style="width: 2rem; height: 2rem; background: linear-gradient(to right, #6b7280, #374151); font-size: 0.875rem;">
                                                                    {{ $index + 1 }}
                                                                </span>
                                                                Bình luận con {{ $index + 1 }}
                                                            </h3>
                                                        </div>
                                                        <div class="row g-3">
                                                            <div class="col-md-8">
                                                                <div class="mb-3">
                                                                    <label class="form-label text-light">Nội dung <span class="text-danger">*</span></label>
                                                                    <textarea wire:model="childComments.{{ $index }}.content"
                                                                              rows="3"
                                                                              placeholder="Nhập nội dung bình luận con..."
                                                                              class="form-control bg-dark text-light border-light @error('childComments.'.$index.'.content') is-invalid @enderror"></textarea>
                                                                    @error('childComments.'.$index.'.content')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="mb-3">
                                                                    <label class="form-label text-light">Trạng thái <span class="text-danger">*</span></label>
                                                                    <select wire:model="childComments.{{ $index }}.status"
                                                                            class="form-select bg-dark text-light border-light @error('childComments.'.$index.'.status') is-invalid @enderror">
                                                                        <option value="active">Hoạt động</option>
                                                                        <option value="hidden">Đã ẩn</option>
                                                                    </select>
                                                                    @error('childComments.'.$index.'.status')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            @endif

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between pt-3 border-top border-light">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save me-1"></i>Cập nhật bình luận
                                </button>
                                <a href="{{ route('admin.comments.index') }}" class="btn btn-outline-danger">
                                    <i class="fas fa-times me-1"></i>Hủy
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Comment Info -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card bg-dark border-light">
                    <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="my-1">Thông tin bình luận hiện tại</h5>
                    </div>
                    <div class="card-body bg-dark">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless text-light">
                                    <tr>
                                        <td><strong class="text-warning">Người tạo:</strong></td>
                                        <td>{{ $comment->user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Phim:</strong></td>
                                        <td>{{ $comment->movie->title }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Ngày tạo:</strong></td>
                                        <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Cập nhật cuối:</strong></td>
                                        <td>{{ $comment->updated_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                @if($comment->parent_comment_id)
                                    <div class="mb-3">
                                        <h6 class="text-warning">Bình luận cha:</h6>
                                        <div class="p-3 bg-secondary bg-opacity-25 rounded border">
                                            {{ Str::limit($comment->parent->content, 200) }}
                                            <div class="text-muted small mt-1">
                                                - {{ $comment->parent->user->name }}
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($comment->reply_comment_id)
                                    <div class="mb-3">
                                        <h6 class="text-warning">Trả lời cho:</h6>
                                        <div class="p-3 bg-secondary bg-opacity-25 rounded border">
                                            {{ Str::limit($comment->reply->content, 200) }}
                                            <div class="text-muted small mt-1">
                                                - {{ $comment->reply->user->name }}
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if(!$comment->parent_comment_id)
                                    @php
                                        $existingChildren = \App\Models\Comment::where('parent_comment_id', $comment->id)->count();
                                    @endphp
                                    <div class="mb-3">
                                        <h6 class="text-info">Thống kê:</h6>
                                        <div class="p-3 bg-info bg-opacity-10 rounded border border-info border-opacity-25">
                                            <div class="text-info">
                                                <i class="fas fa-comments me-1"></i>
                                                Đã có {{ $existingChildren }} bình luận con
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
