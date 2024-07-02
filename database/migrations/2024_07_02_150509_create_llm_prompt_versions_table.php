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
        Schema::create('llm_prompt_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('llm_prompt_id')->nullable();
            $table->foreign('llm_prompt_id')->references('id')->on('llm_prompts')->onDelete('cascade');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llm_prompt_versions');
    }
};
