@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h1>Thùng rác - Danh sách phim đã xóa</h1>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <a href="{{ route('admin.movies.index') }}" class="btn btn-primary">← Quay lại danh sách phim</a>
        
        {{-- Form lọc --}}
        <form action="{{ route('admin.movies.trash') }}" method="GET" class="d-flex gap-2 align-items-center">
            <input type="text" name="title" class="form-control form-control-sm" placeholder="Tiêu đề" value="{{ request('title') }}">
            
            <select name="genre_id" class="form-select form-select-sm">
                <option value="">-- Thể loại --</option>
                @foreach($genres as $genre)
                <option value="{{ $genre->id }}" {{ request('genre_id') == $genre->id ? 'selected' : '' }}>{{ $genre->name }}</option>
                @endforeach
            </select>
            
            <input type="date" name="deleted_at_from" class="form-control form-control-sm" value="{{ request('deleted_at_from') }}" title="Ngày xóa từ">
            <input type="date" name="deleted_at_to" class="form-control form-control-sm" value="{{ request('deleted_at_to') }}" title="Ngày xóa đến">
            
            <button type="submit" class="btn btn-sm btn-secondary">Lọc</button>
            <a href="{{ route('admin.movies.trash') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
        </form>
    </div>

    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th>STT</th>
                <th>Poster</th>
                <th>Tiêu đề</th>
                <th>Thể loại</th>
                <th>Thời lượng (phút)</th>
                <th>Ngày xóa</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($movies as $movie)
            <tr>
                <td>{{ ($movies->currentPage() - 1) * $movies->perPage() + $loop->iteration }}</td>
                <td style="width: 100px;">
                    @if($movie->poster)
                    <img src="{{ asset('storage/' . $movie->poster) }}" alt="{{ $movie->title }}" class="img-fluid rounded shadow" style="max-width: 100px;">
                    @else
                    <span>Chưa có poster</span>
                    @endif
                </td>
                <td>{{ $movie->title }}</td>
                <td>
                    @if($movie->genres && $movie->genres->count())
                    {{ $movie->genres->pluck('name')->implode(', ') }}
                    @else
                    <span>Không có</span>
                    @endif
                </td>
                <td>{{ $movie->duration }}</td>
                <td>{{ $movie->deleted_at->format('d/m/Y H:i') }}</td>
                <td>
                    <form action="{{ route('admin.movies.restore', $movie->id) }}" method="POST" onsubmit="return confirm('Khôi phục phim này?')" style="display:inline-block;">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-sm btn-success mb-1" title="Khôi phục">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </button>
                    </form>

                    <form action="{{ route('admin.movies.forceDelete', $movie->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa phim này vĩnh viễn?')" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger mb-1" title="Xóa vĩnh viễn">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Không có phim nào trong thùng rác.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $movies->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
