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
        Schema::create('seat_holds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('showtime_id')->constrained()->onDelete('cascade');
            $table->foreignId('seat_id')->constrained()->onDelete('cascade');
            $table->string('session_id');
            $table->string('user_ip');
            $table->timestamp('held_at');
            $table->timestamp('expires_at');
            $table->enum('status', ['holding', 'expired', 'released'])->default('holding');
            $table->timestamps();

            $table->index(['showtime_id', 'seat_id']);
            $table->index(['session_id', 'status']);
            $table->index(['expires_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seat_holds');
    }
};
