<?php

namespace App\Model\Api;

use App\Model\Database\User;
use NilPortugues\Api\Mappings\JsonApiMapping;

class UserTransformer implements JsonApiMapping
{
	public function getClass()
	{
		return User::class;
	}

	public function getAlias()
	{
		return 'user';
	}

	public function getAliasedProperties()
	{
		return [];
	}

	public function getHideProperties()
	{
		return [];
	}

	public function getIdProperties()
	{
		return ['id'];
	}

	public function getUrls()
	{
		return [
			'self' => ['name' => 'users.show', 'as_id' => 'id'],
			'users' => ['name' => 'users.index']
		];
	}

	public function getRelationships()
	{
		return ['devices', 'templates'];
	}
}
