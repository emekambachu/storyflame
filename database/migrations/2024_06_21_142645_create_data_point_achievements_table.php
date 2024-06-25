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
            $table->uuid('data_point_id');
            $table->uuid('achievement_id');
            $table->timestamps();

            $table->foreign('data_point_id')
                ->references('id')->on('data_points')->onDelete('cascade');
            $table->foreign('achievement_id')
                ->references('id')->on('achievements')->onDelete('cascade');
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
