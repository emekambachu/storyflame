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
        // Check if the table exists
        if (Schema::hasTable('summary_links')) {
            // Drop the table
            Schema::dropIfExists('summary_links');
        }

        // Create the table
        Schema::create('summary_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('summary_id')->constrained()->cascadeOnDelete();
            $table->foreignId('linked_summary_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('summary_links');
    }
};
