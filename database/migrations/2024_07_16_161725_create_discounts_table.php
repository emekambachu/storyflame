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
        Schema::create('discounts', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('status')->nullable();
            $table->string('description')->nullable();
            $table->boolean('enabled_for_checkout');
            $table->string('code')->nullable();
            $table->string('type')->nullable();
            $table->string('amount')->nullable();
            $table->string('currency_code')->nullable();
            $table->boolean('recur')->default(false);
            $table->integer('maximum_recurring_intervals')->nullable();
            $table->integer('usage_limit')->nullable();
            $table->string('restrict_to')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->integer('times_used')->default(0);
            $table->json('custom_data')->nullable();
            $table->json('import_meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
