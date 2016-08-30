<?php

namespace App\Models;

use Model;
use App\Models\Traits\Label;

class DeviceLabel extends Model {
	use Label;

	static $rules = [
		'device_id' => 'required|integer',
		'name' => 'required|string',
	];
	protected $fillable = ['device_id', 'name', 'value'];
	protected $hidden = ['id', 'device_id', 'updated_at'];
	public $timestamps = true;
	public $relationships = ['device'];

	function device () {
		return $this->belongsTo(Device::class);
	}

	function getUserIdsAttribute () {
		return $this->device->user_ids;
	}

	// Cast attributes to correct types
	function getIdAttribute ($val) { return (int) $val; }
	function getDeviceIdAttribute ($val) { return (int) $val; }
}
