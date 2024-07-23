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
        Schema::table('images', function (Blueprint $table) {
            $table->dropColumn('prompt');
            $table->string('path')->nullable()->change();
            $table->string('imageable_type')->after('id')->change();
            $table->unsignedBigInteger('imageable_id')->after('imageable_type')->change();
            $table->string('generation_service_name')->nullable()->after('group');
            $table->string('generation_id')->nullable()->after('generation_service_name');
            $table->json('generation_settings')->nullable()->after('generation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('images', function (Blueprint $table) {
            $table->string('prompt')->nullable()->after('group');
            $table->string('path')->change();
            $table->dropColumn('generation_service_name');
            $table->dropColumn('generation_id');
            $table->dropColumn('generation_settings');
        });
    }
};
