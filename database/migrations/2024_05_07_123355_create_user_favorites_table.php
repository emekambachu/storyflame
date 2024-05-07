<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_favorites', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->uuidMorphs('favorite');
            $table->timestamps();

            $table->primary(['user_id', 'favorite_id', 'favorite_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_favorites');
    }
};
