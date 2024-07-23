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
        Schema::table('summaries', function (Blueprint $table) {
            if (Schema::hasColumn('summaries', 'item_id')) {
                $table->dropColumn('item_id');
            }
            if (Schema::hasColumn('summaries', 'admin_id')) {
                $table->dropColumn('admin_id');
            }
            if (!Schema::hasColumn('summaries', 'user_id')) {
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('summaries', function (Blueprint $table) {
            //
        });
    }
};
