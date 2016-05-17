<?php

namespace App\Http\Controllers\Api;

use App\Models\Database\UserToken;
use NilPortugues\Laravel5\JsonApi\Controller\JsonApiController;

class UserTokenController extends JsonApiController
{
	public function getDataModel()
	{
		return new UserToken();
	}
}
