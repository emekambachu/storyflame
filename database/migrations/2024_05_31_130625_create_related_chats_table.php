<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('related_chats', function (Blueprint $table) {
            $table->foreignUuid('chat_id')->constrained()->cascadeOnDelete();
            $table->uuidMorphs('related');

            $table->primary(['chat_id', 'related_id', 'related_type']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('related_chats');
    }
};
