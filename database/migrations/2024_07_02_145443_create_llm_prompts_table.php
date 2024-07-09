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
        Schema::create('llm_prompts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('current_prompt_version_id')->nullable();
            $table->unsignedBigInteger('updated_by_user_id')->nullable();
            $table->timestamps();
            $table->engine = 'InnoDB';

//            $table->foreign('current_prompt_version_id')->references('id')->on('llm_prompt_versions')->onDelete('set null');
//            $table->foreign('updated_by_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('llm_prompt_versions', function (Blueprint $table) {
            $table->dropForeign(['llm_prompt_id']);
        });

        Schema::dropIfExists('llm_prompts');
    }
};
