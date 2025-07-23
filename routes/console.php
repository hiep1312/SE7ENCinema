<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

Artisan::command('app:refresh', function () {
    Schema::withoutForeignKeyConstraints(function () {
        Schema::dropAllTables();
    });
})->purpose('Drop all database tables while ignoring foreign key constraints');
