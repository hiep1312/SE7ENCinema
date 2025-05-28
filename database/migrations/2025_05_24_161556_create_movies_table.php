<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('duration'); // in minutes
            $table->date('release_date');
            $table->date('end_date')->nullable();
            $table->string('director')->nullable();
            $table->text('actors')->nullable();
            $table->enum('age_restriction', ['P', 'K', 'T13', 'T16', 'T18', 'C'])->default('P');
            $table->string('poster')->nullable();
            $table->string('trailer_url')->nullable();
            $table->enum('format', ['2D', '3D', '4DX', 'IMAX'])->default('2D');
            $table->bigInteger('price')->default(0);
            $table->enum('status', ['coming_soon', 'showing', 'ended'])->default('coming_soon');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
