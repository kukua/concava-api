<?php

namespace App\Models\Api;

use App\Models\Database\Calibrator;
use NilPortugues\Api\Mappings\JsonApiMapping;

class CalibratorTransformer implements JsonApiMapping
{
	public function getClass()
	{
		return Calibrator::class;
	}

	public function getAlias()
	{
		return 'calibrator';
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
			'self' => ['name' => 'calibrators.show', 'as_id' => 'id'],
			'calibrators' => ['name' => 'calibrators.index']
		];
	}

	public function getRelationships()
	{
		return [];
	}
}
