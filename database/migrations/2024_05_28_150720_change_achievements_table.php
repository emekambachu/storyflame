<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        \Illuminate\Support\Facades\DB::table('achievements')->truncate();

        Schema::table('achievements', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn('progress');
            $table->dropColumn('completed_at');

            $table->string('slug')->after('id');
            $table->string('element')->after('name');
            $table->text('extraction_description')->nullable()->after('element');
            $table->string('subtitle')->after('element');
            $table->string('purpose')->after('subtitle');
            $table->string('color')->after('purpose');
            $table->string('icon')->after('color');
        });
    }

    public function down(): void
    {
        \Illuminate\Support\Facades\DB::table('achievements')->truncate();

        Schema::table('achievements', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->integer('progress')->default(0);
            $table->timestamp('completed_at')->nullable();

            $table->dropColumn('slug');
            $table->dropColumn('element');
            $table->dropColumn('extraction_description');
            $table->dropColumn('subtitle');
            $table->dropColumn('purpose');
            $table->dropColumn('color');
            $table->dropColumn('icon');
        });
    }
};
