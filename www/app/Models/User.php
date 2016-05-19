<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Hash;

class User extends Authenticatable
{
	static $rules = [
		'name' => 'required|max:255',
		'email' => 'required|email',
		'password' => 'required|confirmed|min:8|max:255'
	];
	protected $fillable = ['name', 'email', 'password'];
	protected $hidden = ['password', 'remember_token'];
	public $timestamps = true;
	public $relationships = ['devices', 'templates', 'tokens'];
	public $guardCreate = false;

	static function findByToken ($token)
	{
		$userToken = UserToken::where('token', $token)->first();

		if ( ! $userToken) return;

		return static::findOrFail($userToken->user_id);
	}

	function getDates ()
	{
		return ['last_login', 'created_at', 'updated_at'];
	}

	function devices ()
	{
		return $this->belongsToMany(Device::class, 'user_devices');
	}

	function templates ()
	{
		return $this->hasMany(Template::class);
	}

	function tokens ()
	{
		return $this->hasMany(UserToken::class);
	}

	function setPasswordAttribute ($val)
	{
		$this->attributes['password'] = Hash::make($val);
	}

	function getUserIdsAttribute ()
	{
		return [(int) $this->id];
	}
}
