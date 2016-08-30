<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplateLabelsTable extends Migration
{
	public function up()
	{
		Schema::create('template_labels', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('template_id')->unsigned()->nullable();
			$table->string('name');
			$table->string('key');
			$table->text('value')->nullable();
			$table->timestamps();

			$table->foreign('template_id')
				->references('id')->on('templates')
				->onDelete('cascade');
			$table->unique(['template_id', 'key']);
		});
	}

	public function down()
	{
		Schema::drop('template_labels');
	}
}
