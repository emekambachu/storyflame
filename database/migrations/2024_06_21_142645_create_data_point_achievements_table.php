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
            $table->id();
            $table->foreignId('data_point_id')->nullable();
            $table->foreignId('achievement_id')->nullable();
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
