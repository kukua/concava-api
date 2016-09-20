<?php

namespace App\Models\Relations;

use DB;
use InvalidArgumentException;
use App\Models\Device;
use App\Models\Measurement;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;

class HasManyMeasurements extends Relation {
	function __construct (Device $parent) {
		$related = new Measurement();

		parent::__construct($related->newQuery(), $parent);
	}

	function addConstraints () {
		if ( ! static::$constraints) return;

		$this->addDeviceConstraints($this->parent);
	}

	function addEagerConstraints (array $models) {
		if ( ! $models) return;

		if (count($models) > 1) {
			throw new InvalidArgumentException('Eager loading for multiple devices not supported.');
		}

		$this->addDeviceConstraints($models[0]);
	}

	function addDeviceConstraints (Device $device) {
		$this->query
			->from($device->udid)
			->where('timestamp', '<=', DB::raw('NOW()'));
	}

	function initRelation (array $models, $relation) {
		foreach ($models as $model) {
			$model->setRelation($relation, null);
		}

		return $models;
	}

	function match (array $models, Collection $results, $relation) {
		foreach ($models as $model) {
			$model->setRelation($relation, $results->first());
		}

		return $models;
	}

	function getResults () {
		return $this->query->get();
	}
}
