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
        Schema::create('commission_transaction_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('referrer_id');
            $table->unsignedBigInteger('referral_reward_id');
            $table->unsignedBigInteger('transaction_id');
            $table->decimal('transaction_earnings', 10, 2);
            $table->decimal('commission_amount', 10, 2);
            $table->unsignedBigInteger('commission_paid_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_transaction_items');
    }
};
