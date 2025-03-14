<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('summaries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('item_id')->unique();
            $table->string('location');
            $table->text('purpose');
            $table->text('creation_prompt');
            $table->text('example_summary');
            $table->timestamp('published_at')->nullable();
            $table->foreignId('admin_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('data_point_summaries');
        Schema::dropIfExists('summaries');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
