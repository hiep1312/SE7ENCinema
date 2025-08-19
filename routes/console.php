<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

Artisan::command('app:refresh', function () {
    Schema::withoutForeignKeyConstraints(function () {
        Schema::dropAllTables();
    });

    $directories = Storage::disk('public')->directories();
    !empty($directories) && array_walk($directories, fn($directory) => Storage::disk('public')->deleteDirectory($directory));
})->purpose('Drop all database tables while ignoring foreign key constraints');
