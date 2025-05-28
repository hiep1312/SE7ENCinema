@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h1>Chỉnh sửa phim: {{ $movie->title }}</h1>

    {{-- Hiển thị lỗi chung nếu có --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Lỗi:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.movies.update', $movie) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Tiêu đề</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" 
                value="{{ old('title', $movie->title) }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="duration" class="form-label">Thời lượng (phút)</label>
            <input type="number" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" 
                value="{{ old('duration', $movie->duration) }}" min="1" required>
            @error('duration')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="release_date" class="form-label">Ngày phát hành</label>
            <input type="date" class="form-control @error('release_date') is-invalid @enderror" id="release_date" name="release_date" 
                value="{{ old('release_date', $movie->release_date->format('Y-m-d')) }}" required>
            @error('release_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="end_date" class="form-label">Ngày kết thúc (không bắt buộc)</label>
            <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" 
                value="{{ old('end_date', optional($movie->end_date)->format('Y-m-d')) }}">
            <small class="form-text text-muted">Ngày kết thúc phải cùng hoặc sau ngày phát hành.</small>
            @error('end_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="poster" class="form-label">Poster (ảnh)</label>
            @if($movie->poster)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $movie->poster) }}" alt="{{ $movie->title }}" style="width: 120px; height: auto;">
                </div>
            @endif
            <input type="file" class="form-control @error('poster') is-invalid @enderror" id="poster" name="poster" accept="image/*">
            <small class="form-text text-muted">Chọn để thay đổi ảnh poster (bỏ trống nếu không thay đổi)</small>
            @error('poster')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="trailer_url" class="form-label">Link trailer</label>
            <input type="url" class="form-control @error('trailer_url') is-invalid @enderror" id="trailer_url" name="trailer_url" 
                value="{{ old('trailer_url', $movie->trailer_url) }}">
            @error('trailer_url')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" placeholder="Nhập mô tả chi tiết về phim...">{{ old('description', $movie->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Thể loại</label>
            <div class="row">
                @foreach ($genres as $genre)
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input @error('genre_ids') is-invalid @enderror" type="checkbox" name="genre_ids[]" value="{{ $genre->id }}"
                            id="genre_{{ $genre->id }}"
                            {{ (is_array(old('genre_ids', $movie->genres->pluck('id')->toArray())) && in_array($genre->id, old('genre_ids', $movie->genres->pluck('id')->toArray()))) ? 'checked' : '' }}>
                        <label class="form-check-label" for="genre_{{ $genre->id }}">
                            {{ $genre->name }}
                        </label>
                    </div>
                </div>
                @endforeach
            </div>
            @error('genre_ids')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật phim</button>
        <a href="{{ route('admin.movies.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
