<?php

namespace App\Models\Database;

class Converter extends Model
{
	public static $rules = [
		'attribute_id' => 'required|integer',
		'type' => 'required|max:32',
		'value' => 'required|max:255',
		'order' => 'required|integer|min:0'
	];
	protected $fillable = ['attribute_id', 'type', 'value', 'order'];
	public $timestamps = true;

	public function attribute ()
	{
		return $this->belongsTo(Attribute::class);
	}
}
