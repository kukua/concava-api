<?php

namespace App\Http\Controllers\Api;

use App\Models\Attribute;
use App\Models\Converter;
use App\Models\Calibrator;
use App\Models\Validator as ValidatorModel;
use Request;
use Validator;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AttributeController extends Controller
{
	protected $class = Attribute::class;

	function store ()
	{
		return $this->handleConCaVa(parent::store());
	}

	function update ($id)
	{
		return $this->handleConCaVa(parent::update($id));
	}

	protected function handleConCaVa ($response)
	{
		$model = $response;

		if ( ! ($model instanceof Model))
			return $response;

		$this->setConverter($model, Request::input('converter'));
		$this->setCalibrator($model, Request::input('calibrator'));
		$this->setValidators($model, Request::input('validators'));
	}

	protected function setConverter (Model $model, $type)
	{
		if (empty($type))
			return;

		$data = [
			'attribute_id' => $model->id,
			'type' => $type,
			'value' => '',
			'order' => 0
		];
		$validator = Validator::make($data, Converter::$rules);

		if ($validator->fails())
		{
			throw new HttpException(400, 'Invalid converter given.');
		}

		$model->converters()->delete();
		Converter::create($data);
	}

	protected function setCalibrator (Model $model, $fn)
	{
		if (is_null($fn))
			return;

		$model->calibrators()->delete();

		if (empty($fn))
			return;

		$data = [
			'attribute_id' => $model->id,
			'fn' => $fn,
			'order' => 0
		];
		$validator = Validator::make($data, Calibrator::$rules);

		if ($validator->fails())
		{
			throw new HttpException(400, 'Invalid calibrator given.');
		}

		Calibrator::create($data);
	}

	protected function setValidators (Model $model, $types)
	{
		if (is_null($types))
			return;

		$model->validators()->delete();

		if (empty($types))
			return;

		$types = explode(' ', $types);

		foreach ($types as $order => $type)
		{
			list($type, $value) = explode('=', $type);

			$data = [
				'attribute_id' => $model->id,
				'type' => $type,
				'value' => $value,
				'order' => $order
			];
			$validator = Validator::make($data, ValidatorModel::$rules);

			if ($validator->fails())
			{
				throw new HttpException(400, 'Invalid validator given.');
			}

			ValidatorModel::create($data);
		}
	}
}
