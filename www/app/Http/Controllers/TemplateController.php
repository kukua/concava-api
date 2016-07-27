<?php

namespace App\Http\Controllers;

use App\Models\Template;

class TemplateController extends Controller {
	protected $class = Template::class;

	function duplicate ($id) {
		return $this->findOrFail($id)->duplicate();
	}
}
