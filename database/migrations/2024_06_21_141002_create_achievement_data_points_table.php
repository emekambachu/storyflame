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
        Schema::create('achievement_data_points', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('achievement_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuid('data_point_id')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievement_data_points');
    }
};
