<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameUsersActiveColumnToIsActive extends Migration
{
	public function up()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->renameColumn('active', 'is_active');
		});
	}

	public function down()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->renameColumn('is_active', 'active');
		});
	}
}
