<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::all(); // Lấy tất cả phim, có thể thêm phân trang nếu cần
        return view('client.movies.index', compact('movies'));
    }
}