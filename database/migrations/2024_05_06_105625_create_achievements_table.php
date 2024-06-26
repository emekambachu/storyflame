<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('achievements', static function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('icon_path')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('admin_id')->nullable();

            $table->softDeletes();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::table('achievements', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['admin_id']);
        });
        Schema::dropIfExists('achievements');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
