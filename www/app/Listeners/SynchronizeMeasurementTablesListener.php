<?php

namespace App\Listeners;

use App\Models\Device;
use App\Models\Attribute;
use Schema;
use DB;
use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;

class SynchronizeMeasurementTablesListener {
	protected $connection = 'mysql-measurements';

	function onDeviceCreated (Device $device) {
		$this->createTable($device);
	}

	function onDeviceUpdated (Device $device) {
		if ($device->getOriginal()['udid'] !== $device->udid) {
			$this->renameTable(
				$this->getTableName($device, true),
				$this->getTableName($device)
			);
		}
	}

	function onDeviceDeleted (Device $device) {
		$this->backupTable($device);
	}

	function onAttributeCreated (Attribute $attribute) {
		foreach ($attribute->template->devices as $device) {
			$this->addTableColumn($device, $attribute);
		}
	}

	function onAttributeUpdated (Attribute $attribute) {
		foreach ($attribute->template->devices as $device) {
			$this->updateTableColumn($device, $attribute);
		}
	}

	protected function getTableName (Device $device, $original = false) {
		if ($original) {
			return $device->getOriginal()['udid'];
		}
		return $device->udid;
	}

	protected function getColumnType ($name) {
		$parts = explode('_', snake_case($name));
		if (in_array('timestamp', $parts)) {
			return 'timestamp';
		}
		return 'float';
	}

	protected function addColumn ($table, $name) {
		$type = $this->getColumnType($name);
		$column = $table->$type($name)->nullable();

		if ($type === 'timestamp') {
			$column->nullable(false)->default('0000-00-00 00:00:00');
		}

		return $column;
	}

	protected function renameTable ($from, $to) {
		try {
			Schema::connection($this->connection)->rename($from, $to);
			return true;
		} catch (QueryException $e) {
			return false;
		}
	}

	protected function backupTable (Device $device) {
		$table = $this->getTableName($device, true);
		$date = date('YmdHis');
		$newTable = "$table-$date";
		$this->renameTable($table, $newTable);
	}

	protected function getAttributeNames (Device $device) {
		return $device->template->attributes()->pluck('name')->toArray();
	}

	protected function createTable (Device $device) {
		$self = $this;
		$conn = Schema::connection($this->connection);

		$conn->create($this->getTableName($device), function (Blueprint $table) use ($self, $device) {
			// Add timestamp column
			$attributes = $self->getAttributeNames($device);

			if ( ! in_array('timestamp', array_map('strtolower', $attributes))) {
				$self->addColumn($table, 'timestamp');
			}

			// Add custom columns
			foreach ($attributes as & $name) {
				$self->addColumn($table, $name);
			}

			// Add default columns
			$table->binary('_raw')->nullable();
			$table->timestamp('modified')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
		});
	}

	protected function addTableColumn (Device $device, Attribute $attribute) {
		$self = $this;
		$conn = Schema::connection($this->connection);
		$table = $this->getTableName($device);

		// Create if not exists
		if ( ! $conn->hasTable($table)) {
			$this->createTable($device);
			return;
		}

		// Alter existing table
		$tableName = & $table;
		$conn->table($table, function (Blueprint $table) use ($self, $conn, $tableName, $device, $attribute) {
			// Skip if exists
			$name = $attribute->name;

			if ($conn->hasColumn($tableName, $name)) {
				return;
			}

			// Add column
			$column = $self->addColumn($table, $name);

			// Place column after previous attribute
			$attributes = $self->getAttributeNames($device);
			$index = array_search($name, $attributes);

			if ($index === 0) {
				$column->first();
			} else if ($index > 0) {
				$prev = $attributes[$index - 1];
				if ( ! empty($prev)) {
					$column->after($prev);
				}
			}
		});
	}

	protected function updateTableColumn (Device $device, Attribute $attribute) {
		$self = $this;
		$conn = Schema::connection($this->connection);

		$conn->table($this->getTableName($device), function (Blueprint $table) use ($self, $device, $attribute) {
			// Recreate table on type change
			$oldType = $self->getColumnType($attribute->getOriginal()['name']);
			$type = $self->getColumnType($attribute->name);
			if ($oldType !== $type) {
				$self->backupTable($device);
				$self->createTable($device);
				return;
			}

			// Rename column on name change
			// TODO(mauvm): Test if working after https://goo.gl/os9wpr is solved.
			$oldName = $attribute->getOriginal()['name'];
			$name = $attribute->name;
			if ($oldName !== $name) {
				$table->renameColumn($oldName, $name);
			}
		});
	}

	function subscribe ($events) {
		$conn = $this->connection;

		if ( ! config("database.connections.$conn.enabled")) {
			return;
		}

		$events->listen(
			'eloquent.created: App\Models\Device',
			'App\Listeners\SynchronizeMeasurementTablesListener@onDeviceCreated'
		);
		$events->listen(
			'eloquent.updated: App\Models\Device',
			'App\Listeners\SynchronizeMeasurementTablesListener@onDeviceUpdated'
		);
		$events->listen(
			'eloquent.deleted: App\Models\Device',
			'App\Listeners\SynchronizeMeasurementTablesListener@onDeviceDeleted'
		);
		$events->listen(
			'eloquent.created: App\Models\Attribute',
			'App\Listeners\SynchronizeMeasurementTablesListener@onAttributeCreated'
		);
		$events->listen(
			'eloquent.updated: App\Models\Attribute',
			'App\Listeners\SynchronizeMeasurementTablesListener@onAttributeUpdated'
		);
	}
}
