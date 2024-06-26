<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_data_point_chat_messages', function (Blueprint $table) {
            $table->foreignId('user_data_point_id')->constrained()->cascadeOnDelete();
            $table->foreignId('chat_message_id')->constrained()->cascadeOnDelete();
            $table->primary(['user_data_point_id', 'chat_message_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_data_point_chat_messages');
    }
};
