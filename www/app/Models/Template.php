<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
	static $rules = [
		'user_id' => 'required|integer',
		'name' => 'required|max:255'
	];
	protected $fillable = ['user_id', 'name'];
	public $timestamps = true;
	public $relationships = ['user', 'devices', 'attributes'];

	function user ()
	{
		return $this->belongsTo(User::class);
	}

	function devices ()
	{
		return $this->hasMany(Device::class);
	}

	function attributes ()
	{
		return $this->hasMany(Attribute::class);
	}

	function getUserIdsAttribute ()
	{
		return [$this->user_id];
	}
}
