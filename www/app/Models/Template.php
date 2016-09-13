<?php

namespace App\Models;

use Model;
use App\Models\Traits\ISO8601Dates;

class Template extends Model {
	use ISO8601Dates;

	static $rules = [
		'user_id' => 'required|integer',
		'name' => 'required|max:255'
	];
	protected $fillable = ['user_id', 'name'];
	public $timestamps = true;
	public $relationships = ['user', 'devices', 'attributes', 'labels'];
	public $setCurrentUserIdOnCreate = true;

	function user () {
		return $this->belongsTo(User::class);
	}

	function devices () {
		return $this->hasMany(Device::class);
	}

	function attributes () {
		return $this->hasMany(Attribute::class)->orderBy('order');
	}

	function labels () {
		return $this->hasMany(TemplateLabel::class);
	}

	function getUserIdsAttribute () {
		return [(int) $this->user_id];
	}

	function duplicate () {
		$template = $this->replicate();
		$template->name = $template->name . ' (copy)';
		$template->push();

		$template->attributes()->saveMany($this->attributes()->get()->map(
			function ($attribute) use ($template) {
				return $attribute->duplicate($template->id);
			}
		));

		$template->labels()->saveMany($this->labels()->get()->map(
			function ($label) use ($template) {
				return $label->duplicate($template->id);
			}
		));

		return $template;
	}

	// Cast attributes to correct types
	function getIdAttribute ($val) { return (int) $val; }
	function getUserIdAttribute ($val) { return (int) $val; }
}
