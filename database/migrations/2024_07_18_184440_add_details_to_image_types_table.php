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
        Schema::table('image_types', function (Blueprint $table) {
            $table->integer('height')->after('slug')->default(1024);
            $table->integer('width')->after('height')->default(1024);
            $table->json('prompt_settings')->after('creation_prompt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('image_types', function (Blueprint $table) {
            $table->dropColumn('height');
            $table->dropColumn('width');
            $table->dropColumn('prompt_settings');
        });
    }
};
