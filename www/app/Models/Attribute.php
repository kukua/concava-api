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
		return $this->hasMany(Converter::class)->orderBy('order');
	}

	function calibrators () {
		return $this->hasMany(Calibrator::class)->orderBy('order');
	}

	function validators () {
		return $this->hasMany(Validator::class)->orderBy('order');
	}

	function getUserIdsAttribute () {
		return $this->template->user_ids;
	}

	function duplicate ($templateId) {
		$attribute = $this->replicate();
		$attribute->template_id = $templateId;
		$attribute->push();

		$attribute->converters()->saveMany($this->converters()->get()->map(
			function ($converter) use ($attribute) {
				return $converter->duplicate($attribute->id);
			}
		));

		$attribute->calibrators()->saveMany($this->calibrators()->get()->map(
			function ($calibrator) use ($attribute) {
				return $calibrator->duplicate($attribute->id);
			}
		));

		$attribute->validators()->saveMany($this->validators()->get()->map(
			function ($validator) use ($attribute) {
				return $validator->duplicate($attribute->id);
			}
		));

		return $attribute;
	}

	// Cast attributes to correct types
	function getIdAttribute ($val) { return (int) $val; }
	function getTemplateIdAttribute ($val) { return (int) $val; }
	function getOrderAttribute ($val) { return (int) $val; }
}
