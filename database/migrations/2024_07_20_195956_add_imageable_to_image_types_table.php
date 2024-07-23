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
            Schema::table('image_types', function (Blueprint $table) {
                $table->string('model_type')->nullable()->after('id'); // like 'App\Models\Vendor' or 'App\Models\Story'
                $table->index(['model_type']);
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('image_types', function (Blueprint $table) {
            Schema::table('image_types', function (Blueprint $table) {
                $table->dropIndex(['model_type']);
                $table->dropColumn(['model_type']);
            });
        });
    }
};
