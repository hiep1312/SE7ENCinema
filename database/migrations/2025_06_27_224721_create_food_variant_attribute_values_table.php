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
        Schema::create('food_variant_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_variant_id')->constrained()->onDelete('cascade');
            $table->foreignId('food_attribute_value_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_variant_attribute_values');
    }
};
