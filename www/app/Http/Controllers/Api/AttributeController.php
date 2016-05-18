<?php

namespace App\Http\Controllers\Api;

use App\Models\Database\Attribute;
use NilPortugues\Laravel5\JsonApi\Controller\JsonApiController;

class AttributeController extends JsonApiController
{
	public function getDataModel ()
	{
		return new Attribute();
	}
}
