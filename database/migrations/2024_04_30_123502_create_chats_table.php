<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('type');
            $table->foreignUuid('sender_id')->comment('User that initiated this chat.')->constrained('users');
            $table->foreignUuid('recipient_id')->nullable()->comment('The person user is speaking to, typically null which means AI')->constrained('users');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
