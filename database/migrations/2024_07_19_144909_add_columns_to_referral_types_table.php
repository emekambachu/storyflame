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

            if(Schema::hasColumn('referral_types', 'discount_percent')) {
                $table->dropColumn('discount_percent');
            }

            $table->float('referrer_discount_percent')->nullable();
            $table->float('recipient_discount_percent')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referral_types', function (Blueprint $table) {
            //
        });
    }
};
