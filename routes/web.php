<?php

use App\Livewire\Admin\Food\FoodCreate;
use App\Livewire\Admin\Food\FoodDelete;
use App\Livewire\Admin\Food\FoodDetail;
use App\Livewire\Admin\Food\FoodEdit;
use App\Livewire\Admin\Food\FoodList;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin/food')->group(function () {

    Route::get('/', FoodList::class)->name('admin.food.list');
    Route::get('/create', FoodCreate::class)->name('admin.food.create');
    Route::get('delete/{id}', [FoodDelete::class, 'delete'])->name('admin.food.delete');
    Route::get('/detail/{id}', FoodDetail::class)->name('admin.food.detail');
    Route::get('/edit/{id}', FoodEdit::class)->name('admin.food.edit');
});

