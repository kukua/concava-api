<?php

namespace App\Models;

use Model;

class Device extends Model {
	static $rules = [
		'template_id' => 'required|integer',
		'udid' => 'required|regex:/^[a-z0-9]{16}$/',
		'name' => 'required|max:255'
	];
	protected $fillable = ['user_id', 'template_id', 'udid', 'name'];
	public $timestamps = true;
	public $relationships = ['users', 'template'];

	public $setCurrentUserIdOnCreate = true;
	public $user_id = 0; // Used by App\Http\Controllers\DeviceController

	function users () {
		return $this->belongsToMany(User::class, 'user_devices')->withTimestamps();
	}

	function template () {
		return $this->belongsTo(Template::class);
	}

	function setUserIdAttribute ($val) {
		$this->user_id = $val;
	}

	function getUserIdsAttribute () {
		return $this->users->pluck('id')->toArray() + [(int) $this->user_id];
	}

	// Cast attributes to correct types
	function getIdAttribute ($val) { return (int) $val; }
	function getTemplateIdAttribute ($val) { return (int) $val; }
}
