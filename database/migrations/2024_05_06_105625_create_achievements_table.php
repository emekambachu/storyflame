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
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete();

            $table->softDeletes();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    public function down(): void
    {
        //Schema::dropIfExists('achievements');

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('achievements');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
