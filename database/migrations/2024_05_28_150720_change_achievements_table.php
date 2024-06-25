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
            // Drop columns if they exist
            $columnsToDrop = ['user_id', 'progress', 'completed_at'];
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('achievements', $column)) {

                    if(in_array($column, ['user_id', 'admin_id'])) {
                        if (DB::getSchemaBuilder()->getColumnType('achievements', $column) === 'uuid') {
                            $table->dropForeign([$column]); // use this if the foreign key is named 'user_id'
                        }
                    }else{
                        $table->dropColumn($column);
                    }

                }
            }

            // Add new columns
            $columnsToAdd = [
                'slug' => 'string',
                'extraction_description' => 'text',
                'subtitle' => 'string',
                'purpose' => 'string',
                'color' => 'string',
                'icon' => 'string',
                'icon_path' => 'string',
                'example' => 'string',
                'item_id' => 'unsignedBigInteger',
                'publish_at' => 'timestamp',
            ];

            foreach ($columnsToAdd as $column => $type) {
                if (!Schema::hasColumn('achievements', $column)) {
                    $table->$type($column);
                }
            }

            // Add unique constraint to 'item_id' after it has been created
            if (Schema::hasColumn('achievements', 'item_id')) {
                $table->unique('item_id');
            }

//            if (Schema::hasColumn('achievements', 'icon_path')) {
//                $table->string('icon_path')->nullable();
//            }

            if (Schema::hasColumn('achievements', 'publish_at')) {
                $table->timestamp('publish_at')->nullable();
            }

            // Add foreign key constraint to 'user_id'

        });

//        Schema::table('achievements', function (Blueprint $table) {
//            $table->dropConstrainedForeignId('user_id');
//            $table->dropColumn('progress');
//            $table->dropColumn('completed_at');
//
//            $table->string('slug')->after('id');
//            //$table->string('element')->after('name');
//            //$table->text('extraction_description')->nullable()->after('element');
//            $table->text('extraction_description')->nullable();
//            //$table->string('subtitle')->after('element');
//            $table->string('subtitle');
//            $table->string('purpose')->after('subtitle');
//            $table->string('color')->after('purpose');
//            $table->string('icon')->after('color');
//            $table->string('icon_path')->nullable();
//            $table->unsignedBigInteger('item_id')->unique();
//            $table->timestamp('publish_at')->nullable();
//        });
    }

    public function down(): void
    {
        // temporarily disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('achievements')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::table('achievements', function (Blueprint $table) {

            foreach ($this->columns as $column) {
                if (Schema::hasColumn('achievements', $column)) {
                    $table->dropColumn($column);
                }
            }

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

        });
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    private array $columns = [
        'slug',
        'item_id',
        'progress',
        //'element',
        'extraction_description',
        'subtitle',
        'purpose',
        'color',
        'icon',
//        'icon_path',
        'publish_at'
    ];
};
