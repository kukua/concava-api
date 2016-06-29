<?php

$app->options('{route:.*}', function () {
	return response()->json();
});
$app->get('users/login', ['uses' => 'UserController@login']);
resource('users', 'UserController');
resource('devices', 'DeviceController');
resource('templates', 'TemplateController');
$app->put('attributes/reorder', ['uses' => 'AttributeController@reorder']);
resource('attributes', 'AttributeController');
$app->post('devices/{id}/newMeasurementTable', ['uses' => 'MeasurementTableController@newMeasurementTable']);
