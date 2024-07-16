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
        Schema::create('development_report_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('development_report_id')->constrained()->onDelete('cascade');
            $table->foreignId('summary_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('development_report_summaries');
    }
};
