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
        Schema::table('user_development_reports', function (Blueprint $table) {
            //update status column, which is currently enum to include "pending"
            $table->enum('status', ['available', 'processing', 'completed', 'failed', 'expired', 'pending'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_development_reports', function (Blueprint $table) {
            $table->enum('status', ['available', 'processing', 'completed', 'failed', 'expired'])->change();
        });
    }
};
