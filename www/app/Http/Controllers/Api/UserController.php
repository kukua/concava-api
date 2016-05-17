<?php

namespace App\Http\Controllers\Api;

use App\Models\Database\User;
use NilPortugues\Laravel5\JsonApi\Controller\JsonApiController;

class UserController extends JsonApiController
{
	public function getDataModel()
	{
		return new User();
	}
}
