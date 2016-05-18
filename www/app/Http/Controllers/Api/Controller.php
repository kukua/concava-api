<?php

namespace App\Http\Controllers\Api;

use Request;
use Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

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

		return $query->get(['*']);
	}

	function store ()
	{
		$validator = Validator::make($data = Request::input(), $this->getRules());

		if ($validator->fails())
		{
			return Response::json($validator->messages(), 500);
		}

		$entity = (new $this->class);
		$entity->fill($data);
		$entity->save();

		return $entity;
	}

	function show ($id)
	{
		return $this->findOrFail($id);
	}

	function update ($id)
	{
		$validator = Validator::make($data = Request::input(), $this->getRules(true));

		if ($validator->fails())
		{
			return Response::json($validator->messages(), 500);
		}

		$entity = $this->findOrFail($id);
		$entity->update($data);

		return $entity;
	}

	function destroy ($id)
	{
		$entity = $this->findOrFail($id);
		$entity->delete();

		return $entity;
	}
}
