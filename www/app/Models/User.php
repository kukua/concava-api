<?php

namespace App\Models;

// Copied and modified from Laravel 5.2 Illuminate\Foundation\Auth\User

use Model;
use Hash;
use DateTime;
use App\Models\Traits\ISO8601Dates;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {
    use Authenticatable, CanResetPassword;
	use ISO8601Dates;

	static $rules = [
		'name' => 'required|max:255',
		'email' => 'required|email',
		'password' => 'required|min:8|max:255'
	];
	protected $fillable = ['name', 'email', 'password'];
	protected $hidden = ['password', 'remember_token'];
	protected $appends = ['token'];
	public $timestamps = true;
	public $relationships = ['devices', 'templates', 'tokens'];
	public $guardCreate = false;

	static function findByToken ($token) {
		$userToken = UserToken::where('token', strtolower($token))->first();

		if ( ! $userToken) return;

		return static::findOrFail($userToken->user_id);
	}

	function getDates () {
		return ['last_login', 'created_at', 'updated_at'];
	}

	function devices () {
		return $this->belongsToMany(Device::class, 'user_devices')->withTimestamps();
	}

	function templates () {
		return $this->hasMany(Template::class);
	}

	function tokens () {
		return $this->hasMany(UserToken::class);
	}

	function touchLastLogin ($save = true) {
		$this->last_login = new DateTime;
		if ($save) $this->save();
	}

	function setPasswordAttribute ($val) {
		$this->attributes['password'] = Hash::make($val);
	}

	function getUserIdsAttribute () {
		return [(int) $this->id];
	}

	function getTokenAttribute () {
		return $this->tokens()->first()->token;
	}

	// Cast attributes to correct types
	function getIdAttribute ($val) { return (int) $val; }
	function getIsActiveAttribute ($val) { return (bool) $val; }
	function getIsAdminAttribute ($val) { return (bool) $val; }
}
