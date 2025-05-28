<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MovieController extends Controller
{
   public function index(Request $request)
{
    // Validate dữ liệu đầu vào
    $validated = $request->validate([
        'search' => 'nullable|string|max:255',
        'genre_id' => 'nullable|exists:genres,id',
        'status' => 'nullable|in:coming_soon,showing,ended',
    ]);

    $search = $validated['search'] ?? null;
    $genreId = $validated['genre_id'] ?? null;
    $status = $validated['status'] ?? null;

    $query = Movie::query()->with('genres');

    if ($search) {
        $query->where('title', 'like', "%$search%");
    }

    if ($genreId) {
        $query->whereHas('genres', function ($q) use ($genreId) {
            $q->where('genres.id', $genreId);
        });
    }

    if ($status) {
        $query->where('status', $status);
    }

    // 👉 Sắp xếp phim mới nhất lên đầu
    $movies = $query->orderBy('created_at', 'desc')->paginate(10);

    $genres = Genre::all();

    return view('admin.movies.index', compact('movies', 'search', 'genres', 'genreId', 'status'));
}





    // Update trạng thái phim nhanh


    public function updateStatus(Request $request, Movie $movie)
    {
        $request->validate([
            'status' => 'required|in:coming_soon,showing,ended',
        ]);

        $status = $request->status;
        $today = Carbon::now()->startOfDay();
        $releaseDate = Carbon::parse($movie->release_date);
        $endDate = $movie->end_date ? Carbon::parse($movie->end_date) : null;

        if ($status == 'coming_soon' && $releaseDate->lte($today)) {
            return redirect()->back()->with('error', 'Không thể đặt trạng thái "Sắp chiếu" cho phim đã đến ngày phát hành hoặc đã chiếu.');
        }

        if ($status == 'showing') {
            if ($releaseDate->gt($today)) {
                return redirect()->back()->with('error', 'Không thể đặt trạng thái "Đang chiếu" cho phim chưa đến ngày phát hành.');
            }
            if ($endDate && $endDate->lt($today)) {
                return redirect()->back()->with('error', 'Không thể đặt trạng thái "Đang chiếu" cho phim đã kết thúc thời gian chiếu.');
            }
        }

        if ($status == 'ended') {
            if (!$endDate || $endDate->gte($today)) {
                return redirect()->back()->with('error', 'Không thể đặt trạng thái "Đã kết thúc" khi phim vẫn đang trong thời gian chiếu.');
            }
        }

        $movie->status = $status;
        $movie->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công');
    }


    // Các method create, store, edit, update, destroy giữ nguyên như bạn đã có

    public function create()
    {
        $genres = Genre::all();
        return view('admin.movies.create', compact('genres'));
    }


    public function store(Request $request)
    {
        // Mảng thông báo lỗi tùy chỉnh
        $messages = [
            'title.required' => 'Vui lòng nhập tên phim.',
            'title.max' => 'Tên phim không được vượt quá 255 ký tự.',
            'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
            'duration.required' => 'Vui lòng nhập thời lượng phim.',
            'duration.integer' => 'Thời lượng phải là số nguyên.',
            'duration.min' => 'Thời lượng phải lớn hơn hoặc bằng 1 phút.',
            'release_date.required' => 'Vui lòng nhập ngày phát hành.',
            'release_date.date' => 'Ngày phát hành không hợp lệ.',
            'release_date.after_or_equal' => 'Ngày phát hành phải là hôm nay hoặc trong tương lai.',
            'end_date.date' => 'Ngày kết thúc không hợp lệ.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải cùng hoặc sau ngày phát hành.',
            'poster.image' => 'Poster phải là file ảnh.',
            'poster.mimes' => 'Poster chỉ chấp nhận định dạng jpeg, png, jpg.',
            'poster.max' => 'Kích thước poster tối đa 2MB.',
            'trailer_url.url' => 'Link trailer không hợp lệ.',
            'genre_ids.required' => 'Vui lòng chọn ít nhất 1 thể loại.',
            'genre_ids.array' => 'Thể loại không hợp lệ.',
            'genre_ids.min' => 'Phải chọn ít nhất 1 thể loại.',
            'genre_ids.*.exists' => 'Thể loại chọn không tồn tại.',

        ];

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'duration' => 'required|integer|min:1',
            'release_date' => 'required|date|after_or_equal:today',  // Rule bắt buộc ngày >= hôm nay
            'end_date' => 'nullable|date|after_or_equal:release_date',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'trailer_url' => 'nullable|url',
            'genre_ids' => 'required|array|min:1',
            'genre_ids.*' => 'exists:genres,id',

        ], $messages);

        if ($request->hasFile('poster')) {
            $validated['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $movie = Movie::create($validated);

        if (!empty($validated['genre_ids'])) {
            $movie->genres()->sync($validated['genre_ids']);
        }

        return redirect()->route('admin.movies.index')->with('success', 'Thêm phim thành công!');
    }






    public function edit(Movie $movie)
    {
        // Nạp sẵn quan hệ genres nếu có
        $movie->load('genres');

        $genres = Genre::all();
        return view('admin.movies.edit', compact('movie', 'genres'));
    }


   public function update(Request $request, Movie $movie)
{
    $messages = [
        'title.required' => 'Vui lòng nhập tên phim.',
        'title.max' => 'Tên phim không được vượt quá 255 ký tự.',
        
        'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',

        'duration.required' => 'Vui lòng nhập thời lượng phim.',
        'duration.integer' => 'Thời lượng phải là số nguyên.',
        'duration.min' => 'Thời lượng phải lớn hơn hoặc bằng 1 phút.',

        'release_date.required' => 'Vui lòng nhập ngày phát hành.',
        'release_date.date' => 'Ngày phát hành không hợp lệ.',
        'release_date.after_or_equal' => 'Ngày phát hành phải là ngày hôm nay hoặc trong tương lai.',

        'end_date.date' => 'Ngày kết thúc không hợp lệ.',
        'end_date.after_or_equal' => 'Ngày kết thúc phải cùng hoặc sau ngày phát hành.',

        'poster.image' => 'Poster phải là file ảnh.',
        'poster.mimes' => 'Poster phải là ảnh định dạng jpeg, png hoặc jpg.',
        'poster.max' => 'Kích thước poster tối đa là 2MB.',

        'trailer_url.url' => 'Link trailer không hợp lệ.',

        'genre_ids.required' => 'Vui lòng chọn ít nhất 1 thể loại.',
        'genre_ids.array' => 'Thể loại không hợp lệ.',
        'genre_ids.min' => 'Phải chọn ít nhất 1 thể loại.',
        'genre_ids.*.exists' => 'Thể loại chọn không tồn tại.',
    ];

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'duration' => 'required|integer|min:1',
        'release_date' => ['required', 'date', 'after_or_equal:' . date('Y-m-d')],
        'end_date' => 'nullable|date|after_or_equal:release_date',
        'poster' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'trailer_url' => 'nullable|url',
        'genre_ids' => 'required|array|min:1',
        'genre_ids.*' => 'exists:genres,id',
    ], $messages);

    if ($request->hasFile('poster')) {
        $validated['poster'] = $request->file('poster')->store('posters', 'public');
    }

    $movie->update($validated);

    $movie->genres()->sync($validated['genre_ids']);

    return redirect()->route('admin.movies.index')->with('success', 'Cập nhật thành công!');
}





    public function destroy(Movie $movie)
    {
        $movie->delete();
        return redirect()->route('admin.movies.index')->with('success', 'Xóa phim thành công!');
    }
    public function show(Movie $movie)
{
    $movie->load('genres'); // đảm bảo load genres
    return view('admin.movies.show', compact('movie'));
}

}
