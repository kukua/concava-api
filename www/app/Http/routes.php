<?php

$app->options('{route:.*}', function () {
	return response()->json();
});

$app->get('users/login', ['uses' => 'UserController@login']);
resource('users', 'UserController');

$app->post('devices/{id}/newMeasurementTable', ['uses' => 'MeasurementTableController@newMeasurementTable']);
$app->post('devices/{id}/measurements', ['uses' => 'MeasurementController@storeByDeviceId']);
$app->get('devices/{id}/measurements', ['uses' => 'MeasurementController@showByDeviceId']);
resource('devices', 'DeviceController');

$app->post('templates/{id}/duplicate', ['uses' => 'TemplateController@duplicate']);
resource('templates', 'TemplateController');

$app->put('attributes/reorder', ['uses' => 'AttributeController@reorder']);
resource('attributes', 'AttributeController');

$app->get('measurements', ['uses' => 'MeasurementController@index']);
