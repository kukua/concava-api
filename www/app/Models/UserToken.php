<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
	public static $rules = [
		'user_id' => 'required|integer',
		'token' => 'required|regex:^[a-z0-9]{32}$'
	];
	protected $fillable = ['token'];
	public $timestamps = true;

	public static function randomToken ()
	{
		return bin2hex(openssl_random_pseudo_bytes(16));
	}

	function user ()
	{
		return $this->belongsTo(User::class, 'user_devices');
	}
}
