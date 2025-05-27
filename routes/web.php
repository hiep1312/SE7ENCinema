<?php

use Illuminate\Support\Facades\Route;

Route::get('/user', function () {
    return view('admin.user.index');
});
Route::get('/', function () {
    return view('welcome');
});