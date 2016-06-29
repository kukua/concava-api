<?php

namespace App\Listeners;

class ModelEventListener {

	function subscribe ($events) {
		$events->listen(
			'eloquent.creating: *',
			'App\Http\Controllers\ACLController@onCreating'
		);
		$events->listen(
			'eloquent.updating: *',
			'App\Http\Controllers\ACLController@onUpdating'
		);
		$events->listen(
			'eloquent.deleting: *',
			'App\Http\Controllers\ACLController@onDeleting'
		);
	}
}
