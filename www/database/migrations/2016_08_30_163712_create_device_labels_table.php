<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceLabelsTable extends Migration
{
	public function up()
	{
		Schema::create('device_labels', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('device_id')->unsigned()->nullable();
			$table->string('name');
			$table->string('key');
			$table->text('value')->nullable();
			$table->timestamps();

			$table->foreign('device_id')
				->references('id')->on('devices')
				->onDelete('cascade');
			$table->unique(['device_id', 'key']);
		});
	}

	public function down()
	{
		Schema::drop('device_labels');
	}
}
