<?php

use App\Models\Api\AttributeTransformer;
use App\Models\Api\CalibratorTransformer;
use App\Models\Api\ConverterTransformer;
use App\Models\Api\DeviceTransformer;
use App\Models\Api\TemplateTransformer;
use App\Models\Api\UserTokenTransformer;
use App\Models\Api\UserTransformer;
use App\Models\Api\ValidatorTransformer;

return [
	AttributeTransformer::class,
	CalibratorTransformer::class,
	ConverterTransformer::class,
	DeviceTransformer::class,
	TemplateTransformer::class,
	UserTokenTransformer::class,
	UserTransformer::class,
	ValidatorTransformer::class
];
