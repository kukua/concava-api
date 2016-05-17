<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDevicesTable extends Migration
{
	public function up()
	{
		Schema::create('user_devices', function (Blueprint $table) {
			$table->integer('user_id')->unsigned();
			$table->integer('device_id')->unsigned();
			$table->timestamps();

			$table->foreign('user_id')
				->references('id')->on('users')
				->onDelete('cascade');
			$table->foreign('device_id')
				->references('id')->on('devices')
				->onDelete('cascade');
		});
	}

	public function down()
	{
		Schema::drop('user_devices');
	}
}
