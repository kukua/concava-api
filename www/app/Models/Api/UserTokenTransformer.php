<?php

namespace App\Models\Api;

use App\Models\Database\UserToken;
use NilPortugues\Api\Mappings\JsonApiMapping;

class UserTokenTransformer implements JsonApiMapping
{
	public function getClass ()
	{
		return UserToken::class;
	}

	public function getAlias ()
	{
		return 'userToken';
	}

	public function getAliasedProperties ()
	{
		return [];
	}

	public function getHideProperties ()
	{
		return [];
	}

	public function getIdProperties ()
	{
		return ['id'];
	}

	public function getUrls ()
	{
		return [
			'self' => ['name' => 'userTokens.show', 'as_id' => 'id'],
			'userTokens' => ['name' => 'userTokens.index']
		];
	}

	public function getRelationships ()
	{
		return [];
	}
}
