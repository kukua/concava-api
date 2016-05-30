<?php

namespace App\Models;

use Model;

class Attribute extends Model {
	static $rules = [
		'template_id' => 'required|integer',
		'name' => 'required|max:255',
		'order' => 'required|integer|min:0'
	];
	protected $fillable = ['template_id', 'name', 'order'];
	public $timestamps = true;
	public $relationships = ['template', 'converters', 'calibrators', 'validators'];

	static function scopeByTemplate ($query, $template) {
		if ($template instanceof Model) {
			$id = $template->id;
		} else {
			$id = (int) $template;
		}

		return $query->where('template_id', $id);
	}

	function template () {
		return $this->belongsTo(Template::class);
	}

	function converters () {
		return $this->hasMany(Converter::class);
	}

	function calibrators () {
		return $this->hasMany(Calibrator::class);
	}

	function validators () {
		return $this->hasMany(Validator::class);
	}

	function getUserIdsAttribute () {
		return [(int) $this->template->user_id];
	}

	// Cast attributes to correct types
	function getIdAttribute ($val) { return (int) $val; }
	function getTemplateIdAttribute ($val) { return (int) $val; }
	function getOrderAttribute ($val) { return (int) $val; }
}
