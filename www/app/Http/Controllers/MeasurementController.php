<?php

namespace App\Http\Controllers;

use Request;
use Model;
use App\Models\Measurement;
use App\Models\Device;

class MeasurementController {
	protected $class = Measurement::class;

	function storeByDeviceId ($id) {
		$device = Device::findOrFail($id);

		$measurement = $this->instance();
		$measurement->udid = $device->udid;
		$measurement->fill(Request::input());
		$measurement->save();

		return response(null, 200);
	}

	protected function instance () {
		return (new $this->class);
	}
}
