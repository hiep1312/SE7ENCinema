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
        Schema::table('food_variants', function (Blueprint $table) {
            $table->renameColumn('name', 'value');
            $table->unsignedBigInteger('food_attribute_id')->nullable()->after('food_item_id');
            $table->foreign('food_attribute_id', 'fk_food_variants_attribute')
                ->references('id')
                ->on('food_attributes')
                ->onDelete('cascade');
            $table->dropForeign(['food_item_id']);
            $table->dropColumn('food_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('food_variants', function (Blueprint $table) {
            $table->dropForeign('fk_food_variants_attribute');
            $table->dropColumn('food_attribute_id');
            $table->renameColumn('value', 'name');
            $table->foreignId('food_item_id')->constrained()->onDelete('cascade');
        });
    }
};
