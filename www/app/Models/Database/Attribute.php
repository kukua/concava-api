<?php

namespace App\Models\Database;

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

	public function template ()
	{
		return $this->belongsTo(Template::class);
	}

	public function converters ()
	{
		return $this->hasMany(Converter::class);
	}

	public function calibrators ()
	{
		return $this->hasMany(Calibrator::class);
	}

	public function validators ()
	{
		return $this->hasMany(Validator::class);
	}
}
