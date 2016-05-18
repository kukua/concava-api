<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
	public static $rules = [
		'template_id' => 'required|integer',
		'name' => 'required|max:255',
		'order' => 'required|integer|min:0'
	];
	protected $fillable = ['template_id', 'name', 'order'];
	public $timestamps = true;

	function template ()
	{
		return $this->belongsTo(Template::class);
	}

	function converters ()
	{
		return $this->hasMany(Converter::class);
	}

	function calibrators ()
	{
		return $this->hasMany(Calibrator::class);
	}

	function validators ()
	{
		return $this->hasMany(Validator::class);
	}
}
