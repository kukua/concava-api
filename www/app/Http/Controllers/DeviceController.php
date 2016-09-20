<?php

namespace App\Http\Controllers;

use Request;
use Auth;
use Model;
use App\Models\Device;
use App\Models\DeviceLabel;

class DeviceController extends Controller {
	protected $class = Device::class;

	function store () {
		$model = $response = parent::store();

		if ( ! ($model instanceof Model))
			return $response;

		$model->users()->attach($model->user_id);
		unset($model->users);

		$this->updateLabels($model);
		$this->addIncludes($model);

		return $model;
	}

	function show ($id) {
		$model = $this->findOrFail($id);

		if ( ! in_array(Auth::id(), $model->user_ids, true)) {
			throw new HttpException(401, 'Not allowed to read entity.');
		}

		$this->addIncludes($model);

		return $model;
	}

	function update ($id) {
		$model = $response = parent::update($id);

		if ( ! ($model instanceof Model))
			return $response;

		$this->updateLabels($model);
		$this->addIncludes($model);

		return $model;
	}

	protected function updateLabels (Model $model) {
		if ( ! Request::has('labels')) return;

		$labels = (array) Request::input('labels');

		$model->labels()->delete();

		foreach ($labels as $name => & $value) {
			DeviceLabel::create([
				'device_id' => $model->id,
				'name' => $name,
				'value' => $value,
			]);
		}

		unset($model->labels);
	}
}
