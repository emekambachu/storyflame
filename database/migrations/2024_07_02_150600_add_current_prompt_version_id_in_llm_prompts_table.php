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
        Schema::table('llm_prompts', function (Blueprint $table) {
            $table->foreignId('current_prompt_version_id')->nullable()->constrained('llm_prompt_versions')->onDelete('set null')->after('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('llm_prompts', function (Blueprint $table) {
            $table->dropForeign(['current_prompt_version_id']);
            $table->dropColumn('current_prompt_version_id');
        });
    }
};
