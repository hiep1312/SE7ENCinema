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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('avatar')->nullable();
            $table->date('birthday')->nullable();
            $table->enum('gender', ['man', 'woman', 'other'])->default('other');
            $table->enum('role', ['user', 'staff', 'admin'])->default('user');
            $table->enum('status', ['active', 'inactive', 'banned'])->default('active');
            $table->timestamp('banned_at')->nullable();
            $table->text('ban_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['status', 'banned_at', 'ban_reason']);
        });
    }
};
