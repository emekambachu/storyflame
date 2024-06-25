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
        Schema::dropIfExists('data_point_achievements');

        Schema::create('data_point_achievements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('data_point_id')->nullable();
            $table->foreignUuid('achievement_id')->nullable();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_point_achievements');
    }
};
