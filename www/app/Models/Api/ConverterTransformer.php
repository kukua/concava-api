<?php

namespace App\Models\Api;

use App\Models\Database\Converter;
use NilPortugues\Api\Mappings\JsonApiMapping;

class ConverterTransformer implements JsonApiMapping
{
	public function getClass ()
	{
		return Converter::class;
	}

	public function getAlias ()
	{
		return 'converter';
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
			'self' => ['name' => 'converters.show', 'as_id' => 'id'],
			'converters' => ['name' => 'converters.index']
		];
	}

	public function getRelationships ()
	{
		return [];
	}
}
