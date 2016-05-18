<?php

Route::get('users/login', ['as' => 'users.login', 'uses' => 'UserController@login']);
Route::resource('users', 'UserController');
Route::resource('devices', 'DeviceController');
Route::resource('templates', 'TemplateController');
Route::resource('attributes', 'AttributeController');
