<?php

if ( ! function_exists('resource')) {
	function resource ($uri, $controller) {
		global $app;

		$app->get($uri,             "$controller@index");
		$app->get("$uri/create",    "$controller@create");
		$app->post($uri,            "$controller@store");
		$app->get("$uri/{id}",      "$controller@show");
		$app->get("$uri/{id}/edit", "$controller@edit");
		$app->put("$uri/{id}",      "$controller@update");
		$app->patch("$uri/{id}",    "$controller@update");
		$app->delete("$uri/{id}",   "$controller@destroy");
	}
}

if ( ! function_exists('uses_trait')) {
	function uses_trait ($class, $trait, $includeParents = true) {
		if (in_array(trim($trait, ' \\'), class_uses($class), true)) {
			return true;
		}

		if ($includeParents) {
			foreach (class_parents($class) as $parent) {
				if (uses_trait($parent, $trait, false)) {
					return true;
				}
			}
		}

		return false;
	}
}
