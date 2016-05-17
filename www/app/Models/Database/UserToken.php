<?php

namespace App\Models\Database;

class UserToken extends Model
{
	public static $rules = [
		'user_id' => 'required|integer',
		'token' => 'required|regex:^[a-z0-9]{32}$'
	];
	protected $fillable = ['token'];
	public $timestamps = true;

	public function user ()
	{
		return $this->belongsTo(User::class, 'user_devices');
	}

	public function getRandomTokenAttribute ()
	{
		return bin2hex(openssl_random_pseudo_bytes(16));
	}
}
