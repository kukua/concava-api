<?php

namespace App\Http\Controllers;

use Request;
use Model;
use App\Models\Template;
use App\Models\TemplateLabel;

class TemplateController extends Controller {
	protected $class = Template::class;

	function store () {
		$model = $response = parent::store();

		if ( ! ($model instanceof Model))
			return $response;

		$this->updateLabels($model);
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
			TemplateLabel::create([
				'template_id' => $model->id,
				'name' => $name,
				'value' => $value,
			]);
		}

		unset($model->labels);
	}

	function duplicate ($id) {
		return $this->findOrFail($id)->duplicate();
	}
}
