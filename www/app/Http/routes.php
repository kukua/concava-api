<?php

Route::group(['namespace' => 'Api'], function() {
	Route::resource('devices', 'DeviceController');
});
