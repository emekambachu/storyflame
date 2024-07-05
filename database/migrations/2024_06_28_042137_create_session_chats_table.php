<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('session_chats', function (Blueprint $table) {
            $table->id();

            $table->foreignId('chat_id')->constrained()->cascadeOnDelete();
            $table->nullableMorphs('target');
            $table->text('summary')->nullable();
            $table->boolean('persistent')->default(false)->comment('If the chat is persistent, it will not be deleted until finished manually');
            $table->timestamp('last_message_at');
            $table->schemalessAttributes('extra_attributes');
            $table->timestamp('finished_at')->nullable();
            $table->timestamp('summarized_at')->nullable()->comment('When the chat was summarized');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('session_chat_user_achievements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('session_chat_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_achievement_id')->constrained()->cascadeOnDelete();

            $table->timestamps();
        });

        Schema::create('session_chat_elements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('session_chat_id')->constrained()->cascadeOnDelete();
            $table->morphs('element');
            $table->text('action')->comment('Created, Updated, Deleted, or Restored');
            $table->timestamp('summarized_at')->nullable()->comment('When the element was summarized');

            $table->timestamps();
        });

        Schema::create('session_chat_chat_messages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('session_chat_id')->constrained()->cascadeOnDelete();
            $table->foreignId('chat_message_id')->constrained()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_chat_chat_messages');
        Schema::dropIfExists('session_chat_elements');
        Schema::dropIfExists('session_chat_user_achievements');
        Schema::dropIfExists('session_chats');
    }
};
