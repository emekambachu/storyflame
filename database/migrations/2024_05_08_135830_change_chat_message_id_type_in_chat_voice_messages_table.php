<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('chat_voice_messages', function (Blueprint $table) {
//            $table->dropForeign(['chat_message_id']);
            $table->dropColumn('chat_message_id');
        });

        Schema::table('chat_voice_messages', function (Blueprint $table) {
            $table->foreignId('chat_message_id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('chat_voice_messages', function (Blueprint $table) {
            $table->dropForeign(['chat_message_id']);
            $table->dropColumn('chat_message_id');
        });

        Schema::table('chat_voice_messages', function (Blueprint $table) {
            $table->foreignId('chat_message_id');
        });
    }
};
