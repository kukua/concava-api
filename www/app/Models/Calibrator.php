<?php

namespace App\Models;

use Model;

class Calibrator extends Model {
	static $rules = [
		'attribute_id' => 'required|integer',
		'fn' => 'required',
		'order' => 'required|integer|min:0'
	];
	protected $fillable = ['attribute_id', 'fn', 'order'];
	public $timestamps = true;
	public $relationships = ['attribute'];
	protected $touches = ['attribute'];

	function attribute () {
		return $this->belongsTo(Attribute::class);
	}

	function getUserIdsAttribute () {
		return $this->attribute->user_ids;
	}

	function duplicate ($attributeId) {
		$calibrator = $this->replicate();
		$calibrator->attribute_id = $attributeId;
		$calibrator->push();

		return $calibrator;
	}

	// Cast attributes to correct types
	function getIdAttribute ($val) { return (int) $val; }
	function getAttributeIdAttribute ($val) { return (int) $val; }
	function getOrderAttribute ($val) { return (int) $val; }
}
