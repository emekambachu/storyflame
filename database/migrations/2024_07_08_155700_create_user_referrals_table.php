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
        Schema::create('user_referrals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('referrer_id')->nullable();
            $table->unsignedBigInteger('recipient_id')->nullable();
            $table->unsignedBigInteger('referral_type_id')->nullable();
            $table->decimal('recipient_discount_amount', 10, 2)->nullable();
            $table->decimal('referrer_discount_amount', 10, 2)->nullable();
            $table->decimal('referrer_commission_amount', 10, 2)->nullable();
            $table->timestamp('discount_ends_at')->nullable();
            $table->timestamp('commission_ends_at')->nullable();

            $table->foreign('referrer_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('recipient_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('referral_type_id')->references('id')->on('referral_types')->onDelete('set null');

            $table->timestamps();
            $table->engine('InnoDB');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_referrals');
    }
};
