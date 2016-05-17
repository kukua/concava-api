<?php

namespace App\Model\Api;

use App\Model\Database\Template;
use NilPortugues\Api\Mappings\JsonApiMapping;

class TemplateTransformer implements JsonApiMapping
{
	public function getClass()
	{
		return Template::class;
	}

	public function getAlias()
	{
		return 'template';
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
			'self' => ['name' => 'templates.show', 'as_id' => 'id'],
			'templates' => ['name' => 'templates.index']
		];
	}

	public function getRelationships()
	{
		return ['user', 'devices'];
	}
}
