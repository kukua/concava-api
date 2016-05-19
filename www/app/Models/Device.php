<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
	static $rules = [
		'template_id' => 'required|integer',
		'udid' => 'required|regex:/^[a-z0-9]{16}$/',
		'name' => 'required|max:255'
	];
	protected $fillable = ['user_id', 'template_id', 'udid', 'name'];
	public $timestamps = true;
	public $relationships = ['users', 'template'];

	public $setCurrentUserIdOnCreate = true;
	// Used by App\Http\Controllers\Api\DeviceController
	protected $userId = 0;

	function users ()
	{
		return $this->belongsToMany(User::class, 'user_devices')->withTimestamps();
	}

	function template ()
	{
		return $this->belongsTo(Template::class);
	}

	function setUserIdAttribute ($val)
	{
		$this->userId = $val;
	}

	function getUserIdsAttribute ()
	{
		return $this->users->pluck('id')->toArray() + [(int) $this->userId];
	}
}
