<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
	static $rules = [
		'user_id' => 'required|integer',
		'token' => 'required|regex:^[a-z0-9]{32}$'
	];
	protected $fillable = ['token'];
	public $timestamps = true;
	public $relationships = ['user'];

	static function randomToken ()
	{
		return bin2hex(openssl_random_pseudo_bytes(16));
	}

	function user ()
	{
		return $this->belongsTo(User::class, 'user_devices');
	}

	function getUserIdsAttribute ()
	{
		return [$this->user_id];
	}
}
