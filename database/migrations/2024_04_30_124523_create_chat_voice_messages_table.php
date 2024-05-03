<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chat_voice_messages', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('filename')->comment('Filename containing the recording.');
            $table->text('transcription')->nullable()->comment('Nullable if still transcribing.');
            $table->foreignId('chat_message_id')->comment('Chat message this voice is attached to.');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_voice_messages');
    }
};
