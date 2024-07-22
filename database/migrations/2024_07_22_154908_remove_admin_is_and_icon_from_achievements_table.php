<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $columns = ['icon', 'icon_path', 'admin_id'];

        Schema::table('achievements', function (Blueprint $table) use ($columns) {
            foreach ($columns as $column) {
                if (Schema::hasColumn('achievements', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('achievements', function (Blueprint $table) {
            if (!Schema::hasColumn('achievements', 'icon')) {
                $table->string('icon')->nullable();
            }

            if (!Schema::hasColumn('achievements', 'icon_path')) {
                $table->string('icon_path')->nullable();
            }

            if (!Schema::hasColumn('achievements', 'admin_id')) {
                $table->foreignId('admin_id')->constrained('admins');
            }
        });
    }
};
