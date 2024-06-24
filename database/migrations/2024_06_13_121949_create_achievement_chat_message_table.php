<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('achievement_chat_message', function (Blueprint $table) {
            $table->foreignId('achievement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('chat_message_id')->constrained()->cascadeOnDelete();
            $table->primary(['achievement_id', 'chat_message_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achievement_chat_message');
    }
};
