<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Attribute;
use DB;
use Schema;
use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Laravel\Lumen\Routing\Controller as BaseController;

class MeasurementTableController extends BaseController {
	public static $connection = 'mysql-measurements';

	function __construct () {
		$this->middleware('auth.token', ['only' => 'newMeasurementTable']);
	}

	function newMeasurementTable ($id) {
		$device = Device::findOrFail($id);

		$this->backupTable($device);
		$this->createTable($device);
	}

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

		switch ($type) {
			case 'float':
				return $table->float($name, 0, 0)->nullable();

			case 'timestamp':
				return $table->timestamp($name)->nullable(false)->default('0000-00-00 00:00:00');

			default:
				return $table->$type($name)->nullable();
		}
	}

	protected function getSchema () {
		return Schema::connection(static::$connection);
	}

	protected function renameTable ($from, $to) {
		try {
			$this->getSchema()->rename($from, $to);
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
		$conn = $this->getSchema();

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
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
		});
	}

	protected function addTableColumn (Device $device, Attribute $attribute) {
		$self = $this;
		$conn = $this->getSchema();
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
		$conn = $this->getSchema();

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
}
