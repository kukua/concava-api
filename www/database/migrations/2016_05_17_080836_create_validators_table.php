<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateValidatorsTable extends Migration
{
	public function up()
	{
		Schema::create('validators', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('attribute_id')->unsigned();
			$table->string('type', 32);
			$table->string('value');
			$table->integer('order')->unsigned()->default(0);
			$table->timestamps();

			$table->foreign('attribute_id')
				->references('id')->on('attributes')
				->onDelete('cascade');
		});
	}

	public function down()
	{
		Schema::drop('validators');
	}
}
