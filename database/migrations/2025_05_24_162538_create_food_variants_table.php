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
        Schema::create('food_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_item_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->bigInteger('price')->default(0);
            $table->string('image')->nullable();
            $table->integer('quantity_available')->default(0);
            $table->integer('limit')->nullable();
            $table->enum('status', ['available', 'out_of_stock', 'hidden'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_variants');
    }
};
