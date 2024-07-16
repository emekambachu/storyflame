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
        Schema::create('user_development_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->foreignId('development_report_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('status', ['available', 'processing', 'completed', 'failed', 'expired'])->default('available');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_development_reports');
    }
};
