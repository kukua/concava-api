<?php

namespace App\Models;

use Model;
use App\Models\Traits\ISO8601Dates;
use App\Models\Relations\HasOneMeasurement;

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
