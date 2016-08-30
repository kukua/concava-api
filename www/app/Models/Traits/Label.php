<?php

namespace App\Models\Traits;

// Create key by name and json encode/decode value attribute
trait Label {
	function setNameAttribute ($val) {
		$this->attributes['name'] = $val;
		$this->attributes['key'] = snake_case(strtr(trim($val), ' ', '_'));
	}

	function setValueAttribute ($val) {
		if ($val === '' || is_null($val)) {
			$val = '';
		} else if ( ! is_string($val)) {
			$val = json_encode($val);
		} else if (json_decode($val) === null) {
			$val = "\"$val\"";
		}

		$this->attributes['value'] = $val;
	}

	function getValueAttribute ($val) {
		return json_decode($val, true);
	}
}
