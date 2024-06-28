<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('data_points')) {
            Schema::create('data_points', function (Blueprint $table) {
                $table->id();

                $table->foreignId('achievement_id')->nullable();
                $table->string('slug')->unique();
                $table->string('name');
                $table->unsignedBigInteger('item_id')->unique();
                $table->string('purpose');
                $table->unsignedBigInteger('development_order');
                $table->unsignedSmallInteger('impact_score');
                $table->text('extraction_description')->nullable();
                $table->json('example')->nullable();
                $table->string('type')->default('text');
                $table->bigInteger('estimated_seconds')->default(0);
                $table->foreignId('admin_id')->nullable();
                $table->timestamp('deleted_at')->nullable();
                $table->timestamps();
                $table->engine = 'InnoDB';
            });
        }
    }

    public function down(): void
    {
        // temporarily disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('data_point_achievements');
        Schema::dropIfExists('data_points');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
