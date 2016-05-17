<?php

Route::group(['namespace' => 'Api'], function() {
	Route::resource('users', 'UserController');
	Route::resource('userTokens', 'UserTokenController');
	Route::resource('devices', 'DeviceController');
	Route::resource('templates', 'TemplateController');
	Route::resource('attributes', 'AttributeController');
	Route::resource('converters', 'ConverterController');
	Route::resource('calibrators', 'CalibratorController');
	Route::resource('validators', 'ValidatorController');
});
