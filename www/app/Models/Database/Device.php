<?php

namespace App\Models\Database;

class Device extends Model
{
	public static $rules = [
		'template_id' => 'required|integer',
		'udid' => 'required|unique|regex:^[a-z0-9]{16}$',
		'name' => 'required|max:255'
	];
	protected $fillable = ['template_id', 'udid', 'name'];
	public $timestamps = true;

	public function users ()
	{
		return $this->belongsToMany(User::class, 'user_devices');
	}

	public function template ()
	{
		return $this->hasOne(Template::class);
	}

	public function tokens ()
	{
		return $this->hasMany(UserToken::class);
	}
}
