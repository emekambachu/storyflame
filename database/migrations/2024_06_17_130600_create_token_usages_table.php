<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('token_usages', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->foreignId('user_id');
            $table->morphs('target');
            $table->string('model');
            $table->unsignedSmallInteger('input_tokens')->default(0);
            $table->unsignedSmallInteger('output_tokens')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('token_usages');
    }
};
