<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
	static $rules = [
		'template_id' => 'required|integer',
		'udid' => 'required|unique|regex:^[a-z0-9]{16}$',
		'name' => 'required|max:255'
	];
	protected $fillable = ['template_id', 'udid', 'name'];
	public $timestamps = true;
	public $relationships = ['users', 'template'];

	function users ()
	{
		return $this->belongsToMany(User::class, 'user_devices');
	}

	function template ()
	{
		return $this->belongsTo(Template::class);
	}

	function getUserIdsAttribute ()
	{
		return $this->users->pluck('id')->toArray();
	}
}
