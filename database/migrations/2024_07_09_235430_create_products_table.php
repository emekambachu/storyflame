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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('paddle_id')->unique();
            $table->string('name');
            $table->enum('type', ['subscription', 'one_time_purchase']);
            $table->string('description')->nullable();
            $table->json('benefits')->nullable();
            $table->text('details')->nullable();
            $table->enum('status', ['active', 'archived'])->default('active');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
