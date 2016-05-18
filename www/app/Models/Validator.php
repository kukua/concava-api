<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Validator extends Model
{
	public static $rules = [
		'attribute_id' => 'required|integer',
		'type' => 'required|max:32',
		'value' => 'required|max:255',
		'order' => 'required|integer|min:0'
	];
	protected $fillable = ['attribute_id', 'type', 'value', 'order'];
	public $timestamps = true;

	function attribute ()
	{
		return $this->belongsTo(Attribute::class);
	}

	function getUserIdsAttribute ()
	{
		return $this->attribute->user_ids;
	}
}
