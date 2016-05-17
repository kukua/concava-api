<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalibratorsTable extends Migration
{
	public function up()
	{
		Schema::create('calibrators', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('attribute_id')->unsigned();
			$table->text('fn');
			$table->integer('order')->unsigned()->default(0);
			$table->timestamps();

			$table->foreign('attribute_id')
				->references('id')->on('attributes')
				->onDelete('cascade');
		});
	}

	public function down()
	{
		Schema::drop('calibrators');
	}
}
