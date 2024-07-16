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
        Schema::create('referral_rewards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('referrer_id');
            $table->unsignedBigInteger('receiver_id');
            $table->unsignedBigInteger('referral_type_id');
            $table->string('reward_type');
            $table->decimal('reward_amount', 10, 2)->nullable();
            $table->decimal('reward_percentage', 10, 2)->nullable();
            $table->timestamp('reward_starts_at')->nullable();
            $table->timestamp('reward_ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_rewards');
    }
};
