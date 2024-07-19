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
        Schema::create('referral_type_date_ranges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('referral_type_id')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->integer('month_duration')->nullable();
            $table->timestamps();

            $table->foreign('referral_type_id')->references('id')->on('referral_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_type_date_ranges');
    }
};
