<?php

namespace App\Http\Controllers\Api;

use App\Models\Database\Device;
use NilPortugues\Laravel5\JsonApi\Controller\JsonApiController;

class DeviceController extends JsonApiController
{
	public function getDataModel()
	{
		return new Device();
	}
}
