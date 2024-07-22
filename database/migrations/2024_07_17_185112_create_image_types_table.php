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
        Schema::create('image_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('purpose')->nullable();
            $table->string('creation_prompt')->nullable();
            $table->string('example_prompt')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('images', function (Blueprint $table) {
            $table->foreignId('image_type_id')->nullable()->constrained()->onDelete('set null')->after('group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image_types');
        Schema::table('images', function (Blueprint $table) {
            $table->dropForeign(['image_type_id']);
            $table->dropColumn('image_type_id');
        });
    }
};
