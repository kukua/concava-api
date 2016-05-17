<?php

namespace App\Model\Api;

use App\Model\Database\Attribute;
use NilPortugues\Api\Mappings\JsonApiMapping;

class AttributeTransformer implements JsonApiMapping
{
	public function getClass()
	{
		return Attribute::class;
	}

	public function getAlias()
	{
		return 'attribute';
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
			'self' => ['name' => 'attributes.show', 'as_id' => 'id'],
			'attributes' => ['name' => 'attributes.index']
		];
	}

	public function getRelationships()
	{
		return ['template', 'converters', 'calibrators', 'validators'];
	}
}
