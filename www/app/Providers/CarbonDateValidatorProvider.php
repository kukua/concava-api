<?php

namespace App\Providers;

use Validator;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\ServiceProvider;

class CarbonDateValidatorProvider extends ServiceProvider {
	function boot() {
		Validator::extend('carbon_date', function (
			$attribute, $value, $parameters, $validator
		) {
			if ($value instanceof Carbon) {
				return true;
			}

			try {
				new Carbon($value);
				return true;
			} catch (Exception $e) {
				return false;
			}
		});
	}

	function register() {
	}
}
