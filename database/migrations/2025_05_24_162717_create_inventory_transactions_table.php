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
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_item_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->enum('transaction_type', ['purchase', 'sales', 'waste', 'adjustment']);
            $table->date('transaction_date');
            $table->text('note')->nullable();
            $table->foreignId('staff_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
