<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	public static $rules = [
		'name' => 'required|max:255',
		'email' => 'required|email|unique',
		'password' => 'required|min:8|max:255'
	];
	protected $fillable = ['name', 'email', 'password'];
	protected $hidden = ['password'];
	public $timestamps = true;

	public function getDates ()
	{
		return ['last_login', 'created_at', 'updated_at'];
	}

	public function devices ()
	{
		return $this->belongsToMany(Device::class, 'user_devices');
	}

	public function templates ()
	{
		return $this->hasMany(Template::class);
	}
}
