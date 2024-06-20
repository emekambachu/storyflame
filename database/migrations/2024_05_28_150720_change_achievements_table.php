<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        DB::table('achievements')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

        Schema::table('achievements', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn('progress');
            $table->dropColumn('completed_at');

            $table->string('slug')->after('id');
            //$table->string('element')->after('name');
            //$table->text('extraction_description')->nullable()->after('element');
            $table->text('extraction_description')->nullable();
            //$table->string('subtitle')->after('element');
            $table->string('subtitle');
            $table->string('purpose')->after('subtitle');
            $table->string('color')->after('purpose');
            $table->string('icon')->after('color');
            $table->string('icon_path')->nullable();
            $table->unsignedBigInteger('item_id')->unique()->after('slug');
            $table->timestamp('publish_at')->nullable();

        });
    }

    public function down(): void
    {
        // temporarily disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('achievements')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::table('achievements', function (Blueprint $table) {
            //$table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
//            $table->integer('progress')->default(0);
//            $table->timestamp('completed_at')->nullable();
//
//            $table->dropColumn('slug');
//            $table->dropColumn('element');
//            $table->dropColumn('extraction_description');
//            $table->dropColumn('subtitle');
//            $table->dropColumn('purpose');
//            $table->dropColumn('color');
//            $table->dropColumn('icon');

            foreach ($this->columns as $column) {
                if (Schema::hasColumn('achievements', $column)) {
                    $table->dropColumn($column);
                }
            }

        });
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    private array $columns = [
        'slug',
        'item_id',
        //'element',
        'extraction_description',
        'subtitle',
        'purpose',
        'color',
        'icon',
        'icon_path',
        'item_id',
        'publish_at'
    ];
};
