<?php

namespace App\Http\Controllers\Api;

use Auth;
use Request;
use Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Controller extends \App\Http\Controllers\Controller
{
	public $restful = true;

	function __construct ($registerMiddleware = true)
	{
		$this->registerMiddleware();
	}

	protected function registerMiddleware ()
	{
		$this->middleware('auth.token');
	}

	function index ()
	{
		$query = $this->query();

		// Filtering
		if ($filters = Request::input('filter'))
		{
			foreach (explode(',', $filters) as $filter)
			{
				list($column, $value) = explode(':', $filter);
				$query->where($column, '=', $value);
			}
		}

		// Sorting
		if ($sort = Request::input('sort'))
		{
			foreach (explode(',', $sort) as $column)
			{
				$order = 'asc';
				if (starts_with($column, '-'))
				{
					$order = 'desc';
					$column = substr($column, 1);
				}

				$query->orderBy($column, $order);
			}
		}

		// Filter for user
		$models = $query->get(['*']);
		$userId = Auth::id();

		$models = $models->filter(function ($model) use ($userId) {
			return in_array($userId, $model->user_ids, true);
		});

		// Add includes
		$self = $this;
		$models->each(function ($model) use ($self) {
			$self->addIncludes($model);
		});

		// Return models
		// NOTE(mauvm): Use array_values to reset array indices, or else
		// json_encode could create an object instead of an array.
		return array_values($models->all());
	}

	function store ()
	{
		$data = Request::input();
		$model = $this->instance();

		if (empty($data['user_id']) && $model->setCurrentUserIdOnCreate === true)
		{
			$data['user_id'] = Auth::id();
		}

		$validator = Validator::make($data, $this->getRules());

		if ($validator->fails())
		{
			return response()->json([
				'messages' => $validator->messages()
			], 400);
		}

		$model->fill($data);
		$model->save();

		return $model;
	}

	function show ($id)
	{
		$model = $this->findOrFail($id);

		if ( ! in_array(Auth::id(), $model->user_ids, true))
			throw new HttpException(401, 'Not allowed to read entity.');

		$this->addIncludes($model);

		return $model;
	}

	function update ($id)
	{
		$validator = Validator::make($data = Request::input(), $this->getRules(true));

		if ($validator->fails())
		{
			return response()->json([
				'messages' => $validator->messages()
			], 400);
		}

		$model = $this->findOrFail($id);
		$model->update($data);

		return $model;
	}

	function destroy ($id)
	{
		$model = $this->findOrFail($id);
		$model->delete();

		return $model;
	}

	protected function instance ()
	{
		return (new $this->class);
	}

	protected function query ()
	{
		return $this->instance()->newQuery();
	}

	protected function getRules ($forInput = false)
	{
		$rules = get_class_vars($this->class)['rules'];

		if ($forInput)
		{
			return array_only($rules, array_keys(Request::input()));
		}

		return $rules;
	}

	protected function findOrFail ($id)
	{
		try
		{
			return $this->instance()->findOrFail($id);
		}
		catch (ModelNotFoundException $e)
		{
			throw new HttpException(404, 'Entity not found.', $e);
		}
	}

	// Recursively eager load includes
	protected function loadIncludes (Model $model, array & $includes)
	{
		foreach ($model->relationships as $relation)
		{
			$include = array_get($includes, $relation);

			if ( ! $include)
			{
				unset($model->$relation); // Unload
				continue;
			}

			$model->load($relation);

			if (is_array($include))
			{
				$models = $model->$relation;

				if ($models instanceof Collection)
					$models = $models->all();
				else
					$models = [$models];

				foreach ($models as $otherModel)
				{
					$this->loadIncludes($otherModel, $include);
				}
			}
		}
	}

	protected function addIncludes (Model $model)
	{
		$keys = explode(',', (string) Request::input('include'));
		$includes = [];

		foreach ($keys as & $key)
		{
			if (array_get($includes, $key))
				continue;

			array_set($includes, $key, true);
		}

		$this->loadIncludes($model, $includes);
	}
}
