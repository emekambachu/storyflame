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
        Schema::table('user_referral_types', function (Blueprint $table) {
            if(Schema::hasColumn('user_referral_types', 'user_id')) {
                $table->foreign('user_id')->references('id')->on('users');
            }
            if(Schema::hasColumn('user_referral_types', 'referral_type_id')) {
                $table->foreign('referral_type_id')->references('id')->on('referral_types');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_referral_types', function (Blueprint $table) {
            if(Schema::hasColumn('user_referral_types', 'user_id')) {
                $table->dropForeign(['user_id']);
            }
            if(Schema::hasColumn('user_referral_types', 'referral_type_id')) {
                $table->dropForeign(['referral_type_id']);
            }
        });
    }
};
