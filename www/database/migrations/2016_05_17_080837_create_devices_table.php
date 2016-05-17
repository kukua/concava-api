<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicesTable extends Migration
{
	public function up()
	{
		Schema::create('devices', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('template_id')->unsigned()->nullable();
			$table->string('udid', 16)->unique();
			$table->string('name');
			$table->timestamps();

			$table->foreign('template_id')
				->references('id')->on('templates')
				->onDelete('set null');
		});
	}

	public function down()
	{
		Schema::drop('devices');
	}
}
