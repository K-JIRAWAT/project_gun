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
        Schema::create('firearms', function (Blueprint $table) {
            $table->id();
            $table->string('images')->nullable();
            $table->string('name');
            $table->string('code')->nullable();
            $table->bigInteger('type');
            $table->bigInteger('stock');
            $table->string('created_by', 100)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('deleted_by', 100)->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('firearms');
    }
};
