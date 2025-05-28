<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
use App\Http\Controllers\admin\MovieController;

Route::prefix('admin/movies')->name('admin.movies.')->group(function () {
    Route::get('/', [MovieController::class, 'index'])->name('index');
    Route::post('/bulk-delete', [MovieController::class, 'bulkDelete'])->name('bulkDelete');
    // Route::post('/{movie}/update-status', [MovieController::class, 'updateStatus'])->name('updateStatus');

    Route::get('/create', [MovieController::class, 'create'])->name('create');
    Route::post('/store', [MovieController::class, 'store'])->name('store');
    Route::get('/{movie}/edit', [MovieController::class, 'edit'])->name('edit');
    Route::put('/{movie}', [MovieController::class, 'update'])->name('update');
    Route::delete('/{movie}', [MovieController::class, 'destroy'])->name('destroy');
});
Route::get('/admin/movies/{movie}', [MovieController::class, 'show'])->name('admin.movies.show');


Route::patch('/admin/movies/{movie}/status', [MovieController::class, 'updateStatus'])->name('admin.movies.updateStatus');

