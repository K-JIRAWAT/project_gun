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
            $table->bigInteger('role_id')->nullable()->comment('รหัสสิทธิ์การใช้งาน');
            $table->string('firstname')->nullable()->comment('ชื่อจริง');
            $table->string('lastname')->nullable()->comment('นามสกุล');
            $table->string('username')->nullable();
            $table->string('images')->nullable();
            $table->bigInteger('sector')->nullable();
            $table->bigInteger('status')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('created_by', 100)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('deleted_by', 100)->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('updatePassword_by', 100)->nullable();
            $table->timestamp('updatePassword_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
