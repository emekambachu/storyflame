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
        Schema::table('data_points', function (Blueprint $table) {
            if(Schema::hasColumn('data_points', 'item_id')){
                $table->dropColumn('item_id');
            }
            if(Schema::hasColumn('data_points', 'admin_id')){
                $table->dropColumn('admin_id');
            }
            if(!Schema::hasColumn('data_points', 'user_id')){
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
        Schema::table('data_points', function (Blueprint $table) {
            //
        });
    }
};
