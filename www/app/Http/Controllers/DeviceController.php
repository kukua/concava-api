<?php

namespace App\Http\Controllers;

use Model;
use App\Models\Device;

class DeviceController extends Controller {
	protected $class = Device::class;

	function store () {
		$model = $response = parent::store();

		if ( ! ($model instanceof Model))
			return $response;

		$model->users()->attach($model->user_id);
		unset($model->users);

		return $model;
	}
}
