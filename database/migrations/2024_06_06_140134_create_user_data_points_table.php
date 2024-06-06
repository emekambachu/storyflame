<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_data_points', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('data_point_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_achievement_id')->constrained()->cascadeOnDelete();
            $table->uuidMorphs('target');

            $table->json('data');
            $table->text('summary');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('story_data_points');
    }
};
