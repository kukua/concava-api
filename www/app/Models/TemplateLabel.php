<?php

namespace App\Models;

use Model;
use App\Models\Traits\Label;

class TemplateLabel extends Model {
	use Label;

	static $rules = [
		'template_id' => 'required|integer',
		'name' => 'required|string',
	];
	protected $fillable = ['template_id', 'name', 'value'];
	protected $hidden = ['id', 'template_id', 'created_at'];
	public $timestamps = true;
	public $relationships = ['template'];

	function template () {
		return $this->belongsTo(Template::class);
	}

	function getUserIdsAttribute () {
		return $this->template->user_ids;
	}

	function duplicate ($templateId) {
		$label = $this->replicate();
		$label->template_id = $templateId;
		$label->push();

		return $label;
	}

	// Cast attributes to correct types
	function getIdAttribute ($val) { return (int) $val; }
	function getTemplateIdAttribute ($val) { return (int) $val; }
}
