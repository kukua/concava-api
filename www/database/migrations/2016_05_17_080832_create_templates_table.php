<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplatesTable extends Migration
{
	public function up()
	{
		Schema::create('templates', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned()->nullable();
			$table->string('name');
			$table->timestamps();

			$table->foreign('user_id')
				->references('id')->on('users')
				->onDelete('set null');
		});
	}

	public function down()
	{
		Schema::drop('templates');
	}
}
