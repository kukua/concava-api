<?php

namespace App\Http\Controllers\Api;

use App\Models\Database\Calibrator;
use NilPortugues\Laravel5\JsonApi\Controller\JsonApiController;

class CalibratorController extends JsonApiController
{
	public function getDataModel()
	{
		return new Calibrator();
	}
}
