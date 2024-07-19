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
        // if users.first_name does not exist run this
        if(!Schema::hasColumn('users', 'first_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('first_name')->nullable()->after('paddle_id');
                $table->string('last_name')->nullable()->after('first_name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
        });
    }
};
