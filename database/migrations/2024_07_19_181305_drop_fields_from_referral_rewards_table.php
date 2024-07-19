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
        Schema::table('referral_rewards', function (Blueprint $table) {
            if (Schema::hasColumn('referral_rewards', 'reward_type')) {
                $table->dropColumn('reward_type');
            }
            if (Schema::hasColumn('referral_rewards', 'reward_amount')) {
                $table->dropColumn('reward_amount');
            }
            if (Schema::hasColumn('referral_rewards', 'reward_percentage')) {
                $table->dropColumn('reward_percentage');
            }
            if (Schema::hasColumn('referral_rewards', 'reward_starts_at')) {
                $table->dropColumn('reward_starts_at');
            }
            if (Schema::hasColumn('referral_rewards', 'reward_ends_at')) {
                $table->dropColumn('reward_ends_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referral_rewards', function (Blueprint $table) {
            $table->string('reward_type');
            $table->decimal('reward_amount', 10, 2)->nullable();
            $table->decimal('reward_percentage', 10, 2)->nullable();
            $table->timestamp('reward_starts_at')->nullable();
            $table->timestamp('reward_ends_at')->nullable();
        });
    }
};
