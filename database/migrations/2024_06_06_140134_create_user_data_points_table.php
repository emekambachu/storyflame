<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_data_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('data_point_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_achievement_id')->constrained()->cascadeOnDelete();
            $table->morphs('target');

            $table->json('data');
            $table->text('summary')->nullable();
            $table->boolean('is_latest')->default(true);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('story_data_points');
    }
};
