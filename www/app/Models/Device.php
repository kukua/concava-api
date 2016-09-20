<?php

namespace App\Models;

use Model;
use App\Models\Traits\ISO8601Dates;
use App\Models\Relations\HasOneMeasurement;
use App\Models\Relations\HasManyMeasurements;

class Device extends Model {
	use ISO8601Dates;

	static $rules = [
		'template_id' => 'required|integer',
		'udid' => 'required|regex:/^[a-z0-9]{16}$/',
		'name' => 'required|max:255'
	];
	protected $fillable = ['user_id', 'template_id', 'udid', 'name'];
	public $timestamps = true;
	public $relationships = ['users', 'template', 'labels', 'measurement'];

	public $setCurrentUserIdOnCreate = true;
	public $user_id = 0; // Used by App\Http\Controllers\DeviceController

	static function isUDID ($udid) {
		return (bool) preg_match('/^[a-zA-Z0-9]{16}$/', $udid);
	}

	static function scopeByUDID ($query, $udid) {
		return $query->where('udid', strtolower($udid));
	}

	static function find ($id) {
		if (static::isUDID($id)) {
			return static::byUDID($id)->first();
		}

		return call_user_func_array([static::query(), 'find'], [$id, $columns]);
	}

	static function findOrFail ($id, $columns = ['*']) {
		if (static::isUDID($id)) {
			return static::byUDID($id)->firstOrFail();
		}

		return call_user_func_array([static::query(), 'findOrFail'], [$id, $columns]);
	}

	function users () {
		return $this->belongsToMany(User::class, 'user_devices')->withTimestamps();
	}

	function template () {
		return $this->belongsTo(Template::class);
	}

	function labels () {
		return $this->hasMany(DeviceLabel::class);
	}

	function measurement () {
		return new HasOneMeasurement($this);
	}

	function measurements () {
		return new HasManyMeasurements($this);
	}

	function setUserIdAttribute ($val) {
		$this->user_id = $val;
	}

	function getUserIdsAttribute () {
		return $this->users()->pluck('id')->toArray() + [(int) $this->user_id];
	}

	// Cast attributes to correct types
	function getIdAttribute ($val) { return (int) $val; }
	function getTemplateIdAttribute ($val) { return (int) $val; }
}
