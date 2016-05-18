<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
	public static $rules = [
		'user_id' => 'required|integer',
		'name' => 'required|max:255'
	];
	protected $fillable = ['user_id', 'name'];
	public $timestamps = true;

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
}
