<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::table('chat_messages', function (Blueprint $table) {
			$table->string('type')->after('user_id')->default('text');
			$table->schemalessAttributes('extra_attributes')->after('content');
		});
	}

	public function down(): void
	{
		Schema::table('chat_messages', function (Blueprint $table) {
			$table->dropColumn('type');
			$table->dropColumn('extra_attributes');
		});
	}
};
