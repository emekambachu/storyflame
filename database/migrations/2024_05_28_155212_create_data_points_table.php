<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('data_points', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('achievement_id');
            $table->string('slug');

            $table->string('name');
            $table->string('type')->default('text');
            $table->string('category');
            $table->string('extraction_description')->nullable();
            $table->string('purpose');

            $table->unsignedSmallInteger('development_order');
            $table->unsignedSmallInteger('impact_score');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_points');
    }
};
