<?php

$app->get('users/login', ['as' => 'users.login', 'uses' => 'UserController@login']);
resource('users', 'UserController');
resource('devices', 'DeviceController');
resource('templates', 'TemplateController');
$app->put('attributes/reorder', ['as' => 'attributes.reorder', 'uses' => 'AttributeController@reorder']);
resource('attributes', 'AttributeController');

$app->get('blaat', function () {
	return response()->json([
		'read-device' => \App\Models\User::find(11)->can('read-device'),
	]);
});
