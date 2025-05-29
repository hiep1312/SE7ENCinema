<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
use App\Http\Controllers\admin\MovieController;

Route::prefix('admin/movies')->name('admin.movies.')->group(function () {
    Route::get('/', [MovieController::class, 'index'])->name('index');
    Route::post('/bulk-delete', [MovieController::class, 'bulkDelete'])->name('bulkDelete');

    Route::get('/create', [MovieController::class, 'create'])->name('create');
    Route::post('/store', [MovieController::class, 'store'])->name('store');
    Route::get('/{movie}/edit', [MovieController::class, 'edit'])->name('edit');
    Route::put('/{movie}', [MovieController::class, 'update'])->name('update');
    Route::delete('/{movie}', [MovieController::class, 'destroy'])->name('destroy');

    // Sửa ở đây, bỏ "movies" đi để thành "/admin/movies/trash"
    Route::get('/trash', [MovieController::class, 'trash'])->name('trash');
    Route::patch('/{id}/restore', [MovieController::class, 'restore'])->name('restore');
});

Route::get('/admin/movies/{movie}', [MovieController::class, 'show'])->name('admin.movies.show');
Route::delete('admin/movies/{id}/force-delete', [MovieController::class, 'forceDelete'])->name('admin.movies.forceDelete');


Route::patch('/admin/movies/{movie}/status', [MovieController::class, 'updateStatus'])->name('admin.movies.updateStatus');

