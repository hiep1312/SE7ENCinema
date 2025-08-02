<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('booking_seats', function (Blueprint $table) {
            $table->timestamp('created_at')->useCurrent()->after('booking_id');
        });
    }

    public function down(): void
    {
        Schema::table('booking_seats', function (Blueprint $table) {
            $table->dropColumn('created_at');
        });
    }
};
