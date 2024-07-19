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
        Schema::table('commission_transaction_items', function (Blueprint $table) {
            $table->renameColumn('commission_paid_id', 'commission_payout_id');

            $table->foreign('referrer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('referral_reward_id')->references('id')->on('referral_rewards')->onDelete('cascade');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commission_transaction_items', function (Blueprint $table) {
            $table->dropForeign(['referrer_id']);
            $table->dropForeign(['referral_reward_id']);
            $table->dropForeign(['transaction_id']);

            $table->renameColumn('commission_payout_id', 'commission_paid_id');
        });
    }
};
