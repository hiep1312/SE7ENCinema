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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('showtime_id')->constrained()->onDelete('cascade');
            $table->string('booking_code')->unique();
            $table->bigInteger('total_price')->default(0);
            $table->string('transaction_code')->unique()->nullable();
            $table->dateTime('start_transaction')->nullable();
            $table->dateTime('end_transaction')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'paid'])->default('pending');
            $table->enum('payment_method', ['credit_card', 'bank_transfer', 'e_wallet', 'cash'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
