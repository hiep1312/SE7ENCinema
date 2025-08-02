<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_violations', function (Blueprint $table) {
            $table->id();
            $table->string('session_id');
            $table->string('user_ip');
            $table->enum('violation_type', ['seat_timeout', 'payment_timeout'])->default('seat_timeout');
            $table->text('violation_details')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();

            $table->index(['session_id', 'violation_type']);
            $table->index(['user_ip', 'violation_type']);
        });

        Schema::create('banned_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id');
            $table->string('user_ip');
            $table->timestamp('banned_at');
            $table->timestamp('banned_until');
            $table->text('reason');
            $table->timestamps();

            $table->index(['session_id', 'banned_until']);
            $table->index(['user_ip', 'banned_until']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('banned_sessions');
        Schema::dropIfExists('user_violations');
    }
};
