<?php

namespace App\Http\Controllers\Api;

use App\Models\Device;
use Illuminate\Database\Eloquent\Model;

class DeviceController extends Controller
{
	protected $class = Device::class;

	function store ()
	{
		$model = $response = parent::store();

		if ( ! ($model instanceof Model))
			return $response;

		$model->users()->attach($model->user_id);
		unset($model->users);

		return $model;
	}
}
