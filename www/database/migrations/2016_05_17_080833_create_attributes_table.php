<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributesTable extends Migration
{
	public function up()
	{
		Schema::create('attributes', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('template_id')->unsigned();
			$table->string('name', 32)->index();
			$table->integer('order')->unsigned()->default(0);
			$table->timestamps();

			$table->foreign('template_id')
				->references('id')->on('templates')
				->onDelete('cascade');
		});
	}

	public function down()
	{
		Schema::drop('attributes');
	}
}
