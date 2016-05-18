<?php

namespace App\Models\Api;

use App\Models\Database\Validator;
use NilPortugues\Api\Mappings\JsonApiMapping;

class ValidatorTransformer implements JsonApiMapping
{
	public function getClass ()
	{
		return Validator::class;
	}

	public function getAlias ()
	{
		return 'validator';
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
			'self' => ['name' => 'validators.show', 'as_id' => 'id'],
			'validators' => ['name' => 'validators.index']
		];
	}

	public function getRelationships ()
	{
		return [];
	}
}
