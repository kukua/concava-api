<?php

namespace App\Http\Controllers\Api;

use App\Model\Database\Attribute;
use NilPortugues\Laravel5\JsonApi\Controller\JsonApiController;

class AttributeController extends JsonApiController
{
	public function getDataModel()
	{
		return new Attribute();
	}
}
