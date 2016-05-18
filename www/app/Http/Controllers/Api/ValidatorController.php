<?php

namespace App\Http\Controllers\Api;

use App\Models\Database\Validator;
use NilPortugues\Laravel5\JsonApi\Controller\JsonApiController;

class ValidatorController extends JsonApiController
{
	public function getDataModel ()
	{
		return new Validator();
	}
}
