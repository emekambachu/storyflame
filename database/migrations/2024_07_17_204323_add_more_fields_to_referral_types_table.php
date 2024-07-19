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
            $table->float('commission_percent')->nullable()->after('description');
            $table->float('discount_percent')->nullable()->after('commission_percent');
            $table->integer('month_duration')->nullable()->after('discount_percent');
            $table->timestamp('starts_at')->nullable()->after('month_duration');
            $table->timestamp('ends_at')->nullable()->after('starts_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referral_types', function (Blueprint $table) {
            $table->dropColumn('commission_percent');
            $table->dropColumn('discount_percent');
            $table->dropColumn('month_duration');
            $table->dropColumn('starts_at');
            $table->dropColumn('ends_at');
        });
    }
};
