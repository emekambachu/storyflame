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
        if(!Schema::hasTable('data_point_summaries')) {
            Schema::create('data_point_summaries', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->foreignUuid('data_point_id')->nullable();
                $table->foreignUuid('summary_id')->nullable();
                $table->timestamps();
                $table->engine = 'InnoDB';
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_point_summaries');
    }
};
