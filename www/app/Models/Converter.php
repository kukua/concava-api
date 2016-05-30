<?php

namespace App\Models;

use Model;

class Converter extends Model {
	static $rules = [
		'attribute_id' => 'required|integer',
		'type' => 'required|max:32',
		'value' => 'max:255',
		'order' => 'required|integer|min:0'
	];
	protected $fillable = ['attribute_id', 'type', 'value', 'order'];
	public $timestamps = true;
	public $relationships = ['attribute'];

	function attribute () {
		return $this->belongsTo(Attribute::class);
	}

	function getUserIdsAttribute () {
		return $this->attribute->user_ids;
	}

	// Cast attributes to correct types
	function getIdAttribute ($val) { return (int) $val; }
	function getAttributeIdAttribute ($val) { return (int) $val; }
	function getOrderAttribute ($val) { return (int) $val; }
}
