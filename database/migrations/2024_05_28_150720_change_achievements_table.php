<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('achievements', function (Blueprint $table) {
            // Drop foreign key constraint if it exists
//            if (DB::getSchemaBuilder()->hasTable('achievements') && Schema::hasColumn('achievements', 'user_id')) {
//                $table->dropForeign(['user_id']);
//            }

            // Drop columns if they exist
            $columnsToDrop = ['progress', 'completed_at'];
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('achievements', $column)) {
                    $table->dropColumn($column);
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
                    $table->$type($column)->nullable();
                }
            }

            // Add unique constraint to 'item_id' after it has been created
            if (Schema::hasColumn('achievements', 'item_id')) {
                $table->unique('item_id');
            }
        });
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::table('achievements', function (Blueprint $table) {

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
        'progress',
        'extraction_description',
        'subtitle',
        'purpose',
        'color',
        'icon',
        'icon_path',
        'publish_at'
    ];
};
