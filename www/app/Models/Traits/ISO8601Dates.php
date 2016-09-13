<?php

namespace App\Models\Traits;

use DateTime;

trait ISO8601Dates {
	protected function serializeDate (DateTime $date) {
		return $date->toIso8601String();
	}
}
