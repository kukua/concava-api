<?php

namespace App\Http\Controllers\Api;

use App\Models\Database\Converter;
use NilPortugues\Laravel5\JsonApi\Controller\JsonApiController;

class ConverterController extends JsonApiController
{
	public function getDataModel ()
	{
		return new Converter();
	}
}
