<div>
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Thêm bình luận mới</h2>
            <a href="{{ route('admin.comments.index', ['movie_id' => $movieId]) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <!-- Form thông tin bình luận chính -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-dark">
                    <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="my-1">Bình luận chính</h5>
                    </div>
                    <div class="card-body bg-dark">
                        <form wire:submit.prevent="createComment">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="movieId" class="form-label text-light">Phim <span class="text-danger">*</span></label>
                                        <select wire:model.live="movieId"
                                                class="form-select bg-dark text-light border-light @error('movieId') is-invalid @enderror">
                                            <option value="">Chọn phim</option>
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
                                        <select wire:model="status" class="form-select bg-dark text-light border-light @error('status') is-invalid @enderror">
                                            <option value="active">Hoạt động</option>
                                            <option value="hidden">Đã ẩn</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Parent Comment Selection -->
                            @if($movieId && count($parentComments) > 0)
                                <div class="mb-3">
                                    <label for="parentCommentId" class="form-label text-light">Bình luận gốc (tùy chọn)</label>
                                    <select wire:model.live="parentCommentId"
                                            class="form-select bg-dark text-light border-light @error('parentCommentId') is-invalid @enderror">
                                        <option value="">Bình luận độc lập</option>
                                        @foreach($parentComments as $parent)
                                            <option value="{{ $parent->id }}">
                                                {{ $parent->user->name }} - {{ Str::limit($parent->content, 50) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('parentCommentId')
                                        <div class="invalid-feedback">{{ $message ?? '' }}</div>
                                    @enderror
                                    @php
                                        $selectedParent = $parentComments->where('id', $parentCommentId)->first();
                                    @endphp
                                    @if($selectedParent && $selectedParent->status == 'hidden')
                                        <div class="alert alert-info mt-2 py-2 px-3">
                                            <i class="fas fa-info-circle"></i> Bình luận cha này đã bị ẩn nhưng vẫn có thể tạo bình luận con.
                                        </div>
                                    @endif
                                    <small class="text-muted">Chỉ chọn nếu đây là bình luận trả lời</small>
                                </div>
                            @endif

                            <!-- Content -->
                            <div class="mb-3">
                                <label for="content" class="form-label text-light">Nội dung bình luận <span class="text-danger">*</span></label>
                                <textarea wire:model="content"
                                          rows="4"
                                          placeholder="Nhập nội dung bình luận chính..."
                                          class="form-control bg-dark text-light border-light @error('content') is-invalid @enderror"></textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Tối đa 1000 ký tự</small>
                            </div>

                            <!-- Input số lượng bình luận con -->
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
                                            @if($parentCommentId)
                                                <span class="text-info">Đang tạo bình luận con cho bình luận cha đã chọn.</span>
                                            @else
                                                <span class="text-warning">Đang tạo bình luận con cho bình luận chính.</span>
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Tạo bình luận
                                    @if(count($childComments) > 0)
                                        ({{ count($childComments) + 1 }} bình luận)
                                    @endif
                                </button>
                                <a href="{{ route('admin.comments.index', ['movie_id' => $movieId]) }}" class="btn btn-outline-danger">
                                    Hủy bỏ
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Các form bình luận con -->
        @if(count($childComments) > 0)
            <hr class="border-light">
            <h5 class="text-light">Bình luận con</h5>
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

        <!-- Sidebar Info -->
        @if($selectedMovie || $parentComment)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card bg-dark border-light">
                        <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <h5 class="my-1">Thông tin liên quan</h5>
                        </div>
                        <div class="card-body bg-dark">
                            <div class="row">
                                <!-- Selected Movie Info -->
                                @if($selectedMovie)
                                    <div class="col-md-6">
                                        <h6 class="text-warning">Thông tin phim</h6>
                                        <div class="d-flex align-items-start">
                                            @if($selectedMovie->poster)
                                                <img src="{{ asset('storage/' . $selectedMovie->poster) }}"
                                                     alt="{{ $selectedMovie->title }}"
                                                     class="me-3" style="width: 64px; height: 96px; object-fit: cover; border-radius: 4px;">
                                            @else
                                                <div class="me-3 bg-secondary rounded d-flex align-items-center justify-content-center" style="width: 64px; height: 96px;">
                                                    <i class="fas fa-film text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="text-light">{{ $selectedMovie->title }}</h6>
                                                <p class="text-muted small">{{ Str::limit($selectedMovie->description, 100) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Parent Comment Info -->
                                @if($parentComment)
                                    <div class="col-md-6">
                                        <h6 class="text-warning">Bình luận gốc</h6>
                                        <div class="d-flex align-items-start">
                                            @if($parentComment->user->avatar && !str_contains($parentComment->user->avatar, 'placeholder.com'))
                                                <img src="{{ asset('storage/' . $parentComment->user->avatar) }}"
                                                     alt="{{ $parentComment->user->name }}"
                                                     class="me-2" style="width: 32px; height: 32px; border-radius: 50%;">
                                            @else
                                                <div class="me-2 bg-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                    <i class="fas fa-user text-muted small"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="text-light small mb-1">{{ $parentComment->user->name }}</p>
                                                <p class="text-muted small">{{ Str::limit($parentComment->content, 100) }}</p>
                                                <small class="text-muted">{{ $parentComment->created_at->format('d/m/Y H:i') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
