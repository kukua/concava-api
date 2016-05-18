<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calibrator extends Model
{
	static $rules = [
		'attribute_id' => 'required|integer',
		'fn' => 'required',
		'order' => 'required|integer|min:0'
	];
	protected $fillable = ['attribute_id', 'fn', 'order'];
	public $timestamps = true;
	public $relationships = ['attribute'];

	function attribute ()
	{
		return $this->belongsTo(Attribute::class);
	}

	function getUserIdsAttribute ()
	{
		return $this->attribute->user_ids;
	}
}
