<?php

namespace App\Listeners;

// Auth::id() must not return zero! This breaks authorization.

use Model;
use Auth;
use HttpException;

class ModelEventListener {
	function onCreating (Model $model) {
		$user = Auth::user();

		if ($user && $user->is_admin) return;
		if ($model->guardCreate === false) return;

		if ( ! in_array($user->id, $model->user_ids, true)) {
			throw new HttpException(401, 'Not allowed to create entity.');
		}
	}

	function onUpdating (Model $model) {
		$user = Auth::user();

		if ($user && $user->is_admin) return;
		if ($model->guardUpdate === false) return;

		$original = $model->findOrFail($model->getOriginal()['id']);

		if ( ! in_array($user->id, $original->user_ids, true)) {
			throw new HttpException(401, 'Not allowed to modify entity.');
		}
		if ( ! in_array($user->id, $model->user_ids, true)) {
			throw new HttpException(401, 'Not allowed to modify entity.');
		}
	}

	function onDeleting (Model $model) {
		$user = Auth::user();

		if ($user && $user->is_admin) return;
		if ($model->guardDelete === false) return;

		if ( ! in_array($user->id, $model->user_ids, true)) {
			throw new HttpException(401, 'Not allowed to delete entity.');
		}
	}

	function subscribe ($events) {
		$events->listen(
			'eloquent.creating: *',
			'App\Listeners\ModelEventListener@onCreating'
		);
		$events->listen(
			'eloquent.updating: *',
			'App\Listeners\ModelEventListener@onUpdating'
		);
		$events->listen(
			'eloquent.deleting: *',
			'App\Listeners\ModelEventListener@onDeleting'
		);
	}
}
