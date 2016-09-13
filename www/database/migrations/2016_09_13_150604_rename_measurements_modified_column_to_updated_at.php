<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\support\Facades\DB;

class RenameMeasurementsModifiedColumnToUpdatedAt extends Migration
{
	protected $connection = 'mysql-measurements';

	public function up()
	{
		$this->rename('modified', 'updated_at');
	}

	public function down()
	{
		$this->rename('updated_at', 'modified');
	}

	protected function rename ($from, $to) {
		$conn = $this->connection;

		if ( ! config("database.connections.$conn.enabled")) return;

		$tables = DB::connection($conn)->select('SHOW TABLES');

		foreach ($tables as & $table) {
			$vars = get_object_vars($table);
			$table = reset($vars);

			DB::connection($conn)->statement("ALTER TABLE `$table` CHANGE `$from` `$to` " .
				"DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL");
		}
	}
}
