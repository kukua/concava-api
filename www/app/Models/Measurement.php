<?php

namespace App\Models;

use Model;
use DB;
use App\Models\Traits\ISO8601Dates;

class Measurement extends Model {
	use ISO8601Dates;

	protected $connection = 'mysql-measurements';
	protected $table = '';

	static $rules = [
		'udid' => 'required|regex:/^[a-z0-9]{16}$/',
	];
	protected $guarded = ['udid', 'updated_at'];
	protected $dates = ['timestamp', 'updated_at'];
	public $timestamps = false;

	public $guardCreate = false;
	public $guardUpdate = false;
	public $guardDelete = false;

	static function scopeByUDID ($query, $udid) {
		return $query->from($udid);
	}

	static function scopeInterval ($query, $interval = 300) {
		$interval = (int) $interval;

		return $query
			->addSelect(DB::raw('UNIX_TIMESTAMP(timestamp) - mod(UNIX_TIMESTAMP(timestamp), ' .
				$interval . ') as timestamp'))
			->groupBy(DB::raw('UNIX_TIMESTAMP(timestamp) - UNIX_TIMESTAMP(timestamp) % ' . $interval));
	}

	function setUdidAttribute ($val) {
		$this->setTable(strtolower($val));
	}

	function getUdidAttribute () {
		return $this->getTable();
	}

	function isFillable ($key) {
		return parent::isFillable($key) || $key === '_raw';
	}
}
