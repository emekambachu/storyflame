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
            $table->renameColumn('receiver_id', 'recipient_id');

            $table->foreign('referrer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('referral_type_id')->references('id')->on('referral_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referral_rewards', function (Blueprint $table) {
            $table->dropForeign(['referrer_id']);
            $table->dropForeign(['recipient_id']);
            $table->dropForeign(['referral_type_id']);

            $table->renameColumn('recipient_id', 'receiver_id');
        });
    }
};
