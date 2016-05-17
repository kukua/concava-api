<?php

namespace App\Models\Api;

use App\Models\Database\Device;
use NilPortugues\Api\Mappings\JsonApiMapping;

class DeviceTransformer implements JsonApiMapping
{
	public function getClass()
	{
		return Device::class;
	}

	public function getAlias()
	{
		return 'device';
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
			'self' => ['name' => 'devices.show', 'as_id' => 'id'],
			'devices' => ['name' => 'devices.index']
		];
	}

	public function getRelationships()
	{
		return [];
	}
}
