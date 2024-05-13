<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('achievements', function (Blueprint $table) {
            $table->timestamp('completed_at')->nullable();
        });

        // Set all completed achievements to the current time
        foreach (\App\Models\Achievement::whereNotNull('progress')->get() as $achievement) {
            if ($achievement->progress >= 100) {
                $achievement->completed_at = now();
                $achievement->save();
            }
        }
    }

    public function down(): void
    {
        Schema::table('achievements', function (Blueprint $table) {
            $table->dropColumn('completed_at');
        });
    }
};
