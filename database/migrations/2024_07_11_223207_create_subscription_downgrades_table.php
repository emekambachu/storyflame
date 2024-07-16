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
        Schema::create('subscription_downgrades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('subscription_id')->constrained('subscriptions');
            $table->foreignId('subscription_item_id')->constrained('subscription_items');
            $table->foreignId('new_product_id')->constrained('products');
            $table->foreignId('new_product_price_id')->constrained('product_prices');
            $table->timestamp('downgrade_at');
            $table->timestamp('downgraded_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_downgrades');
    }
};
