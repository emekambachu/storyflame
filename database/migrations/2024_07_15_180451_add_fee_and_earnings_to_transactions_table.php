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
        Schema::table('transactions', function (Blueprint $table) {
            // add discount column after total
            $table->unsignedInteger('discount')->default(0)->after('total');
            $table->unsignedInteger('fee')->default(0)->after('discount');
            $table->unsignedInteger('earnings')->default(0)->after('tax');
            $table->decimal('proration_rate')->nullable()->after('earnings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('discount');
            $table->dropColumn('fee');
            $table->dropColumn('earnings');
            $table->dropColumn('proration_rate');
        });
    }
};
