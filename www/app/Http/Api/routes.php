<?php

// Applies to all requests
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
header('Access-Control-Allow-Credentials: true');

Route::get('users/login', ['as' => 'users.login', 'uses' => 'UserController@login']);
Route::resource('users', 'UserController');
Route::resource('devices', 'DeviceController');
Route::resource('templates', 'TemplateController');
Route::put('attributes/reorder', ['as' => 'attributes.reorder', 'uses' => 'AttributeController@reorder']);
Route::resource('attributes', 'AttributeController');
