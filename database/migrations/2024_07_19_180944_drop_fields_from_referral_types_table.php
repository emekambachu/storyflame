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
        Schema::table('referral_types', function (Blueprint $table) {
            if (Schema::hasColumn('referral_types', 'starts_at')) {
                $table->dropColumn('starts_at');
            }
            if (Schema::hasColumn('referral_types', 'ends_at')) {
                $table->dropColumn('ends_at');
            }
            if (Schema::hasColumn('referral_types', 'discount_percent')) {
                $table->dropColumn('discount_percent');
            }
            if (Schema::hasColumn('referral_types', 'month_duration')) {
                $table->dropColumn('month_duration');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referral_types', function (Blueprint $table) {
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->float('commission_percent')->nullable();
            $table->float('discount_percent')->nullable();
            $table->integer('month_duration')->nullable();
        });
    }
};
