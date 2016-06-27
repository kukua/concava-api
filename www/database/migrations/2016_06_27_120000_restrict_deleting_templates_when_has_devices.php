<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RestrictDeletingTemplatesWhenHasDevices extends Migration
{
	public function up()
	{
		Schema::table('devices', function (Blueprint $table) {
			$table->dropForeign(['template_id']);

			$table->foreign('template_id')
				->references('id')->on('templates')
				->onDelete('restrict');
		});
	}

	public function down()
	{
		Schema::table('devices', function (Blueprint $table) {
			$table->dropForeign(['template_id']);

			$table->foreign('template_id')
				->references('id')->on('templates')
				->onDelete('set null');
		});
	}
}
