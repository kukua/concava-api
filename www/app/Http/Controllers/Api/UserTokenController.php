<?php

namespace App\Http\Controllers\Api;

use App\Model\Database\UserToken;
use NilPortugues\Laravel5\JsonApi\Controller\JsonApiController;

class UserTokenController extends JsonApiController
{
	public function getDataModel()
	{
		return new UserToken();
	}
}
