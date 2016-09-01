<?php

namespace App\Models;

use Model;

class Measurement extends Model {
	protected $connection = 'mysql-measurements';
	protected $table = '';

	static $rules = [
		'udid' => 'required|regex:/^[a-z0-9]{16}$/',
	];
	protected $guarded = ['udid', 'modified'];
	public $timestamps = false;
	public $guardCreate = false;
	public $guardUpdate = false;
	public $guardDelete = false;

	static function scopeByUDID ($query, $udid) {
		return $query->from($udid);
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
