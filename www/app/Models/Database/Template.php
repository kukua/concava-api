<?php

namespace App\Models\Database;

class Template extends Model
{
	public static $rules = [
		'user_id' => 'required|integer',
		'name' => 'required|max:255'
	];
	protected $fillable = ['user_id', 'name'];
	public $timestamps = true;

	public function user ()
	{
		return $this->belongsTo(User::class);
	}

	public function devices ()
	{
		return $this->hasMany(Device::class);
	}
}
