<?php

namespace App\Listeners;

use App\Http\Controllers\MeasurementTableController;

class SynchronizeMeasurementTableListener {
	protected $controller = MeasurementTableController::class;

	function subscribe ($events) {
		$controller = $this->controller;
		$connection = $controller::$connection;

		if ( ! config("database.connections.$connection.enabled")) {
			return;
		}

		$events->listen(
			'eloquent.created: App\Models\Device',
			"$controller@onDeviceCreated"
		);
		$events->listen(
			'eloquent.updated: App\Models\Device',
			"$controller@onDeviceUpdated"
		);
		$events->listen(
			'eloquent.deleted: App\Models\Device',
			"$controller@onDeviceDeleted"
		);
		$events->listen(
			'eloquent.created: App\Models\Attribute',
			"$controller@onAttributeCreated"
		);
		$events->listen(
			'eloquent.updated: App\Models\Attribute',
			"$controller@onAttributeUpdated"
		);
	}
}
