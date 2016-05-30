<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTemplateIdAndNameUniqueIndexToAttributesTable extends Migration
{
	public function up()
	{
		Schema::table('attributes', function (Blueprint $table) {
			$table->unique(['template_id', 'name']);
		});
	}

	public function down()
	{
		Schema::table('attributes', function (Blueprint $table) {
			$table->dropUnique(['template_id', 'name']);
		});
	}
}
