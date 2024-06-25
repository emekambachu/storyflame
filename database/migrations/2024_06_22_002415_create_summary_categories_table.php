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
        Schema::create('summary_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('summary_id');
            $table->uuid('category_id');
            $table->timestamps();

            $table->foreign('summary_id')
                ->references('id')->on('summaries')->onDelete('cascade');
            $table->foreign('category_id')
                ->references('id')->on('categories')->onDelete('cascade');
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('summary_categories');
    }
};
